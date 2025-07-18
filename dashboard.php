<?php
// Ensure $user_id is defined, typically from a session or authentication system
// For demonstration, we'll set a placeholder. Replace with your actual user ID retrieval.
$user_id = $_SESSION['user_id'] ?? 1; // Example: get from session, default to 1 if not set

// Calculate notification count
// This is a placeholder. You need to implement your actual notification count logic here.
// For example, querying a database for unread notifications for the current user.
$notif_count = 0; // Initialize to 0

// Example: Fetch actual notification count from database (uncomment and modify as needed)
/*
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error === false) {
    $notifQuery = "SELECT COUNT(*) as count FROM notifications WHERE user_id = $user_id AND is_read = FALSE";
    $notifResult = $conn->query($notifQuery);
    if ($notifResult && $notifRow = $notifResult->fetch_assoc()) {
        $notif_count = $notifRow['count'] ?? 0;
    }
    $conn->close();
}
*/

// Calculate unread messages for badge (unique senders)
$conn = new mysqli('localhost', 'root', '', 'sia1');
$unique_unread_senders = 0;
if ($conn->connect_error === false) {
    $msgCountQuery = "SELECT COUNT(DISTINCT sender_id) as unique_unread_senders FROM messages WHERE receiver_id = $user_id AND is_read = FALSE";
    $msgResult = $conn->query($msgCountQuery);
    if ($msgResult && $msgRow = $msgResult->fetch_assoc()) {
        $unique_unread_senders = $msgRow['unique_unread_senders'] ?? 0;
    }
    $conn->close();
}
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];

// Get current user's ID
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Count like notifications (people who liked you but you haven't liked them back)
$likeCountQuery = "
    SELECT COUNT(DISTINCT ul.user_id) as like_notif_count
    FROM user_likes ul
    WHERE ul.liked_user_id = ? 
      AND ul.action = 'like'
      AND ul.status = 'active'
      AND NOT EXISTS (
          SELECT 1 FROM user_likes ul2
          WHERE ul2.user_id = ? 
            AND ul2.liked_user_id = ul.user_id
            AND ul2.action = 'like'
            AND ul2.status = 'active'
      )
";
$stmt = $conn->prepare($likeCountQuery);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$like_notif_count = $row['like_notif_count'] ?? 0;
$stmt->close();

// Count message notifications
$messageCountQuery = "
    SELECT COUNT(*) as message_notif_count
    FROM notifications 
    WHERE user_id = ? 
      AND is_read = FALSE 
      AND notification_type = 'message'
";
$stmt = $conn->prepare($messageCountQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$message_notif_count = $row['message_notif_count'] ?? 0;
$stmt->close();

// Total notification count (only likes)
$notif_count = $like_notif_count;

// Add this after user_id is set and $conn is available
$unread_msg_count = 0;
$msgCountQuery = "SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = ? AND is_read = FALSE";
$stmt = $conn->prepare($msgCountQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_msg_count = $row['unread_count'] ?? 0;
$stmt->close();

// Add: Count new matches for badge
$matched_badge_count = 0;
$matched_badge_sql = "
    SELECT COUNT(*) as new_matches
    FROM users u
    JOIN user_likes ul1 ON ul1.liked_user_id = u.user_id AND ul1.user_id = $user_id AND ul1.action = 'like'
    JOIN user_likes ul2 ON ul2.user_id = u.user_id AND ul2.liked_user_id = $user_id AND ul2.action = 'like'
    WHERE u.user_id != $user_id
      AND NOT EXISTS (
          SELECT 1 FROM messages m
          WHERE (m.sender_id = $user_id AND m.receiver_id = u.user_id)
             OR (m.sender_id = u.user_id AND m.receiver_id = $user_id)
      )
";
$result = $conn->query($matched_badge_sql);
if ($result) {
    $row = $result->fetch_assoc();
    $matched_badge_count = $row['new_matches'] ?? 0;
}

// Fetch distinct specific skills for dropdown
$skills_result = $conn->query("SELECT DISTINCT specific_skill FROM skills_offer ORDER BY specific_skill ASC");
$specific_skills = [];
if ($skills_result && $skills_result->num_rows > 0) {
    while ($row = $skills_result->fetch_assoc()) {
        $specific_skills[] = $row['specific_skill'];
    }
}

// Build leaderboard query with optional filters safely (corrected proficiency_level)
$query = "
  SELECT u.user_id, 
         p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic,
         COALESCE(SUM(a.score), 0) as total_points,
         COALESCE(AVG((r.understanding_rating + r.knowledge_sharing_rating + r.listening_rating) / 3), 0) as avg_rating,
         COUNT(r.rating_id) as rating_count,
         GROUP_CONCAT(DISTINCT s.specific_skill SEPARATOR ', ') as skills,
         COUNT(DISTINCT ul.likes_id) as total_likes_given
  FROM users u
  LEFT JOIN users_profile p ON u.user_id = p.user_id
  LEFT JOIN initial_assessment a ON u.user_id = a.user_id
  LEFT JOIN user_ratings r ON u.user_id = r.rated_user_id
  LEFT JOIN skills_offer s ON u.user_id = s.user_id
  LEFT JOIN user_likes ul ON u.user_id = ul.user_id AND ul.action = 'like'
  WHERE (? = '' OR s.specific_skill = ?)
    AND (? = '' OR a.proficiency = ?)
  GROUP BY u.user_id, p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic
  ORDER BY total_points DESC
";

// Get Top Tutors query
$top_tutors_query = "
  SELECT u.user_id,
         p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic,
         AVG((r.understanding_rating + r.knowledge_sharing_rating + r.listening_rating) / 3) as avg_rating,
         COUNT(r.rating_id) as rating_count
  FROM users u
  INNER JOIN users_profile p ON u.user_id = p.user_id
  INNER JOIN user_ratings r ON u.user_id = r.rated_user_id
  GROUP BY u.user_id, p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic
  HAVING rating_count >= 1
  ORDER BY avg_rating DESC
  LIMIT 10
";

// Get Top Likers query
$top_likers_query = "
  SELECT u.user_id,
         p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic,
         COUNT(ul.likes_id) as total_likes_given
  FROM users u
  INNER JOIN users_profile p ON u.user_id = p.user_id
  INNER JOIN user_likes ul ON u.user_id = ul.user_id AND ul.action = 'like'
  GROUP BY u.user_id, p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic
  HAVING total_likes_given > 0
  ORDER BY total_likes_given DESC
  LIMIT 10
";

// Get Top Assessments query
$top_assessments_query = "
  SELECT u.user_id,
         p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic,
         AVG(ar.percentage) as avg_assessment_score,
         COUNT(ar.id) as assessment_count,
         MAX(ar.percentage) as best_score
  FROM users u
  INNER JOIN users_profile p ON u.user_id = p.user_id
  INNER JOIN assessment_results ar ON u.user_id = ar.rated_user_id
  GROUP BY u.user_id, p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic
  HAVING assessment_count >= 1
  ORDER BY avg_assessment_score DESC
  LIMIT 10
";

// Prepare statement and check for errors
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Leaderboard query preparation failed: " . $conn->error);
}

// Get filter values from GET, default to empty string if not set
$specificSkill = $_GET['specificSkill'] ?? '';
$level = $_GET['level'] ?? '';

// Bind parameters for filters
$stmt->bind_param("ssss", $specificSkill, $specificSkill, $level, $level);

// Execute and fetch leaderboard result
$stmt->execute();
$result = $stmt->get_result();

// Execute Top Tutors query
$top_tutors_stmt = $conn->prepare($top_tutors_query);
if (!$top_tutors_stmt) {
    die("Top Tutors query preparation failed: " . $conn->error);
}
$top_tutors_stmt->execute();
$top_tutors_result = $top_tutors_stmt->get_result();

// Execute Top Likers query
$top_likers_stmt = $conn->prepare($top_likers_query);
if (!$top_likers_stmt) {
    die("Top Likers query preparation failed: " . $conn->error);
}
$top_likers_stmt->execute();
$top_likers_result = $top_likers_stmt->get_result();

// Execute Top Assessments query
$top_assessments_stmt = $conn->prepare($top_assessments_query);
if (!$top_assessments_stmt) {
    die("Top Assessments query preparation failed: " . $conn->error);
}
$top_assessments_stmt->execute();
$top_assessments_result = $top_assessments_stmt->get_result();

// Add assessment points to total_points in leaderboard and YOUR STATS
// Fetch assessment points for all users
$assessment_points = [];
$assessment_points_query = "SELECT user_id, COALESCE(SUM(score),0) as assessment_points FROM assessment_attempts GROUP BY user_id";
$ap_result = $conn->query($assessment_points_query);
if ($ap_result && $ap_result->num_rows > 0) {
    while ($ap_row = $ap_result->fetch_assoc()) {
        $assessment_points[$ap_row['user_id']] = $ap_row['assessment_points'];
    }
}

// Get current user's specific stats for mini display
$current_user_query = "
  SELECT u.user_id, 
         p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic,
         COALESCE(SUM(a.score), 0) as total_points,
         COALESCE(AVG((r.understanding_rating + r.knowledge_sharing_rating + r.listening_rating) / 3), 0) as avg_rating,
         COUNT(r.rating_id) as rating_count,
         GROUP_CONCAT(DISTINCT s.specific_skill SEPARATOR ', ') as skills
  FROM users u
  LEFT JOIN users_profile p ON u.user_id = p.user_id
  LEFT JOIN initial_assessment a ON u.user_id = a.user_id
  LEFT JOIN user_ratings r ON u.user_id = r.rated_user_id
  LEFT JOIN skills_offer s ON u.user_id = s.user_id
  WHERE u.user_id = ?
  GROUP BY u.user_id, p.last_name, p.first_name, p.middle_initial, p.suffix, p.profile_pic
";

$current_user_stmt = $conn->prepare($current_user_query);
$current_user_stmt->bind_param("i", $user_id);
$current_user_stmt->execute();
$current_user_result = $current_user_stmt->get_result();
$current_user_data = $current_user_result->fetch_assoc();
$current_user_stmt->close();
// Ensure YOUR STATS points match leaderboard
$current_user_data['total_points'] = $assessment_points[$user_id] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillSynergy Dashboard/Leaderboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: #333;
    }

    .container {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 280px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        padding: 30px 0;
        position: fixed;
        height: 100vh;
        left: 0;
        top: 0;
        z-index: 100;
        box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
    }

    .logo {
        text-align: center;
        margin-bottom: 40px;
        padding: 0 30px;
    }

    .logo img {
        width: 140px;
        height: auto;
    }

    .nav-menu {
        list-style: none;
        padding: 0 20px;
    }

    .nav-item {
        margin: 8px 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: #4a5568;
        text-decoration: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
    }

    .nav-link i {
        width: 24px;
        margin-right: 15px;
        font-size: 18px;
    }

    .nav-link:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transform: translateX(5px);
    }

    .nav-link.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-weight: 600;
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 30px;
        background: white;
        border-radius: 0 4px 4px 0;
    }

    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 40px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .content-wrapper {
        flex: 1;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-dropdown {
        position: relative;
    }

    .profile-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(20px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
        text-decoration: none;
    }

    .profile-icon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 60px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        min-width: 150px;
    }

    .dropdown-content a {
        display: block;
        padding: 15px 20px;
        color: #4a5568;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .dropdown-content a:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .profile-dropdown:hover .dropdown-content {
        display: block;
    }

    .notif-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: linear-gradient(135deg, #fc8181, #e53e3e);
        color: white;
        border-radius: 50%;
        padding: 4px 8px;
        font-size: 12px;
        font-weight: 600;
        min-width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .leaderboard-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .filters {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
    }

    .filter-controls {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: center;
    }

    .mini-user-stats {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border: 2px solid #667eea;
        border-radius: 15px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #4a5568;
        min-width: 200px;
        margin-left: auto;
    }

    .mini-user-stats:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .mini-user-stats .mini-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #667eea;
    }

    .mini-user-stats .mini-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .mini-user-stats .mini-name {
        font-weight: 600;
        color: #667eea;
        font-size: 12px;
    }

    .mini-user-stats .mini-details {
        font-size: 11px;
        color: #718096;
    }

    .mini-user-stats .mini-rank {
        background: #667eea;
        color: white;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 600;
        margin-left: auto;
    }

    .filters select {
        padding: 12px 20px;
        border-radius: 15px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        font-size: 16px;
        background: white;
        color: #4a5568;
        font-weight: 500;
        transition: all 0.3s ease;
        min-width: 180px;
    }

    .filters select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .leaderboard-container table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 15px;
        font-size: 16px;
        font-weight: 500;
        color: #2d3748;
    }

    .leaderboard-container thead th {
        text-align: center;
        padding: 20px 15px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-weight: 600;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .leaderboard-container thead th:first-child {
        border-radius: 15px 0 0 15px;
    }

    .leaderboard-container thead th:last-child {
        border-radius: 0 15px 15px 0;
    }

    .leaderboard-container tbody tr {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .leaderboard-container tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .leaderboard-container td {
        padding: 20px 15px;
        vertical-align: middle;
        text-align: center;
    }

    .leaderboard-container td:first-child {
        border-radius: 15px 0 0 15px;
        font-size: 20px;
        font-weight: 700;
    }

    .leaderboard-container td:last-child {
        border-radius: 0 15px 15px 0;
    }

    .leaderboard-container td img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f7fafc;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .skills {
        font-size: 12px;
        color: #718096;
        font-style: italic;
        max-width: 150px;
        word-wrap: break-word;
    }

    .top-tutor {
        color: #e67e22;
        font-weight: 600;
        font-size: 12px;
    }

    .top-liker {
        color: #e74c3c;
        font-weight: 600;
        font-size: 12px;
    }

    .top-assessment {
        color: #3498db;
        font-weight: 600;
        font-size: 12px;
    }

    .current-user-row {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15)) !important;
        border: 2px solid #667eea !important;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2) !important;
        font-weight: 600;
    }

    .you-indicator {
        color: #667eea;
        font-weight: 700;
        font-size: 14px;
        background: rgba(102, 126, 234, 0.2);
        padding: 3px 8px;
        border-radius: 8px;
        margin-left: 5px;
        display: inline-block;
    }

    /* Current user highlighting styles */

    /* Add padding to main content to prevent sticky footer overlap */
    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 40px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin-top: auto;
        padding: 40px 0 20px;
        flex-wrap: wrap;
    }

    .footer-links a {
        text-decoration: none;
        color: white;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(20px);
        padding: 15px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .footer-links a:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .main-content {
            margin-left: 0;
            padding: 40px 20px;
        }

        .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-controls {
            flex-direction: column;
            gap: 15px;
        }

        .filters select {
            min-width: 100%;
        }

        .mini-user-stats {
            min-width: 100%;
            justify-content: space-between;
            margin-left: 0;
            order: -1; /* Move to top on mobile */
        }
    }
  </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="logo-profilepage.jpg" alt="SkillSynergy">
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="findmatch.php" class="nav-link">
                        <i class="fas fa-search"></i>
                        Find Match
                    </a>
                </li>
                <li class="nav-item">
                    <a href="notificationtab.php" class="nav-link">
                        <i class="fas fa-bell"></i>
                        Notifications
                        <?php if (
                            isset($notif_count) && $notif_count > 0): ?>
                            <span class="notif-badge"><?= $notif_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="matched_tab.php" class="nav-link">
                        <i class="fas fa-user-friends"></i>
                        Matched
                    </a>
                </li>
                <li class="nav-item">
                    <a href="message.php" class="nav-link">
                        <i class="fas fa-comment-dots"></i>
                        Messages
                        <?php if (
                            isset($unread_msg_count) && $unread_msg_count > 0): ?>
                            <span class="notif-badge"><?= $unread_msg_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="lesson_assessment.php" class="nav-link">
                        <i class="fas fa-book"></i>
                        Lesson & Assessment
                    </a>
                </li>
                <li class="nav-item" style="margin-top: 20px;">
                    <a href="logout.php" class="nav-link" onclick="return customLogoutConfirm(event);">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="content-wrapper">
                <div class="header">
                    <h1 class="page-title">Dashboard & Leaderboard</h1>
                    <div class="profile-dropdown">
                        <a href="user_profile.php" class="profile-icon">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                </div>

<div class="leaderboard-container">
  <form method="GET" id="filterForm">
    <div class="filters">
      <div class="filter-controls">
        <label>
          <select name="specificSkill" id="specificSkillDropdown" onchange="document.getElementById('filterForm').submit()">
            <option value="">Select Skill</option>
            <?php foreach ($specific_skills as $skill): ?>
              <option value="<?= htmlspecialchars($skill) ?>" <?= (isset($_GET['specificSkill']) && $_GET['specificSkill'] == $skill) ? 'selected' : '' ?>>
                <?= htmlspecialchars($skill) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>

        <label>
          <select name="level" id="levelDropdown" onchange="document.getElementById('filterForm').submit()">
            <option value="">Select Level</option>
            <option value="Beginner" <?= (isset($_GET['level']) && $_GET['level'] == 'Beginner') ? 'selected' : '' ?>>Beginner</option>
            <option value="Intermediate" <?= (isset($_GET['level']) && $_GET['level'] == 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
            <option value="Advanced" <?= (isset($_GET['level']) && $_GET['level'] == 'Advanced') ? 'selected' : '' ?>>Advanced</option>
          </select>
        </label>
      </div>

      <?php if ($current_user_data): ?>
      <div class="mini-user-stats" id="miniUserStats" onclick="scrollToMyRank()">
        <img src="<?= $current_user_data['profile_pic'] ? htmlspecialchars($current_user_data['profile_pic']) : '' ?>" class="mini-avatar" alt="Your Avatar" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <span class="mini-avatar user-icon" style="display:none;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;background:#e0e0e0;color:#888;font-size:16px;"><i class="fas fa-user"></i></span>
        <div class="mini-info">
          <div class="mini-name">Your Stats</div>
          <div class="mini-details">
            <?= $current_user_data['total_points'] ?> pts • <?= $current_user_data['rating_count'] ?> ratings
            <?php if ($current_user_data['rating_count'] > 0): ?>
              <br>
              <?php
                $avg_rating = round($current_user_data['avg_rating'], 2);
                $full_stars = floor($avg_rating);
                $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                $empty_stars = 5 - $full_stars - $half_star;
              ?>
              <span style="color:#fbbf24; font-size:1.1em;">
                <?php for ($i = 0; $i < $full_stars; $i++) echo '★'; ?>
                <?php if ($half_star) echo '☆'; ?>
                <?php for ($i = 0; $i < $empty_stars; $i++) echo '☆'; ?>
              </span>
              <span style="color:#555; font-size:0.95em;">(<?= $avg_rating ?>/5)</span>
            <?php endif; ?>
          </div>
        </div>
        <div class="mini-rank" id="miniRankDisplay">Rank #?</div>
      </div>
      <?php endif; ?>
    </div>
  </form>

  <table>
    <thead>
      <tr>
        <th>Rank</th>
        <th>Avatar</th>
        <th>Name</th>
        <th>Points</th>
        <th>Skills</th>
        <th>Top Tutor</th>
        <th>Top Liker</th>
        <th>Top Assessment</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Build a lookup for top tutors
      $top_tutors_lookup = [];
      $top_tutor_rank = 1;
      if ($top_tutors_result->num_rows > 0):
        while ($tutor_row = $top_tutors_result->fetch_assoc()):
          $top_tutors_lookup[$tutor_row['user_id']] = [
            'rank' => $top_tutor_rank,
            'avg_rating' => $tutor_row['avg_rating'],
            'rating_count' => $tutor_row['rating_count']
          ];
          $top_tutor_rank++;
        endwhile;
      endif;

      // Build a lookup for top likers
      $top_likers_lookup = [];
      $top_liker_rank = 1;
      if ($top_likers_result->num_rows > 0):
        while ($liker_row = $top_likers_result->fetch_assoc()):
          $top_likers_lookup[$liker_row['user_id']] = [
            'rank' => $top_liker_rank,
            'total_likes_given' => $liker_row['total_likes_given']
          ];
          $top_liker_rank++;
        endwhile;
      endif;

      // Build a lookup for top assessments
      $top_assessments_lookup = [];
      $top_assessment_rank = 1;
      if ($top_assessments_result->num_rows > 0):
        while ($assessment_row = $top_assessments_result->fetch_assoc()):
          $top_assessments_lookup[$assessment_row['user_id']] = [
            'rank' => $top_assessment_rank,
            'avg_score' => $assessment_row['avg_assessment_score'],
            'best_score' => $assessment_row['best_score'],
            'assessment_count' => $assessment_row['assessment_count']
          ];
          $top_assessment_rank++;
        endwhile;
      endif;

      if ($result->num_rows > 0):
        $rows = [];
        while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
        }
        // Sort by assessment_points descending
        usort($rows, function($a, $b) use ($assessment_points) {
          $pointsA = $assessment_points[$a['user_id']] ?? 0;
          $pointsB = $assessment_points[$b['user_id']] ?? 0;
          return $pointsB <=> $pointsA;
        });
        $rank = 1;
        foreach ($rows as $row):
          $fullname = htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['suffix']);
          $profile_pic = $row['profile_pic'] ? htmlspecialchars($row['profile_pic']) : '/siaproject/default-avatar.jpg';
          $total_points = $assessment_points[$row['user_id']] ?? 0;
          $skills = $row['skills'] ? htmlspecialchars($row['skills']) : 'No skills listed';
          
          // Check if user is a top tutor and display stars based on average rating
          $top_tutor_display = '';
          
          // First check if user has any ratings from the main query
          if ($row['avg_rating'] > 0 && $row['rating_count'] > 0) {
            $user_rating = $row['avg_rating'];
            $stars = str_repeat('★', floor($user_rating)) . str_repeat('☆', 5 - floor($user_rating));
            
            // Check if they're in the top tutors list for special badge
            if (isset($top_tutors_lookup[$row['user_id']])) {
              $tutor_info = $top_tutors_lookup[$row['user_id']];
              $tutor_rank = $tutor_info['rank'];
              
              if ($tutor_rank <= 3) {
                $badge_icon = $tutor_rank == 1 ? '🏆' : ($tutor_rank == 2 ? '🥇' : '🥈');
                $top_tutor_display = $badge_icon . ' #' . $tutor_rank . '<br>' . $stars . ' (' . round($user_rating, 1) . '/5)';
              } else {
                $top_tutor_display = '⭐ Top ' . $tutor_rank . '<br>' . $stars . ' (' . round($user_rating, 1) . '/5)';
              }
            } else {
              // Show stars even if not in top 10
              $top_tutor_display = $stars . '<br>(' . round($user_rating, 1) . '/5)';
            }
          }
          
          // Check if user is a top liker
          $top_liker_display = '';
          if (isset($top_likers_lookup[$row['user_id']])) {
            $liker_info = $top_likers_lookup[$row['user_id']];
            $liker_rank = $liker_info['rank'];
            $likes_given = $liker_info['total_likes_given'];
            
            if ($liker_rank <= 3) {
              $badge_icon = $liker_rank == 1 ? '💖' : ($liker_rank == 2 ? '❤️' : '💕');
              $top_liker_display = $badge_icon . ' #' . $liker_rank . '<br>' . $likes_given . ' likes given';
            } else {
              $top_liker_display = '👍 Top ' . $liker_rank . '<br>' . $likes_given . ' likes given';
            }
          }

          // Check if user is a top assessment performer
          $top_assessment_display = '';
          if (isset($top_assessments_lookup[$row['user_id']])) {
            $assessment_info = $top_assessments_lookup[$row['user_id']];
            $assessment_rank = $assessment_info['rank'];
            $avg_score = $assessment_info['avg_score'];
            $best_score = $assessment_info['best_score'];
            $assessment_count = $assessment_info['assessment_count'];
            
            if ($assessment_rank <= 3) {
              $badge_icon = $assessment_rank == 1 ? '🧠' : ($assessment_rank == 2 ? '📚' : '🎯');
              $top_assessment_display = $badge_icon . ' #' . $assessment_rank . '<br>' . round($avg_score, 1) . '% avg<br>Best: ' . round($best_score, 1) . '%';
            } else {
              $top_assessment_display = '📊 Top ' . $assessment_rank . '<br>' . round($avg_score, 1) . '% avg<br>' . $assessment_count . ' tests';
            }
          }

          // Check if this is the current user
          $isCurrentUser = ($row['user_id'] == $user_id);
          
          // Store current user's rank for mini display
          if ($isCurrentUser) {
            $current_user_rank = $rank;
          }
      ?>
      <tr<?= $isCurrentUser ? ' class="current-user-row" id="currentUserRow"' : '' ?>>
        <td><?= ($rank == 1) ? '🥇' : (($rank == 2) ? '🥈' : (($rank == 3) ? '🥉' : $rank)) ?></td>
        <td>
<?php if ($profile_pic && file_exists($profile_pic) && strpos($profile_pic, 'default-avatar.jpg') === false): ?>
    <img src="<?= $profile_pic ?>" class="avatar" alt="Avatar">
<?php else: ?>
    <span class="avatar user-icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;background:#bdbdbd;color:#fff;font-size:32px;">
        <i class="fas fa-user"></i>
    </span>
<?php endif; ?>
</td>
        <td><?= $fullname ?><?= $isCurrentUser ? ' <span class="you-indicator">(You)</span>' : '' ?></td>
        <td><?= $total_points ?></td>
        <td class="skills"><?= $skills ?></td>
        <td class="top-tutor"><?= $top_tutor_display ?: '-' ?></td>
        <td class="top-liker"><?= $top_liker_display ?: '-' ?></td>
        <td class="top-assessment"><?= $top_assessment_display ?: '-' ?></td>
      </tr>
      <?php $rank++; endforeach; endif; ?>
    </tbody>
  </table>

</div>
            </div>

            <div class="footer-links"> 
                <a href="FAQs.php">FAQs</a>
                <a href="Manual.php">MANUAL</a>
            </div>
        </div>
    </div>

<!-- Include Socket.IO client -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update the mini rank display with the actual rank
    <?php if (isset($current_user_rank)): ?>
    const miniRankDisplay = document.getElementById('miniRankDisplay');
    if (miniRankDisplay) {
        miniRankDisplay.textContent = 'Rank #<?= $current_user_rank ?>';
    }
    <?php endif; ?>

    // Initialize socket connection for real-time notification
    const userId = <?= $_SESSION['user_id'] ?>;
    const socket = io('http://localhost:3000'); // your Node.js socket server

    socket.on('connect', () => {
        console.log('Connected to Socket.IO server');
        socket.emit('register', userId); // register user to their channel/room
    });

    socket.on('new_notification', message => {
        alert("🔔 New notification: " + message);

        // Optional: update a badge count
        const notifBadge = document.getElementById('notif-badge');
        if (notifBadge) {
            let count = parseInt(notifBadge.textContent) || 0;
            notifBadge.textContent = count + 1;
        }

        // Optional: append to a notification list
        const notifList = document.getElementById('notif-list');
        if (notifList) {
            const li = document.createElement('li');
            li.textContent = message;
            notifList.prepend(li);
        }
    });
});

// Function to scroll to current user's rank in the leaderboard
function scrollToMyRank() {
    const currentUserRow = document.getElementById('currentUserRow');
    if (currentUserRow) {
        currentUserRow.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        currentUserRow.style.transform = 'scale(1.02)';
        currentUserRow.style.transition = 'transform 0.3s ease';
        setTimeout(() => {
            currentUserRow.style.transform = 'scale(1)';
        }, 500);
    }
}
</script>

<?php
// Clean up prepared statements
if (isset($stmt)) {
    $stmt->close();
}
if (isset($top_tutors_stmt)) {
    $top_tutors_stmt->close();
}
if (isset($top_likers_stmt)) {
    $top_likers_stmt->close();
}
if (isset($top_assessments_stmt)) {
    $top_assessments_stmt->close();
}
$conn->close();
?>

<!-- Custom Confirm Modal -->
<div id="customConfirmModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <span id="customConfirmMessage"></span>
    <div class="custom-modal-actions">
      <button id="customConfirmOk" class="custom-modal-btn ok">OK</button>
      <button id="customConfirmCancel" class="custom-modal-btn cancel">Cancel</button>
    </div>
  </div>
</div>
<style>
.custom-modal {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.35);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.custom-modal-content {
  background: #fff;
  border-radius: 16px;
  padding: 32px 28px 24px 28px;
  box-shadow: 0 8px 32px rgba(102,126,234,0.18);
  min-width: 320px;
  max-width: 90vw;
  text-align: center;
}
.custom-modal-actions {
  margin-top: 24px;
  display: flex;
  justify-content: center;
  gap: 18px;
}
.custom-modal-btn {
  padding: 8px 28px;
  border-radius: 8px;
  border: none;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.custom-modal-btn.ok {
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
}
.custom-modal-btn.cancel {
  background: #f3f3f3;
  color: #333;
}
.custom-modal-btn.ok:hover {
  background: linear-gradient(135deg, #5a67d8, #6b46c1);
}
.custom-modal-btn.cancel:hover {
  background: #e2e8f0;
}
</style>
<script>
function showConfirmModal(message, onConfirm) {
  const modal = document.getElementById('customConfirmModal');
  const msg = document.getElementById('customConfirmMessage');
  const okBtn = document.getElementById('customConfirmOk');
  const cancelBtn = document.getElementById('customConfirmCancel');
  msg.textContent = message;
  modal.style.display = 'flex';
  function cleanup() {
    modal.style.display = 'none';
    okBtn.removeEventListener('click', okHandler);
    cancelBtn.removeEventListener('click', cancelHandler);
  }
  function okHandler() { cleanup(); onConfirm(true); }
  function cancelHandler() { cleanup(); onConfirm(false); }
  okBtn.addEventListener('click', okHandler);
  cancelBtn.addEventListener('click', cancelHandler);
}
function customLogoutConfirm(e) {
  e.preventDefault();
  showConfirmModal('Are you sure you want to logout?', function(confirmed) {
    if (confirmed) window.location.href = 'logout.php';
  });
  return false;
}
</script>

</body>
</html>