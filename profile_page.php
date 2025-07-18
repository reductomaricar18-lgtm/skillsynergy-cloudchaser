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

// Get user_id from URL if provided, otherwise use session
$user_id = null;
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
} else {
    // Get current user's ID
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
}
// Check if user exists
$user_exists = false;
if ($user_id) {
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_stmt->bind_result($user_exists_count);
    $check_stmt->fetch();
    $check_stmt->close();
    $user_exists = ($user_exists_count > 0);
}
if (!$user_exists) {
    echo '<div style="color:red;text-align:center;margin-top:40px;font-size:1.2rem;">User not found.</div>';
    exit();
}

// Count notifications (people who liked you but you haven't liked them back)
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkillSynergy Profile Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      min-height: 100vh;
      background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
      background-image: url('S3.jpg');
      background-repeat: no-repeat;
      background-size: 100% 100%;
      background-position: center;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }

.sidebar {
  position: fixed;
  top: 100px;
  left: 75px;
  width: 230px;
  height: 80vh;
  background: rgba(206, 204, 204, 0.7);
  backdrop-filter: blur(10px);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px 0;
  border-radius: 20px;
  box-shadow:
    5px 5px rgba(0, 0, 0, 0.4),
    -3px -5px rgba(255, 255, 255, 0.8);
}

.logo img {
  width: 100%;
  height: 100%;
  margin-top: 30px;
  object-fit: cover;
}

.sidebar a {
  color: #000;
  font-size: 15px;
  margin-top: 50px;
  margin: 10px 0px;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 20px;
  width: 150px;
  border-radius: 12px;
  transition: background 0.3s, color 0.3s;
}

.sidebar a i {
  font-size: 22px;
}

.sidebar a:hover {
  background: #007BFF;
  color: #fff;
}

    .profile-dropdown {
      position: fixed;
      top: 20px;
      right: 80px;
      text-align: center;
    }

    .profile-container {
      position: relative;
      width: 50px;
      height: 50px;
      cursor: pointer;
    }

    .profile-icon {
      width: 45px;
      height: 45px;
      background: #004466;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      transition: 0.8s;
    }

    .profile-icon:hover {
      background: #007BFF;
    }

    .arrow-icon {
      position: absolute;
      bottom: 0px;
      right: 0px;
      background-color:rgb(7, 0, 0);
      color:rgb(12, 105, 199);
      border-radius: 50%;
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      margin-top: 5px;
      background:rgba(218, 214, 214, 0.88);
      min-width: 120px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
      z-index: 1;
      box-shadow:
      5px 5px rgba(0, 0, 0, 0.4),    /* bottom-right black shadow */
      -3px -5px rgba(255, 255, 255, 0.8); /* top-left white glow */  
    }

    .dropdown-content a {
      padding: 12px 16px;
      display: block;
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .profile-dropdown:hover .dropdown-content {
      display: block;
    }

    .sidebar a.active {
      position: relative;
      font-weight: bold;
      color: #007BFF;
    }

    .sidebar a.active::after {
      content: "";
      position: absolute;
      bottom: 5px;
      left: 20px;
      width: 80%;
      height: 3px;
      background-color: #007BFF;
      border-radius: 5px;
    }

    .notif-badge {
      position: absolute;
      top: 5px;
      right: 10px;
      background: red;
      color: white;
      border-radius: 50%;
      padding: 2px 7px;
      font-size: 12px;
    }

  </style>
</head>
<body>

<?php
  $current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
  <div class="logo">
    <img src="logo-profilepage.jpg" alt="Logo">
  </div>
  <br><br><br>

  <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>" title="Home">
    <i class="fas fa-home"></i> Home
  </a>
  
  <a href="findmatch.php" class="<?= $current_page == 'findmatch.php' ? 'active' : '' ?>" title="Find Match">
    <i class="fas fa-search"></i> Find Match
  </a>
  
  <a href="lesson_assessment.php" class="<?= $current_page == 'lesson_assessment.php' ? 'active' : '' ?>" title="Lesson & Assessment">
    <i class="fas fa-book"></i> Lesson & Assessment
  </a>
  
  <a href="notificationtab.php" class="<?= $current_page == 'notificationtab.php' ? 'active' : '' ?>" title="Notifications" style="position: relative;">
    <i class="fas fa-bell"></i> Notification
    <?php if ($notif_count > 0): ?>
      <span id="notif-count" class="notif-badge"><?= $notif_count ?></span>
    <?php else: ?>
      <span id="notif-count" class="notif-badge" style="display:none;">0</span>
    <?php endif; ?>
  </a>
  
  <a href="matched_tab.php" class="<?= $current_page == 'matched_tab.php' ? 'active' : '' ?>" title="Matched Users">
    <i class="fas fa-user-friends"></i> Matched
  </a>
  
  <a href="message.php" class="<?= $current_page == 'message.php' ? 'active' : '' ?>" title="Messages">
    <i class="fas fa-comment-dots"></i> Message
  </a>
</div>

<?php
// Fetch user's profile picture if available
$profile_pic = '';
$pic_stmt = $conn->prepare("SELECT profile_pic FROM users_profile WHERE user_id = ?");
$pic_stmt->bind_param("i", $user_id);
$pic_stmt->execute();
$pic_stmt->bind_result($profile_pic);
$pic_stmt->fetch();
$pic_stmt->close();
?>
<div class="profile-avatar" style="width:110px;height:110px;margin:40px auto 20px auto;display:flex;align-items:center;justify-content:center;">
<?php if ($profile_pic && file_exists($profile_pic) && strpos($profile_pic, 'default-avatar.jpg') === false): ?>
    <img src="<?= htmlspecialchars($profile_pic) ?>" alt="Profile Picture" style="width:100px;height:100px;border-radius:50%;object-fit:cover;box-shadow:0 2px 8px rgba(0,0,0,0.10);">
<?php else: ?>
    <span style="display:flex;align-items:center;justify-content:center;width:100px;height:100px;border-radius:50%;background:#e0e0e0;color:#888;font-size:48px;">
        <i class="fas fa-user"></i>
    </span>
<?php endif; ?>
</div>

  <div class="profile-dropdown">
    <div class="profile-container">
      <div class="profile-icon">
        <i class="fas fa-user"></i>
      </div>
      <div class="arrow-icon"><i class="fas fa-caret-down"></i></div>
    </div>
    <div class="dropdown-content">
      <a href="user_profile.php">Profile</a>
      <a href="logout.php">Log Out</a>
    </div>
  </div>

<!-- Add a span for real-time match count -->
<div style="text-align:center;margin:20px 0;font-size:1.2rem;">
    Total Matches: <span id="match-count">0</span>
</div>

<script src="/socket.io/socket.io.js"></script>
<script>
const socket = io();
function updateMatchCount() {
    fetch('fetch_match_count.php')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('match-count').textContent = data.count;
            }
        });
}
socket.on('update_match', function() {
    updateMatchCount();
});
document.addEventListener('DOMContentLoaded', updateMatchCount);
</script>

</body>
</html>



