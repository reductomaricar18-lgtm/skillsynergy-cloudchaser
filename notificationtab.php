<?php
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

// Count notifications (people who liked you but you haven't liked them back)
$countQuery = "
    SELECT COUNT(*) as notif_count
    FROM user_likes ul
    WHERE ul.liked_user_id = ? 
      AND ul.action = 'like'
      AND ul.user_id NOT IN (
          SELECT liked_user_id FROM user_likes WHERE user_id = ? AND action = 'like'
      )
";
$stmt = $conn->prepare($countQuery);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch people who liked you (People who want to learn with you)
// Only show users who liked you but you haven't liked them back
$notifications = [];
$query = "
    SELECT u.user_id, up.first_name, up.last_name, up.profile_pic, e.course
    FROM user_likes ul
    JOIN users u ON ul.user_id = u.user_id
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN education e ON u.user_id = e.user_id
    WHERE ul.liked_user_id = ? AND ul.action = 'like'
      AND ul.user_id NOT IN (
          SELECT liked_user_id FROM user_likes WHERE user_id = ? AND action = 'like'
      )
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
// Deduplicate notifications by user_id
$seen_notifications = [];
while ($row = $result->fetch_assoc()) {
    if (!isset($seen_notifications[$row['user_id']])) {
        $notifications[] = $row;
        $seen_notifications[$row['user_id']] = true;
    }
}
$stmt->close();
// Set notif_count to unique users after deduplication
$notif_count = count($notifications);

// Fetch people who liked you (People who want to learn with you)
// Only show users who liked you but you haven't liked them back
$notifications = [];
$query = "
    SELECT u.user_id, up.first_name, up.last_name, up.profile_pic, e.course
    FROM user_likes ul
    JOIN users u ON ul.user_id = u.user_id
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN education e ON u.user_id = e.user_id
    WHERE ul.liked_user_id = ? AND ul.action = 'like'
      AND ul.user_id NOT IN (
          SELECT liked_user_id FROM user_likes WHERE user_id = ? AND action = 'like'
      )
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
// Deduplicate notifications by user_id
$seen_notifications = [];
while ($row = $result->fetch_assoc()) {
    if (!isset($seen_notifications[$row['user_id']])) {
        $notifications[] = $row;
        $seen_notifications[$row['user_id']] = true;
    }
}
$stmt->close();

// Fetch people you liked (only those who haven't liked you back)
$liked_users = [];
$query2 = "
    SELECT DISTINCT u.user_id, up.first_name, up.last_name, up.profile_pic, e.course
    FROM user_likes ul
    JOIN users u ON ul.liked_user_id = u.user_id
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN education e ON u.user_id = e.user_id
    WHERE ul.user_id = ? AND ul.action = 'like'
      AND ul.liked_user_id NOT IN (
          SELECT user_id FROM user_likes WHERE liked_user_id = ? AND action = 'like'
      )
";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("ii", $user_id, $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
// Deduplicate liked users by user_id
$seen_liked = [];
while ($row = $result2->fetch_assoc()) {
    if (!isset($seen_liked[$row['user_id']])) {
        $liked_users[] = $row;
        $seen_liked[$row['user_id']] = true;
    }
}
$stmt2->close();

// Calculate unread messages for badge (unique senders)
$unique_unread_senders = 0;
$msgCountQuery = "SELECT COUNT(DISTINCT sender_id) as unique_unread_senders FROM messages WHERE receiver_id = ? AND is_read = FALSE";
$msgStmt = $conn->prepare($msgCountQuery);
if ($msgStmt) {
    $msgStmt->bind_param("i", $user_id);
    $msgStmt->execute();
    $msgResult = $msgStmt->get_result();
    if ($msgResult && $msgRow = $msgResult->fetch_assoc()) {
        $unique_unread_senders = $msgRow['unique_unread_senders'] ?? 0;
    }
    $msgStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>SkillSynergy - Notifications</title>
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
        }

        .profile-icon:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
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

        /* Ensure only one profile icon is visible */
        .profile-dropdown:not(#main-profile-dropdown) {
            display: none !important;
        }
        
        .profile-icon:not(#main-profile-icon) {
            display: none !important;
        }

        .content-grid {
            display: grid;
            gap: 40px;
        }

        .section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 15px;
            color: #667eea;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .person-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        .person-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .person-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            object-fit: cover;
            border: 4px solid #f7fafc;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .person-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .person-course {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #e2e8f0;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #4a5568;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            border-radius: 25px;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 24px;
            cursor: pointer;
            color: #718096;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: #e53e3e;
        }

        .modal-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 25px;
            object-fit: cover;
            border: 6px solid #f7fafc;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .modal-course {
            color: #718096;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .modal-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .action-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-reject {
            background: linear-gradient(135deg, #fc8181, #e53e3e);
            color: white;
        }

        .btn-accept {
            background: linear-gradient(135deg, #68d391, #38a169);
            color: white;
        }

        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
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

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
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
                    <a href="notificationtab.php" class="nav-link active">
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
                        <?php if ($unique_unread_senders > 0): ?>
                            <span class="notif-badge"><?= $unique_unread_senders ?></span>
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
                    ?>
                <h1 class="page-title">Notifications</h1>
                <div class="profile-dropdown" id="main-profile-dropdown">
                    <a href="user_profile.php" class="profile-icon" id="main-profile-icon">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>

            <div class="content-grid">
                <!-- People who liked you -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-heart"></i>
                        People who want to learn with you
                    </h2>
                    
                    <?php if (empty($notifications)): ?>
                        <div class="empty-state">
                            <i class="fas fa-heart"></i>
                            <h3>No new notifications</h3>
                            <p>When someone likes you, they'll appear here!</p>
                        </div>
                    <?php else: ?>
                        <div class="cards-grid">
                            <?php foreach ($notifications as $notif): ?>
                                <?php
                                $profile_pic = $notif['profile_pic'];
                                ?>
                                <div class="person-card" onclick="showDetails(
                                    '<?= htmlspecialchars($notif['first_name'] . ' ' . $notif['last_name']) ?>',
                                    '<?= htmlspecialchars($notif['course']) ?>',
                                    '<?= htmlspecialchars($profile_pic) ?>',
                                    <?= $notif['user_id'] ?>
                                )">
                                    <?php if ($profile_pic && file_exists($profile_pic) && strpos($profile_pic, 'default-avatar.jpg') === false): ?>
                                        <img src="<?= $profile_pic ?>" class="person-avatar" alt="Avatar">
                                    <?php else: ?>
                                        <span class="person-avatar user-icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;background:#bdbdbd;color:#fff;font-size:32px;">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    <?php endif; ?>
                                    <div class="person-name">
                                        <?= htmlspecialchars($notif['first_name'] . ' ' . $notif['last_name']) ?>
                                    </div>
                                    <div class="person-course">
                                        <?= htmlspecialchars($notif['course']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- People you liked -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-thumbs-up"></i>
                        People you liked
                    </h2>
                    
                    <?php if (empty($liked_users)): ?>
                        <div class="empty-state">
                            <i class="fas fa-thumbs-up"></i>
                            <h3>No likes sent yet</h3>
                            <p>Start browsing to find people you'd like to learn with!</p>
                        </div>
                    <?php else: ?>
                        <div class="cards-grid">
                            <?php foreach ($liked_users as $liked): ?>
                                <?php $profile_pic = $liked['profile_pic']; ?>
                                <div class="person-card" onclick="showLikedDetails(
                                    '<?= htmlspecialchars($liked['first_name'] . ' ' . $liked['last_name']) ?>',
                                    '<?= htmlspecialchars($liked['course']) ?>',
                                    '<?= htmlspecialchars($profile_pic ?: 'uploads/default.jpg') ?>',
                                    <?= $liked['user_id'] ?>
                                )">
                                    <?php if ($profile_pic && file_exists($profile_pic) && strpos($profile_pic, 'default-avatar.jpg') === false): ?>
                                        <img src="<?= $profile_pic ?>" class="person-avatar" alt="Avatar">
                                    <?php else: ?>
                                        <span class="person-avatar user-icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;background:#bdbdbd;color:#fff;font-size:32px;">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    <?php endif; ?>
                                    <div class="person-name">
                                        <?= htmlspecialchars($liked['first_name'] . ' ' . $liked['last_name']) ?>
                                    </div>
                                    <div class="person-course">
                                        <?= htmlspecialchars($liked['course']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for people who liked you -->
    <div id="toMeModal" class="modal-overlay">
        <div class="modal-content">
            <span class="modal-close" onclick="closeToMeModal()">&times;</span>
            <img id="toMe-user-image" src="uploads/default.jpg" class="modal-avatar">
            <div class="modal-name" id="toMe-user-name"></div>
            <div class="modal-course" id="toMe-user-course"></div>
            <div class="modal-actions">
                <button class="action-btn btn-reject" onclick="removeAction()">
                    <i class="fas fa-times"></i>
                </button>
                <button class="action-btn btn-accept" onclick="confirmAction()">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for people you liked -->
    <div id="fromMeModal" class="modal-overlay">
        <div class="modal-content">
            <span class="modal-close" onclick="closeFromMeModal()">&times;</span>
            <img id="fromMe-user-image" src="uploads/default.jpg" class="modal-avatar">
            <div class="modal-name" id="fromMe-user-name"></div>
            <div class="modal-course" id="fromMe-user-course"></div>
        <div class="modal-actions">
            <button class="action-btn btn-reject" onclick="removeMyLike()">
                <i class="fas fa-times"></i>
            </button>
            <!-- Heart button removed for 'People you liked' modal -->
        </div>
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
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        // Prevent duplicate profile icons
        document.addEventListener('DOMContentLoaded', function() {
            const profileIcons = document.querySelectorAll('.profile-icon');
            if (profileIcons.length > 1) {
                // Keep only the first one (main profile icon)
                for (let i = 1; i < profileIcons.length; i++) {
                    profileIcons[i].remove();
                }
            }
            
            const profileDropdowns = document.querySelectorAll('.profile-dropdown');
            if (profileDropdowns.length > 1) {
                // Keep only the first one (main profile dropdown)
                for (let i = 1; i < profileDropdowns.length; i++) {
                    profileDropdowns[i].remove();
                }
            }

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

        // Socket.io connection
        const socket = io();

        socket.on("update_match", function(data) {
            console.log("Realtime notification received:", data);
            const notifElement = document.querySelector(".notif-badge");
            if (notifElement) {
                let count = parseInt(notifElement.textContent) || 0;
                count++;
                notifElement.textContent = count;
                notifElement.style.display = "flex";
            }
        });

        // Listen for unread_message event (real-time message badge update)
        socket.on("unread_message", function(data) {
            let msgBadge = document.querySelector(".nav-item a[href='message.php'] .notif-badge, .nav-item a[href='message.php'] .msg-badge");
            if (!msgBadge) {
                msgBadge = document.querySelector("a[href='message.php'] .notif-badge, a[href='message.php'] .msg-badge");
            }
            if (msgBadge) {
                let count = parseInt(msgBadge.textContent) || 0;
                msgBadge.textContent = count + 1;
                msgBadge.style.display = "inline-block";
            } else {
                let msgLink = document.querySelector(".nav-item a[href='message.php'], a[href='message.php']");
                if (msgLink) {
                    const newBadge = document.createElement("span");
                    newBadge.className = "notif-badge";
                    newBadge.textContent = "1";
                    newBadge.style.display = "inline-block";
                    msgLink.appendChild(newBadge);
                }
            }
        });

        // Modal functions
        function showDetails(name, course, image, userId) {
            document.getElementById("toMe-user-name").textContent = name;
            document.getElementById("toMe-user-course").textContent = course;
            document.getElementById("toMe-user-image").src = image;
            document.querySelector('.btn-accept').setAttribute("data-user-id", userId);
            document.querySelector('.btn-reject').setAttribute("data-user-id", userId);
            document.getElementById("toMeModal").style.display = "flex";
        }

        function closeToMeModal() {
            document.getElementById("toMeModal").style.display = "none";
        }

        function confirmAction() {
            const userId = document.querySelector('.btn-accept').getAttribute("data-user-id");
            if (!userId) return;

            fetch('manage_like.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `targetUserId=${encodeURIComponent(userId)}&actionType=confirm_to_me`
            })
            .then(response => response.json())
            .then(result => {
                const card = document.querySelector(`[onclick*="${userId}"]`);
                if (card) card.remove();
                
                closeToMeModal();
                
                if (result.status === "match") {
                    socket.emit("match_found", { targetUserId: userId });
                    // Redirect to matched tab instead of message tab
                    window.location.href = 'matched_tab.php';
                }
            })
            .catch(error => console.error("Error:", error));
        }

        function removeAction() {
            const userId = document.querySelector('.btn-reject').getAttribute("data-user-id");
            if (!userId) return;

            fetch('manage_like.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `targetUserId=${encodeURIComponent(userId)}&actionType=remove_to_me`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === "removed") {
                    const card = document.querySelector(`[onclick*="${userId}"]`);
                    if (card) card.remove();
                    closeToMeModal();
                }
            })
            .catch(error => console.error("Error:", error));
        }

        function showLikedDetails(name, course, image, userId) {
            document.getElementById("fromMe-user-name").textContent = name;
            document.getElementById("fromMe-user-course").textContent = course;
            document.getElementById("fromMe-user-image").src = image;
            document.querySelector('#fromMeModal .btn-reject').setAttribute("data-user-id", userId);
            // No heart button, so don't set btn-accept
            document.getElementById("fromMeModal").style.display = "flex";
        }

        function closeFromMeModal() {
            document.getElementById("fromMeModal").style.display = "none";
        }

        function removeMyLike() {
            const userId = document.querySelector('#fromMeModal .btn-reject').getAttribute("data-user-id");
            if (!userId) return;

            fetch('manage_like.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `targetUserId=${encodeURIComponent(userId)}&actionType=remove_from_me`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === "removed") {
                    const card = document.querySelector(`[onclick*="${userId}"]`);
                    if (card) card.remove();
                    closeFromMeModal();
                }
            })
            .catch(error => console.error("Error:", error));
        }

        function likeAgain() {
            // Since they're already liked, this would just close the modal
            // or could potentially unlike and re-like if needed
            closeFromMeModal();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const toMeModal = document.getElementById("toMeModal");
            const fromMeModal = document.getElementById("fromMeModal");
            
            if (event.target === toMeModal) {
                closeToMeModal();
            }
            if (event.target === fromMeModal) {
                closeFromMeModal();
            }
        }
    </script>
</body>
</html>
