<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $middle_initial = $conn->real_escape_string($_POST['middle_initial']);
    $suffix = $conn->real_escape_string($_POST['suffix']);
    $age = (int)$_POST['age'];
    $gender = $conn->real_escape_string($_POST['gender']);
    $location = $conn->real_escape_string($_POST['location']);
    $availability = $conn->real_escape_string($_POST['availability']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $want_to_learn = $conn->real_escape_string($_POST['want_to_learn']);

    // Update users_profile
    $update_sql = "UPDATE users_profile SET first_name=?, last_name=?, middle_initial=?, suffix=?, age=?, gender=?, location=?, availability=?, bio=? WHERE user_id=?";
    $stmt = $conn->prepare($update_sql);
    if (!$stmt) { die("Profile update error: " . $conn->error); }
    $stmt->bind_param("sssssssssi", $first_name, $last_name, $middle_initial, $suffix, $age, $gender, $location, $availability, $bio, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update education
    if (isset($_POST['course'], $_POST['year'], $_POST['block'], $_POST['status'])) {
        $course = $conn->real_escape_string($_POST['course']);
        $year = $conn->real_escape_string($_POST['year']);
        $block = $conn->real_escape_string($_POST['block']);
        $status = $conn->real_escape_string($_POST['status']);

        $edu_sql = "UPDATE education SET course=?, year=?, block=?, status=? WHERE user_id=?";
        $stmt = $conn->prepare($edu_sql);
        if (!$stmt) { die("Education update error: " . $conn->error); }
        $stmt->bind_param("ssssi", $course, $year, $block, $status, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Update or Insert want_to_learn in learning_goals
    if (!empty($want_to_learn)) {
        $check_sql = "SELECT user_id FROM learning_goals WHERE user_id = ? LIMIT 1";
        $stmt = $conn->prepare($check_sql);
        if (!$stmt) { die("Learning goal check error: " . $conn->error); }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $update_want = "UPDATE learning_goals SET want_to_learn = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_want);
            if (!$stmt) { die("Learning goal update error: " . $conn->error); }
            $stmt->bind_param("si", $want_to_learn, $user_id);
        } else {
            $insert_want = "INSERT INTO learning_goals (user_id, want_to_learn) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_want);
            if (!$stmt) { die("Learning goal insert error: " . $conn->error); }
            $stmt->bind_param("is", $user_id, $want_to_learn);
        }
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href='user_profile.php';</script>";
    exit();
}

// Fetch user profile
$sql = "SELECT last_name, first_name, middle_initial, suffix, age, gender, location, availability, bio FROM users_profile WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) { die("Profile fetch error: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

// Fetch want_to_learn from learning_goals
$want_to_learn = '';
$want_sql = "SELECT want_to_learn FROM learning_goals WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($want_sql);
if (!$stmt) { die("Learning goal fetch error: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $want_to_learn = $row['want_to_learn'];
}
$stmt->close();

// Philippine places for dropdown
$philippines_places = [
    "Manila", "Quezon City", "Caloocan", "Pasig", "Makati", "Taguig", "Pasay", "Parañaque",
    "Las Piñas", "San Juan", "Mandaluyong", "Marikina", "Muntinlupa", "Navotas", "Valenzuela"
];

// Count notifications
$countQuery = "
    SELECT COUNT(DISTINCT ul.user_id) as notif_count
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
$stmt = $conn->prepare($countQuery);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$notif_count = $row['notif_count'] ?? 0;
$stmt->close();

// Add: Count unread messages for badge
$unread_msg_count = 0;
$msgCountQuery = "SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = ? AND is_read = FALSE";
$stmt = $conn->prepare($msgCountQuery);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $unread_msg_count = $row['unread_count'] ?? 0;
    $stmt->close();
}

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

// Save profile fields to session
if ($profile) {
    $_SESSION['age'] = $profile['age'];
    $_SESSION['gender'] = $profile['gender'];
    $_SESSION['location'] = $profile['location'];
    $_SESSION['availability'] = $profile['availability'];
    $_SESSION['bio'] = $profile['bio'];
}

// Fetch education
$sql = "SELECT course, year, block, status FROM education WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) { die("Education fetch error: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$education = $result->fetch_assoc();
$stmt->close();

if ($education) {
    $_SESSION['course'] = $education['course'];
    $_SESSION['year'] = $education['year'];
    $_SESSION['block'] = $education['block'];
    $_SESSION['status'] = $education['status'];
}

// Fetch skills
$stmt = $conn->prepare("SELECT skills_id, category, specific_skill FROM skills_offer WHERE user_id = ?");
if (!$stmt) { die("Skills fetch error: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skills_result = $stmt->get_result();
$skills = $skills_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch assessments
$stmt = $conn->prepare("SELECT skills_id, proficiency FROM initial_assessment WHERE user_id = ?");
if (!$stmt) { die("Assessment fetch error: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$assessment_result = $stmt->get_result();
$assessments = $assessment_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$proficiencyMap = [];
foreach ($assessments as $row) {
    $proficiencyMap[$row['skills_id']] = $row['proficiency'];
}

foreach ($skills as $skill) {
    $skill_id = $skill['skills_id'];
    $proficiency = $proficiencyMap[$skill_id] ?? 'Not Assessed';
}

// Fetch skill progression history
$progression_history = [];
$progression_query = "
    SELECT skill_category, specific_skill, old_proficiency, new_proficiency, 
           session_count, progression_date
    FROM skill_progression_log 
    WHERE user_id = ?
    ORDER BY progression_date DESC
    LIMIT 10
";

$stmt = $conn->prepare($progression_query);
if (!$stmt) { die("Skill progression fetch error: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $progression_history[] = $row;
}
$stmt->close();

// Fetch profile picture
$profilePicPath = getProfilePic($conn, $user_id);

// Function to fetch profile picture
function getProfilePic($conn, $user_id) {
    $stmt = $conn->prepare("SELECT profile_pic FROM users_profile WHERE user_id = ?");
    if (!$stmt) { die("Profile pic fetch error: " . $conn->error); }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $fetched_pic = null;
    $stmt->bind_result($fetched_pic);
    $pic = 'uploads/default.jpg';
    if ($stmt->fetch() && !empty($fetched_pic)) {
        $pic = $fetched_pic;
    }
    $stmt->close();
    return $pic;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillSynergy Profile Page</title>
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
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
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

        .notif-badge {
            background: #e53e3e;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 600;
            margin-left: auto;
            min-width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 40px 40px 20px;
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

        .content-area {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            margin: 20px 40px 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex: 1;
            padding: 30px;
        }

        .profile-section {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            margin-bottom: 30px;
        }

        .profile-pic-container {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .profile-pic-container img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }

        .upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #667eea;
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: #764ba2;
            transform: scale(1.1);
        }

        .details {
            flex: 1;
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .details h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-box {
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            flex: 1;
            min-width: 200px;
            font-size: 14px;
        }

        .info-box strong {
            color: #4a5568;
            font-weight: 600;
        }

        .bio-section {
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .bio-section h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #2d3748;
        }

        .bio-content {
            background: rgba(102, 126, 234, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            line-height: 1.6;
            color: #4a5568;
        }

        .skills-section {
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .skills-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .skills-header h3 {
            font-size: 1.5rem;
            color: #2d3748;
        }

        .stats-info {
            display: flex;
            gap: 30px;
            font-size: 14px;
            color: #4a5568;
        }

        .stat-item {
            background: rgba(102, 126, 234, 0.1);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .skill-item {
            display: flex;
            gap: 15px;
            background: rgba(102, 126, 234, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .skill-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .skill-info {
            flex: 1;
            position: relative;
        }

        .skill-info h4 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .skill-status {
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .skill-rank {
            color: #718096;
            font-size: 12px;
        }

        .skill-comments {
            flex: 1;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 15px;
            max-height: 120px;
            overflow-y: auto;
        }

        .skill-comments p {
            font-size: 13px;
            color: #4a5568;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .plus-button {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .plus-button:hover {
            background: #764ba2;
            transform: scale(1.1);
        }

        /* Edit Profile Modal Styles */
        .edit-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 2% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
            transition: color 0.3s;
        }

        .close:hover {
            color: #667eea;
        }

        .modal-title {
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: #2d3748;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .form-section h4 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #4a5568;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #4a5568;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .edit-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            margin-left: 15px;
        }

        .edit-btn:hover {
            background: #764ba2;
            transform: translateY(-1px);
        }

        .save-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .save-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .cancel-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
        }

        .cancel-btn:hover {
            background: #545b62;
            transform: translateY(-1px);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .editable-section {
            position: relative;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .profile-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .skills-grid {
                grid-template-columns: 1fr;
            }

            .skill-item {
                flex-direction: column;
            }
        }
        /* Skill Progression Styles */
        .progression-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
        }

        .progression-timeline {
            position: relative;
            padding-left: 30px;
        }

        .progression-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 20px;
            bottom: 20px;
            width: 2px;
            background: linear-gradient(to bottom, #667eea, #764ba2);
        }

        .progression-item {
            position: relative;
            margin-bottom: 30px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 3px solid #667eea;
        }

        .progression-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .progression-icon {
            position: absolute;
            left: -42px;
            top: 25px;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        .progression-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .progression-header h4 {
            color: #2d3748;
            font-weight: 600;
            margin: 0;
            font-size: 18px;
        }

        .progression-date {
            color: #718096;
            font-size: 14px;
            font-weight: 500;
        }

        .progression-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .level-change {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
        }

        .old-level, .new-level {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .old-level {
            background: #fed7d7;
            color: #c53030;
        }

        .new-level {
            background: #c6f6d5;
            color: #38a169;
        }

        .level-change i {
            color: #667eea;
            font-size: 16px;
        }

        .session-count {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4a5568;
            font-size: 14px;
        }

        .session-count i {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .progression-timeline {
                padding-left: 20px;
            }
            
            .progression-icon {
                left: -35px;
                width: 25px;
                height: 25px;
                font-size: 12px;
            }
            
            .progression-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .level-change {
                flex-wrap: wrap;
                gap: 10px;
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
                    <a href="dashboard.php" class="nav-link">
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
                        <?php if ($notif_count > 0): ?>
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
                        <?php if (isset($unread_msg_count) && $unread_msg_count > 0): ?>
                            <span class="notif-badge"><?php echo $unread_msg_count; ?></span>
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
            <div class="header">
                <h1 class="page-title">My Profile</h1>
                <div class="profile-dropdown">
                    <a href="#" class="profile-icon">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">

                <!-- Profile Section -->
                <form action="personal_info.php" method="POST" enctype="multipart/form-data">
                    <div class="profile-section editable-section">
                        <div class="profile-pic-container">
                            <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture">
                            <label for="profile_pic" class="upload-btn"><i class="fas fa-camera"></i></label>
                            <input type="file" id="profile_pic" name="profile_pic" style="display: none;" onchange="this.form.submit()">
                        </div>

                        <div class="details">
                            <h3><?php echo htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')); ?>
                                <button type="button" class="edit-btn" onclick="openEditModal()">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </button>
                                <button type="button" class="edit-btn" onclick="window.location.href='profile_setup.php'">
                                    <i class="fas fa-tasks"></i> Update Assessment
                                </button>
                            </h3>
                            <div class="info-row">
                                <div class="info-box"><strong>Age:</strong> <?php echo !empty($profile['age']) ? htmlspecialchars($profile['age']) : 'Not set'; ?></div>
                                <div class="info-box"><strong>Gender:</strong> <?php echo !empty($profile['gender']) ? htmlspecialchars($profile['gender']) : 'Not set'; ?></div>
                                <div class="info-box"><strong>Location:</strong> <?php echo !empty($profile['location']) ? htmlspecialchars($profile['location']) : 'Not set'; ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-box"><strong>Availability:</strong> <?php echo !empty($profile['availability']) ? htmlspecialchars($profile['availability']) : 'Not set'; ?></div>
                                <div class="info-box"><strong>PLM Email:</strong> <?php echo !empty($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Not set'; ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-box"><strong>Course:</strong> <?php echo !empty($education['course']) ? htmlspecialchars($education['course']) : 'Not set'; ?></div>
                                <div class="info-box"><strong>Year:</strong> <?php echo !empty($education['year']) ? htmlspecialchars($education['year']) : 'Not set'; ?></div>
                                <div class="info-box"><strong>Block:</strong> <?php echo !empty($education['block']) ? htmlspecialchars($education['block']) : 'Not set'; ?></div>
                                <div class="info-box"><strong>Status:</strong> <?php echo !empty($education['status']) ? htmlspecialchars($education['status']) : 'Not set'; ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-box"><strong>Want to Learn:</strong> <?php echo !empty($want_to_learn) ? htmlspecialchars($want_to_learn) : 'Not set'; ?></div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Bio Section -->
                <div class="bio-section editable-section">
                    <h3>Bio</h3>
                    <div class="bio-content">
                        <?php echo !empty($profile['bio']) ? htmlspecialchars($profile['bio']) : 'No bio added yet. Click edit to add a bio!'; ?>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="skills-section">
                    <div class="skills-header">
                        <h3>Skills & Performance</h3>
                        <div class="stats-info">
                        </div>
                    </div>

                    <?php if (!empty($skills)) : ?>
                        <div class="skills-grid">
                            <?php foreach ($skills as $skill) : ?>
                                <div class="skill-item">
                                    <div class="skill-info">
                                        <h4><?php echo htmlspecialchars($skill['specific_skill']); ?></h4>
                                        <div class="skill-status">
                                            <strong>Status:</strong> <?php echo htmlspecialchars($proficiency); ?>
                                        </div>
                                        <div class="skill-rank">
                                            <strong>Rank:</strong> Not ranked yet
                                        </div>
                                        <button class="plus-button" title="Add comment">+</button>
                                    </div>

                                    <div class="skill-comments">
                                        <?php
                                        if (!empty($comments[$skill['skills_id']])) {
                                            foreach ($comments[$skill['skills_id']] as $comment_text) {
                                                echo "<p>" . htmlspecialchars($comment_text) . "</p>";
                                            }
                                        } else {
                                            echo "<p>No comments yet. Be the first to add feedback!</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div style="text-align: center; padding: 40px; color: #718096;">
                            <i class="fas fa-plus-circle" style="font-size: 3rem; margin-bottom: 20px; color: #667eea;"></i>
                            <h4 style="margin-bottom: 10px;">No Skills Added Yet</h4>
                            <p>Start by adding your skills to connect with other students and showcase your expertise!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Skill Progression History Section -->
    <?php if (!empty($progression_history)) : ?>
    <div class="container">
        <div class="profile-section">
            <div class="section-header">
                <h3><i class="fas fa-chart-line"></i> Skill Progression History</h3>
                <p class="section-description">Track your skill improvement journey and milestones</p>
            </div>
            
            <div class="progression-container">
                <div class="progression-timeline">
                    <?php foreach ($progression_history as $progression) : ?>
                        <div class="progression-item">
                            <div class="progression-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="progression-content">
                                <div class="progression-header">
                                    <h4><?php echo htmlspecialchars($progression['specific_skill']); ?> Level Up!</h4>
                                    <span class="progression-date"><?php echo date('M j, Y', strtotime($progression['progression_date'])); ?></span>
                                </div>
                                <div class="progression-details">
                                    <div class="level-change">
                                        <span class="old-level"><?php echo htmlspecialchars($progression['old_proficiency']); ?></span>
                                        <i class="fas fa-arrow-right"></i>
                                        <span class="new-level"><?php echo htmlspecialchars($progression['new_proficiency']); ?></span>
                                    </div>
                                    <div class="session-count">
                                        <i class="fas fa-graduation-cap"></i>
                                        After <?php echo $progression['session_count']; ?> completed sessions
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="edit-modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2 class="modal-title">Edit Profile</h2>
            
            <form method="POST" action="">
                <input type="hidden" name="update_profile" value="1">
                
                <!-- Personal Information -->
                <div class="form-section">
                    <h4>Personal Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" id="middle_initial" name="middle_initial" value="<?php echo htmlspecialchars($profile['middle_initial'] ?? ''); ?>" maxlength="1">
                        </div>
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" id="suffix" name="suffix" value="<?php echo htmlspecialchars($profile['suffix'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($profile['age'] ?? ''); ?>" min="16" max="100" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo ($profile['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($profile['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Prefer not to say" <?php echo ($profile['gender'] ?? '') === 'Prefer not to say' ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <select id="location" name="location" required>
                                <option value="">Select Location</option>
                                <?php foreach ($philippines_places as $place): ?>
                                    <option value="<?php echo $place; ?>" <?php echo ($profile['location'] ?? '') === $place ? 'selected' : ''; ?>>
                                        <?php echo $place; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="availability">Availability</label>
                            <select id="availability" name="availability" required>
                                <option value="">Select Availability</option>
                                <option value="Weekdays" <?php echo ($profile['availability'] ?? '') === 'Weekdays' ? 'selected' : ''; ?>>Weekdays</option>
                                <option value="Weekends" <?php echo ($profile['availability'] ?? '') === 'Weekends' ? 'selected' : ''; ?>>Weekends</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" placeholder="Tell us about yourself and your skills..."><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Education Information -->
                <div class="form-section">
                    <h4>Education Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="course">Course</label>
                            <select id="course" name="course" required>
                                <option value="">Select Course</option>
                                <option value="Bachelor of Science in Information Technology" <?php echo ($education['course'] ?? '') === 'Bachelor of Science in Information Technology' ? 'selected' : ''; ?>>BS Information Technology</option>
                                <option value="Bachelor of Science in Computer Science" <?php echo ($education['course'] ?? '') === 'Bachelor of Science in Computer Science' ? 'selected' : ''; ?>>BS Computer Science</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year</label>
                            <select id="year" name="year" required>
                                <option value="">Select Year</option>
                                <?php
                                $years = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', '6th Year'];
                                foreach ($years as $y) {
                                    $selected = ($education['year'] ?? '') === $y ? 'selected' : '';
                                    echo "<option value=\"$y\" $selected>$y</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="block">Block</label>
                            <select id="block" name="block" required>
                                <option value="">Select Block</option>
                                <?php
                                for ($i = 1; $i <= 6; $i++) {
                                    $selected = ($education['block'] ?? '') == $i ? 'selected' : '';
                                    echo "<option value=\"$i\" $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Regular" <?php echo ($education['status'] ?? '') === 'Regular' ? 'selected' : ''; ?>>Regular</option>
                                <option value="Irregular" <?php echo ($education['status'] ?? '') === 'Irregular' ? 'selected' : ''; ?>>Irregular</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Learning Goals -->
                <div class="form-section">
                    <h4>Learning Goals</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="want_to_learn">What do you want to learn?</label>
                            <select id="want_to_learn" name="want_to_learn" onchange="handleEditModalLearningGoalChange()" required>
                                <option value="">Select what you want to learn</option>
                                <option value="Python" <?php echo $want_to_learn === 'Python' ? 'selected' : ''; ?>>Python</option>
                                <option value="Java" <?php echo $want_to_learn === 'Java' ? 'selected' : ''; ?>>Java</option>
                                <option value="C" <?php echo $want_to_learn === 'C' ? 'selected' : ''; ?>>C</option>
                                <option value="C++" <?php echo $want_to_learn === 'C++' ? 'selected' : ''; ?>>C++</option>
                                <option value="JavaScript" <?php echo $want_to_learn === 'JavaScript' ? 'selected' : ''; ?>>JavaScript</option>
                                <option value="PHP" <?php echo $want_to_learn === 'PHP' ? 'selected' : ''; ?>>PHP</option>
                                <option value="CSS" <?php echo $want_to_learn === 'CSS' ? 'selected' : ''; ?>>CSS</option>
                                <option value="HTML" <?php echo $want_to_learn === 'HTML' ? 'selected' : ''; ?>>HTML</option>
                                <option value="React" <?php echo $want_to_learn === 'React' ? 'selected' : ''; ?>>React</option>
                                <option value="Node.js" <?php echo $want_to_learn === 'Node.js' ? 'selected' : ''; ?>>Node.js</option>
                                <option value="Database" <?php echo $want_to_learn === 'Database' ? 'selected' : ''; ?>>Database</option>
                                <option value="SQL" <?php echo $want_to_learn === 'SQL' ? 'selected' : ''; ?>>SQL</option>
                                <option value="NoSQL" <?php echo $want_to_learn === 'NoSQL' ? 'selected' : ''; ?>>NoSQL</option>
                                <option value="MongoDB" <?php echo $want_to_learn === 'MongoDB' ? 'selected' : ''; ?>>MongoDB</option>
                            </select>
                            
                            <!-- Database subcategory selection for edit modal -->
                            <div id="edit-database-subcategory" style="display: none; margin-top: 10px;">
                                <select name="database_type" id="edit_database_type_select" onchange="handleEditModalDatabaseTypeChange()">
                                    <option value="">Select Database Type</option>
                                    <option value="relational">Relational Databases</option>
                                    <option value="non-relational">Non-Relational Databases</option>
                                </select>
                            </div>
                            
                            <!-- Specific database skills for edit modal -->
                            <div id="edit-specific-database-skills" style="display: none; margin-top: 10px;">
                                <select name="specific_database_skill" id="edit_specific_database_skill_select">
                                    <option value="">Select Specific Skill</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="save-btn">Save Changes</button>
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clear message badge when notification tab is clicked
            const notifTab = document.querySelector(".nav-item a[href='notificationtab.php']");
            if (notifTab) {
                notifTab.addEventListener('click', function() {
                    const msgBadge = document.querySelector(".nav-item a[href='message.php'] .notif-badge, .nav-item a[href='message.php'] .msg-badge");
                    if (msgBadge) {
                        msgBadge.textContent = '';
                        msgBadge.style.display = 'none';
                    }
                });
            }
            // Clear notification badge when matched tab is clicked
            const matchedTab = document.querySelector(".nav-item a[href='matched_tab.php']");
            if (matchedTab) {
                matchedTab.addEventListener('click', function() {
                    const notifBadge = document.querySelector(".nav-item a[href='notificationtab.php'] .notif-badge");
                    if (notifBadge) {
                        notifBadge.textContent = '';
                        notifBadge.style.display = 'none';
                    }
                });
            }
        });

        document.getElementById('profile_pic').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert("Invalid file type. Please select an image file.");
                    this.value = ''; // reset input
                } else if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    alert("File is too large. Please select a file under 2MB.");
                    this.value = '';
                }
            }
        });

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });

        // Handle Database category selection in edit modal
        function handleEditModalLearningGoalChange() {
            const wantToLearn = document.getElementById('want_to_learn').value;
            const databaseSubcategory = document.getElementById('edit-database-subcategory');
            const specificDatabaseSkills = document.getElementById('edit-specific-database-skills');
            
            if (wantToLearn === 'Database') {
                databaseSubcategory.style.display = 'block';
                specificDatabaseSkills.style.display = 'none';
            } else {
                databaseSubcategory.style.display = 'none';
                specificDatabaseSkills.style.display = 'none';
                // Reset selections when not Database
                document.getElementById('edit_database_type_select').value = '';
                document.getElementById('edit_specific_database_skill_select').value = '';
            }
        }

        // Handle Database type selection (relational/non-relational) in edit modal
        function handleEditModalDatabaseTypeChange() {
            const databaseType = document.getElementById('edit_database_type_select').value;
            const specificSkillsDiv = document.getElementById('edit-specific-database-skills');
            const specificSkillSelect = document.getElementById('edit_specific_database_skill_select');
            
            if (databaseType) {
                specificSkillsDiv.style.display = 'block';
                
                // Clear previous options
                specificSkillSelect.innerHTML = '<option value="">Select Specific Skill</option>';
                
                // Add options based on database type
                if (databaseType === 'relational') {
                    const relationalSkills = ['SQL', 'MySQL', 'PostgreSQL', 'Oracle Database', 'SQL Server'];
                    relationalSkills.forEach(skill => {
                        const option = document.createElement('option');
                        option.value = skill;
                        option.textContent = skill;
                        specificSkillSelect.appendChild(option);
                    });
                } else if (databaseType === 'non-relational') {
                    const nonRelationalSkills = ['MongoDB', 'NoSQL', 'Cassandra', 'Redis', 'DynamoDB'];
                    nonRelationalSkills.forEach(skill => {
                        const option = document.createElement('option');
                        option.value = skill;
                        option.textContent = skill;
                        specificSkillSelect.appendChild(option);
                    });
                }
            } else {
                specificSkillsDiv.style.display = 'none';
                specificSkillSelect.value = '';
            }
        }

        // Initialize Database selection on modal open
        function openEditModal() {
            document.getElementById('editModal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            // Initialize Database category handling
            handleEditModalLearningGoalChange();
        }
    </script>
</body>
</html>