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
$row = $result->fetch_assoc();
$notif_count = $row['notif_count'] ?? 0;
$stmt->close();

// Fetch people you liked (for exclusion in notifications)
$liked_user_ids = [];
$queryLikedIds = "SELECT liked_user_id FROM user_likes WHERE user_id = ? AND action = 'like'";
$stmt = $conn->prepare($queryLikedIds);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $liked_user_ids[] = $row['liked_user_id'];
}
$stmt->close();

// Prepare exclusion clause
$exclude_clause = "";
$params = [];
$types = "";

if (!empty($liked_user_ids)) {
    $placeholders = implode(',', array_fill(0, count($liked_user_ids), '?'));
    $exclude_clause = "AND u.user_id NOT IN ($placeholders)";
    $types .= str_repeat("i", count($liked_user_ids));
    $params = $liked_user_ids;
}

// Fetch people who liked you (excluding those you already liked)
$notifications = [];
$query = "
    SELECT u.user_id, up.first_name, up.last_name, up.profile_pic, e.course
    FROM user_likes ul
    JOIN users u ON ul.user_id = u.user_id
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN education e ON u.user_id = e.user_id
    WHERE ul.liked_user_id = ? AND ul.action = 'like'
    $exclude_clause
";
$stmt = $conn->prepare($query);

// Bind parameters dynamically
$bindParams = [$user_id];
$bindTypes = "i";
if (!empty($params)) {
    $bindTypes .= $types;
    $bindParams = array_merge([$user_id], $params);
}
$refs = [];
$refs[] = &$bindTypes;
foreach ($bindParams as $key => $value) {
    $refs[] = &$bindParams[$key];
}
call_user_func_array([$stmt, 'bind_param'], $refs);

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
$stmt->close();

// Fetch people you liked (only those who haven't liked you back)
$liked_users = [];
$query2 = "
    SELECT u.user_id, up.first_name, up.last_name, up.profile_pic, e.course
    FROM user_likes ul
    JOIN users u ON ul.liked_user_id = u.user_id
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN education e ON u.user_id = e.user_id
    WHERE ul.user_id = ? AND ul.action = 'like'
      AND NOT EXISTS (
          SELECT 1 FROM user_likes ul2 
          WHERE ul2.user_id = u.user_id AND ul2.liked_user_id = ?
      )
";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("ii", $user_id, $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($row = $result2->fetch_assoc()) {
    $liked_users[] = $row;
}
$stmt2->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>SkillSynergy Notification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 280px;
            height: calc(100vh - 40px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            padding: 30px 0;
            z-index: 100;
        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .logo img {
            width: 140px;
            height: auto;
            object-fit: contain;
        }

        .sidebar a {
            color: #4a5568;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 25px;
            margin: 5px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar a i {
            font-size: 20px;
            width: 24px;
        }

        .sidebar a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            transform: translateX(5px);
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
                5px 5px rgba(0, 0, 0, 0.4),
                -3px -5px rgba(255, 255, 255, 0.8);  
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

        .main-content {
            flex: 1;
            padding: 40px 50px;
            overflow-y: auto;
            width: calc(100vh - 250px); 
            margin-left: 300px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }

        .right-section {
            width: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 5px 5px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: left; 
            width: 100%; 
        }

        .person-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            width: 100%;
        }
        .person-card {
            background: #fff;
            padding: 2px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 200px;
            width: 230px;
        }

        .person-card:hover {
            transform: scale(1.05); 
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); 
        }

        .person-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .person-card .name-course {
            font-size: 14px;
            color: #333;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
        }

        .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        width: 300px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 10000;
        }

        .close-btn {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 25px;
        cursor: pointer;
        }

        .modal-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
        }

        .modal-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        }

        .modal-actions button {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 50%;
        padding: 10px 12px;
        font-size: 20px;
        cursor: pointer;
        }

        .modal-actions button:hover {
        background: #0056b3;
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
  <br><br><br><br>

  <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>" title="Home">
    <i class="fas fa-home"></i> Home
  </a>
  
  <a href="findmatch.php" class="<?= $current_page == 'findmatch.php' ? 'active' : '' ?>" title="Find Match">
    <i class="fas fa-search"></i> Find Match
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

  <a href="logout.php" class="" style=" margin-top: 5px;" title="Logout">
    <i class="fas fa-sign-out-alt"></i> Logout

  </a>
</div>

<div class="profile-dropdown">
<a href="user_profile.php" class="profile-icon">
    <i class="fas fa-user"></i>
  </a>
  <div class="dropdown-content">
  </div>
</div>

<div class="main-content">
<div class="right-section">

<div class="section-title">People who want to learn with you</div>
<div class="person-grid">
<?php if (empty($notifications)): ?>
<p>No notifications yet.</p>
<?php else: ?>
<?php foreach ($notifications as $notif): ?>
<div class="person-card"
    id="card-<?= $notif['user_id'] ?>"
    onclick="showDetails(
      '<?= htmlspecialchars($notif['first_name'].' '.$notif['last_name']) ?>',
      '<?= htmlspecialchars($notif['course']) ?>',
      '<?= htmlspecialchars($notif['profile_pic'] ?: 'default.jpg') ?>',
      <?= $notif['user_id'] ?>)">
  <img src="<?= htmlspecialchars($notif['profile_pic'] ?: 'default.jpg') ?>" alt="User">
  <div style="width:300px; height:1px; background-color:#ccc; margin-top:5px;"></div>
  <div class="name-course">
    <?= htmlspecialchars($notif['first_name'].' '.$notif['last_name']) ?>, <?= htmlspecialchars($notif['course']) ?>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<div class="section-title">People you liked</div>
<div class="person-grid">
<?php if (empty($liked_users)): ?>
<p>No liked users yet.</p>
<?php else: ?>
<?php foreach ($liked_users as $liked): ?>
<div class="person-card"
    id="card-<?= $liked['user_id'] ?>"
    onclick="showLikedDetails(
      '<?= htmlspecialchars($liked['first_name'].' '.$liked['last_name']) ?>',
      '<?= htmlspecialchars($liked['course']) ?>',
      '<?= htmlspecialchars($liked['profile_pic'] ?: 'default.jpg') ?>',
      <?= $liked['user_id'] ?>)">
  <img src="<?= htmlspecialchars($liked['profile_pic'] ?: 'default.jpg') ?>" alt="User">
  <div style="width:300px; height:1px; background-color:#ccc; margin-top:5px;"></div>
  <div class="name-course">
    <?= htmlspecialchars($liked['first_name'].' '.$liked['last_name']) ?>, <?= htmlspecialchars($liked['course']) ?>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<div id="toMeModal" class="modal-overlay" style="display:none;">
<div class="modal-content">
<span class="close-btn" onclick="closeToMeModal()">&times;</span>
<img id="toMe-user-image" src="uploads/default.jpg" class="modal-img">
<h2 id="toMe-user-name"></h2>
<p id="toMe-user-course"></p>
<div class="modal-actions">
  <button id="toMe-dislikeBtn" onclick="removeAction()"><i class="fas fa-thumbs-down"></i></button>
  <button id="toMe-likeBtn" onclick="confirmAction()">
    <i class="fas fa-thumbs-up"></i>
  </button>
</button>
</div>
</div>
</div>

<div id="fromMeModal" class="modal-overlay" style="display:none;">
<div class="modal-content">
<span class="close-btn" onclick="closeFromMeModal()">&times;</span>
<img id="fromMe-user-image" src="uploads/default.jpg" alt="Profile" class="modal-img">
<h2 id="fromMe-user-name"></h2>
<p id="fromMe-user-course"></p>
<div class="modal-actions">
  <button id="fromMe-dislikeBtn" onclick="removeMyLike()">
    <i class="fas fa-thumbs-down"></i>
  </button>
</div>
</div>
</div>

<script src="/socket.io/socket.io.js"></script>
<script>
const socket = io();

// 🔔 Listen for real-time match or like updates
socket.on("update_match", function(data) {
  console.log("Realtime notif received:", data);

  const notifElement = document.getElementById("notif-count");
  if (notifElement) {
    let count = parseInt(notifElement.textContent) || 0;
    count++;
    notifElement.textContent = count;
    notifElement.style.display = "inline-block";
  }
});

function showDetails(name, course, image, userId) {
  document.getElementById("toMe-user-name").innerText = name;
  document.getElementById("toMe-user-course").innerText = course;
  document.getElementById("toMe-user-image").src = image;
  document.getElementById("toMe-likeBtn").setAttribute("data-user-id", userId);
  document.getElementById("toMe-dislikeBtn").setAttribute("data-user-id", userId);
  document.getElementById("toMeModal").style.display = "flex";
}

function closeToMeModal() {
  const modal = document.getElementById("toMeModal");
  if (modal) {
    modal.style.display = "none";
  }
}

function confirmAction() {
  const likeBtn = document.getElementById("toMe-likeBtn");
  const userId = likeBtn?.getAttribute("data-user-id");

  if (!userId) {
    console.error("No userId found on like button.");
    return;
  }

  fetch('manage_like.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `targetUserId=${encodeURIComponent(userId)}&actionType=confirm_to_me`
  })
  .then(response => response.json())
  .then(result => {
    console.log("Server response:", result);

    const card = document.getElementById(`card-${userId}`);
    const grid = card?.closest(".person-grid");

    // Remove the card if it exists
    if (card) card.remove();

    // If no more cards in grid, show fallback message
    if (grid && grid.querySelectorAll(".person-card").length === 0) {
      const message = document.createElement("p");
      message.className = "no-cards-message";
      message.textContent = "No notifications yet.";
      grid.appendChild(message);
    }

    // Always close the modal right after confirmation
    closeToMeModal();

    // If it's a match, emit match_found via socket, silently
    if (result.status === "match") {
      if (typeof socket !== "undefined") {
        socket.emit("match_found", { targetUserId: userId });
      }
    } 
    else if (result.status !== "confirm") {
      // If neither confirm nor match, show error alert
      alert("Failed to confirm like: " + result.status);
    }
  })
  .catch(error => {
    console.error("Error during confirm action:", error);
  });
}


function removeAction() {
  const userId = document.getElementById("toMe-dislikeBtn").getAttribute("data-user-id");
  if (!userId) return;

  fetch('manage_like.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'targetUserId=' + encodeURIComponent(userId) + '&actionType=remove_to_me'
  })
  .then(response => response.json())
  .then(result => {
    if (result.status === "removed") {
      const card = document.getElementById("card-" + userId);
      if (card) {
        const grid = card.closest(".person-grid");
        card.remove();
        if (grid.querySelectorAll(".person-card").length === 0) {
          const message = document.createElement("p");
          message.textContent = "No notifications yet.";
          grid.appendChild(message);
        }
      }
      closeToMeModal();
    } else {
      alert("Failed to remove like: " + result.status);
    }
  })
  .catch(error => console.error("Error:", error));
}


function showLikedDetails(name, course, image, userId) {
  document.getElementById("fromMe-user-name").innerText = name;
  document.getElementById("fromMe-user-course").innerText = course;
  document.getElementById("fromMe-user-image").src = image;
  document.getElementById("fromMe-dislikeBtn").setAttribute("data-user-id", userId);
  document.getElementById("fromMeModal").style.display = "flex";
}

function closeFromMeModal() {
  document.getElementById("fromMeModal").style.display = "none";
}

function removeMyLike() {
  const userId = document.getElementById("fromMe-dislikeBtn").getAttribute("data-user-id");
  if (!userId) return;

  fetch('manage_like.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'targetUserId=' + encodeURIComponent(userId) + '&actionType=remove_from_me'
  })
  .then(res => res.json())
  .then(result => {
    if (result.status === "removed") {
      const card = document.getElementById("card-" + userId);
      if (card) {
        const grid = card.closest(".person-grid");
        card.remove();

        if (grid.querySelectorAll(".person-card").length === 0) {
          const message = document.createElement("p");
          message.textContent = "No liked users yet.";
          grid.appendChild(message);
        }
      }
      closeFromMeModal();
    } else {
      alert("Failed: " + result.status);
    }
  });
}
</script>

</body>
</html>