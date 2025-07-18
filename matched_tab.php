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

if (!$user_id) {
    die("User not found. Please log in again.");
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

// Count unread messages for badge
$unread_msg_count = 0;
$msgCountQuery = "SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = ? AND is_read = FALSE";
$stmt = $conn->prepare($msgCountQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_msg_count = $row['unread_count'] ?? 0;
$stmt->close();

// Fetch matched users (mutual likes) - EXCLUDE those who have started sessions
$matched_sql = "
SELECT u.user_id, up.first_name, up.last_name, e.course, 
       COALESCE(up.profile_pic, 'uploads/default.jpg') AS profile_pic
FROM users u
JOIN users_profile up ON u.user_id = up.user_id
JOIN education e ON u.user_id = e.user_id
JOIN user_likes ul1 
  ON ul1.liked_user_id = u.user_id 
  AND ul1.user_id = ? 
  AND ul1.action = 'like'
JOIN user_likes ul2 
  ON ul2.user_id = u.user_id 
  AND ul2.liked_user_id = ?
  AND ul2.action = 'like'
WHERE NOT EXISTS (
    SELECT 1 FROM messages m
    WHERE (m.sender_id = ? AND m.receiver_id = u.user_id)
       OR (m.sender_id = u.user_id AND m.receiver_id = ?)
)
GROUP BY u.user_id, up.first_name, up.last_name, e.course, up.profile_pic
ORDER BY up.first_name ASC
";

$stmt = $conn->prepare($matched_sql);
$stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$matchedUsers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>SkillSynergy - Matched</title>
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

        .content-section {
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

        .match-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        .match-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .match-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            object-fit: cover;
            border: 4px solid #f7fafc;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .match-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .match-course {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .match-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 20px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .message-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .remove-btn {
            background: linear-gradient(135deg, #fc8181, #e53e3e);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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

        /* Skill Selection Modal Styles */
        .skill-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .skill-modal.show {
            display: flex;
        }

        .skill-modal-content {
            background: #23272a;
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .skill-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #40444b;
        }

        .skill-modal-header h3 {
            color: #fff;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .close-btn:hover {
            background-color: #40444b;
        }

        .skill-description {
            margin-bottom: 25px;
            padding: 15px;
            background: #2f3136;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .skill-description p {
            margin: 0;
            line-height: 1.6;
            color: #dcddde;
        }

        .skill-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .skill-btn {
            background: #40444b;
            color: #fff;
            border: none;
            padding: 15px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            text-align: left;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .skill-btn:hover {
            background: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .skill-btn .proficiency {
            background: #2f3136;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .skill-btn:hover .proficiency {
            background: rgba(255, 255, 255, 0.2);
        }

        .proficiency-beginner {
            color: #43b581;
        }

        .proficiency-intermediate {
            color: #faa61a;
        }

        .proficiency-advanced {
            color: #f04747;
        }

        @media (max-width: 768px) {
            .skill-modal-content {
                width: 95%;
                padding: 20px;
            }
            
            .skill-modal-header h3 {
                font-size: 20px;
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
                        <?php if ($unread_msg_count > 0): ?>
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
            <div class="header">
                <h1 class="page-title">Your Matches</h1>
                <div class="profile-dropdown">
                    <a href="user_profile.php" class="profile-icon">
                        <i class="fas fa-user"></i>
                    </a>

                </div>
            </div>

            <div class="content-section">
                <h2 class="section-title">
                    <i class="fas fa-user-friends"></i>
                    Matched Users (<?= count($matchedUsers) ?>)
                </h2>
                
                <?php if (empty($matchedUsers)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-friends"></i>
                        <h3>No matches yet</h3>
                        <p>Start liking people to find your matches!</p>
                    </div>
                <?php else: ?>
                    <div class="cards-grid">
                      <?php foreach ($matchedUsers as $match): ?>
                        <div class="match-card" id="match-card-<?= $match['user_id'] ?>">
                        <?php 
                        $profile_pic = $match['profile_pic'];
                        if ($profile_pic && file_exists($profile_pic) && strpos($profile_pic, 'default-avatar.jpg') === false): 
                        ?>
                            <img src="<?= htmlspecialchars($profile_pic) ?>" class="match-avatar" alt="Profile" onerror="this.src='uploads/default.jpg'">
                        <?php else: ?>
                            <span class="match-avatar user-icon" style="display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;background:#bdbdbd;color:#fff;font-size:32px;">
                                <i class="fas fa-user"></i>
                            </span>
                        <?php endif; ?>
                        <div class="match-name">
                            <?= htmlspecialchars($match['first_name'] . ' ' . $match['last_name']) ?>
                        </div>
                        <div class="match-course">
                            <?= htmlspecialchars($match['course']) ?>
                        </div>
                        <div class="match-actions">
                            <a href="javascript:void(0);" onclick="startSession(<?= $match['user_id'] ?>)" 
                            class="action-btn message-btn">
                            <i class="fas fa-comment-dots"></i>
                            Start Session
                            </a>
                            <button class="action-btn remove-btn" 
                                    onclick="removeMatch(<?= $match['user_id'] ?>)">
                            <i class="fas fa-user-times"></i>
                            Remove
                            </button>
                        </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Skill Selection Modal -->
    <div id="skillModal" class="skill-modal">
        <div class="skill-modal-content">
            <div class="skill-modal-header">
                <h3 id="skillModalTitle">Choose a Skill to Learn</h3>
                <button class="close-btn" onclick="closeSkillModal()">&times;</button>
            </div>
            <div class="skill-modal-body">
                <div id="skillModalDescription" class="skill-description">
                    <!-- Description will be populated by JavaScript -->
                </div>
                <div id="skillOptions" class="skill-options">
                    <!-- Skill buttons will be populated by JavaScript -->
                </div>
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

    function removeMatch(userId) {
      showConfirmModal('Are you sure you want to remove this match?', function(confirmed) {
        if (!confirmed) return;
        fetch('remove_match.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'targetUserId=' + encodeURIComponent(userId)
        })
        .then(response => response.json())
        .then(result => {
          if (result.status === 'removed') {
            var card = document.getElementById('match-card-' + userId);
            if (card) card.remove();
            updateMatchCount && updateMatchCount();
          } else {
            alert('Failed to remove match: ' + (result.message || result.status));
          }
        })
        .catch(() => alert('Failed to remove match. Please try again.'));
      });
    }

    function startSession(userId) {
            fetch('start_session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'targetUserId=' + encodeURIComponent(userId)
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'show_skill_selection') {
                    showSkillSelectionModal(result.target_user);
                } else {
                    alert('Failed to start session: ' + result.status);
                }
            })
            .catch((error) => {
                console.error('Error starting session:', error);
                alert('Failed to start session. Please try again.');
            });
        }

        function showSkillSelectionModal(targetUser) {
            const modal = document.getElementById('skillModal');
            const title = document.getElementById('skillModalTitle');
            const description = document.getElementById('skillModalDescription');
            const options = document.getElementById('skillOptions');

            // Set title
            title.textContent = `Choose a Skill to Learn from ${targetUser.name}`;

            // Set description
            description.innerHTML = `
                <p><strong>This person has the following skills:</strong></p>
                <p>Choose what skill you want to learn from this person.</p>
            `;

            // Clear previous options
            options.innerHTML = '';

            // Add skill buttons
            if (targetUser.skills && targetUser.skills.length > 0) {
                targetUser.skills.forEach(skill => {
                    const skillBtn = document.createElement('button');
                    skillBtn.className = 'skill-btn';
                    skillBtn.onclick = () => selectSkill(skill, targetUser);
                    
                    const proficiencyClass = `proficiency-${skill.proficiency.toLowerCase()}`;
                    
                    skillBtn.innerHTML = `
                        <span>${skill.skill}</span>
                        <span class="proficiency ${proficiencyClass}">${skill.proficiency}</span>
                    `;
                    
                    options.appendChild(skillBtn);
                });
            } else {
                options.innerHTML = '<p style="color: #dcddde; text-align: center;">No skills available</p>';
            }

            // Show modal
            modal.classList.add('show');
        }

        function selectSkill(skill, targetUser) {
            // Send skill choice to backend
            fetch('send_skill_choice.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `to_user_id=${targetUser.id}&skill=${encodeURIComponent(skill.skill)}&proficiency=${encodeURIComponent(skill.proficiency)}`
        })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    // Close modal
                    closeSkillModal();
                    
                    // Show success message
                    alert(`Thank you! Your message has been sent to ${targetUser.name}. Please wait for his/her response. No response within 24 hours will end the session.`);
                    
                    // Remove from DOM immediately
                    var card = document.getElementById('match-card-' + targetUser.id);
                    if (card) card.remove();
                    updateMatchCount();
                    
                    // Remove match in backend
                    fetch('remove_match.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'targetUserId=' + encodeURIComponent(targetUser.id)
                    });
                    
                    // Redirect to message page
                    setTimeout(function() {
                        window.location.href = 'message.php?user_id=' + targetUser.id;
                    }, 1000);
                } else {
                    alert('Failed to send skill choice: ' + result.message);
                }
            })
            .catch((error) => {
                console.error('Error sending skill choice:', error);
                alert('Failed to send skill choice. Please try again.');
            });
        }

        function closeSkillModal() {
            const modal = document.getElementById('skillModal');
            modal.classList.remove('show');
        }

        function updateMatchCount() {
        const remaining = document.querySelectorAll(".match-card").length;
        const titleElement = document.querySelector('.section-title');
        if (titleElement) {
            const match = titleElement.textContent.match(/\((\d+)\)/);
            if (match) {
            const newCount = remaining;
            titleElement.innerHTML = titleElement.innerHTML.replace(/\(\d+\)/, `(${newCount})`);
            }
        }

        if (remaining === 0) {
            document.querySelector('.cards-grid').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-user-friends"></i>
                <h3>No matches yet</h3>
                <p>Start liking people to find your matches!</p>
            </div>
            `;
        }
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
