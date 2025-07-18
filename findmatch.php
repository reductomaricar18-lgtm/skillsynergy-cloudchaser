<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$email = $_SESSION['email'];

// Get user ID
$result = $conn->query("SELECT user_id FROM users WHERE email = '$email' LIMIT 1");
$userRow = $result->fetch_assoc();
$user_id = $userRow['user_id'] ?? null;
if (!$user_id) die("User not found.");

// Notification count
$countQuery = "
    SELECT COUNT(DISTINCT ul.user_id) as notif_count
    FROM user_likes ul
    WHERE ul.liked_user_id = $user_id
      AND ul.action = 'like'
      AND ul.status = 'active'
      AND NOT EXISTS (
          SELECT 1 FROM user_likes ul2
          WHERE ul2.user_id = $user_id
            AND ul2.liked_user_id = ul.user_id
            AND ul2.action = 'like'
            AND ul2.status = 'active'
      )
";
$notifResult = $conn->query($countQuery);
$notifRow = $notifResult->fetch_assoc();
$notif_count = $notifRow['notif_count'] ?? 0;

// Unread message count
$msgResult = $conn->query("SELECT COUNT(*) AS unread_count FROM messages WHERE receiver_id = $user_id AND is_read = 0");
$msgRow = $msgResult->fetch_assoc();
$unread_msg_count = $msgRow['unread_count'] ?? 0;

// Get user's learning goals
$learningGoals = [];
$goalsRes = $conn->query("SELECT want_to_learn FROM learning_goals WHERE user_id = $user_id");
while ($goal = $goalsRes->fetch_assoc()) {
    if (!empty($goal['want_to_learn'])) {
        $learningGoals[] = strtolower(trim($goal['want_to_learn']));
    }
}

// Filters
$search = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$skillFilter = $_GET['skill'] ?? '';
$proficiencyFilter = $_GET['proficiency'] ?? '';

// Get all categories and skills for dropdowns
$categoryListRes = $conn->query("SELECT DISTINCT category FROM skills_offer WHERE category IS NOT NULL ORDER BY category ASC");
$categories = [];
while ($row = $categoryListRes->fetch_assoc()) $categories[] = $row['category'];

$skillListRes = $conn->query("SELECT DISTINCT specific_skill FROM skills_offer WHERE specific_skill IS NOT NULL ORDER BY specific_skill ASC");
$skills = [];
while ($row = $skillListRes->fetch_assoc()) $skills[] = $row['specific_skill'];

// Build query
$query = "
    SELECT DISTINCT 
        u.user_id,
        up.first_name, up.last_name, up.profile_pic,
        up.age, up.gender,
        e.course,
        so.category,
        so.specific_skill AS skill,
        ia.proficiency
    FROM users u
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN education e ON u.user_id = e.user_id
    LEFT JOIN skills_offer so ON u.user_id = so.user_id
    LEFT JOIN initial_assessment ia ON u.user_id = ia.user_id AND ia.skills_id = so.skills_id
    WHERE u.user_id != $user_id
";

if ($categoryFilter) $query .= " AND so.category = '" . $conn->real_escape_string($categoryFilter) . "'";
if ($skillFilter) $query .= " AND so.specific_skill = '" . $conn->real_escape_string($skillFilter) . "'";
if ($proficiencyFilter) $query .= " AND ia.proficiency = '" . $conn->real_escape_string($proficiencyFilter) . "'";

if ($search) {
    $searchEsc = $conn->real_escape_string($search);
    $query .= " AND (
        e.course LIKE '%$searchEsc%' 
        OR so.category LIKE '%$searchEsc%'
        OR so.specific_skill LIKE '%$searchEsc%'
        OR up.gender LIKE '%$searchEsc%'
        OR up.age LIKE '%$searchEsc%'
    )";
}

$result = $conn->query($query);

// Prioritize matches by want_to_learn first
$priorityMatches = [];
$otherMatches = [];
while ($row = $result->fetch_assoc()) {
    $skillLower = strtolower(trim($row['skill'] ?? ''));
    $profile = [
        'user_id' => $row['user_id'],
        'name' => $row['first_name'] . ' ' . $row['last_name'],
        'first_name' => $row['first_name'],
        'last_name' => $row['last_name'],
        'profile_pic' => (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) ? $row['profile_pic'] : "uploads/default.jpg",
        'age' => $row['age'],
        'gender' => $row['gender'],
        'course' => $row['course'],
        'category' => $row['category'] ?? 'N/A',
        'skill' => $row['skill'] ?? 'N/A',
        'proficiency' => $row['proficiency'] ?? 'Not Assessed'
    ];
    if (in_array($skillLower, $learningGoals)) {
        $priorityMatches[] = $profile;
    } else {
        $otherMatches[] = $profile;
    }
}

shuffle($priorityMatches);
shuffle($otherMatches);

$matches = array_merge($priorityMatches, $otherMatches);

// Fallback if no matches found
if (empty($matches)) {
    $fallbackQuery = "
        SELECT 
            u.user_id,
            up.first_name, up.last_name, up.profile_pic,
            up.age, up.gender,
            e.course
        FROM users u
        JOIN users_profile up ON u.user_id = up.user_id
        JOIN education e ON u.user_id = e.user_id
        WHERE u.user_id != $user_id
    ";
    $fallbackResult = $conn->query($fallbackQuery);

    $matches = [];
    while ($row = $fallbackResult->fetch_assoc()) {
        $matches[] = [
            'user_id' => $row['user_id'],
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'profile_pic' => (!empty($row['profile_pic']) && file_exists($row['profile_pic'])) ? $row['profile_pic'] : "uploads/default.jpg",
            'age' => $row['age'],
            'gender' => $row['gender'],
            'course' => $row['course'],
            'category' => 'Any',
            'skill' => 'Any',
            'proficiency' => 'Not Assessed'
        ];
    }
    shuffle($matches);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Match - SkillSynergy</title>
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
            padding: 0 20px;
        }

        .logo img {
            width: 140px;
            height: auto;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin: 8px 20px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #4a5568;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
        }

        .notif-badge {
            background: #e53e3e;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            margin-left: auto;
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 32px 40px 32px 40px;
            border-radius: 0 0 32px 32px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.10);
            margin-bottom: 0;
            position: relative;
        }

        .page-title {
            font-size: 2.7rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 4px 16px rgba(0,0,0,0.13);
            letter-spacing: 0.5px;
        }

        .profile-dropdown {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
        }

    .profile-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(20px);
    border-radius: 50%;
    display: flex
;
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
            background: rgba(255,255,255,0.18);
            transform: scale(1.08);
            box-shadow: 0 4px 24px rgba(102, 126, 234, 0.18);
        }


        .content-area {
            padding: 40px;
        }

        .search-filters-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            margin-bottom: 25px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 15px;
            padding: 15px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-box i {
            color: #a0aec0;
            margin-right: 15px;
            font-size: 18px;
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 16px;
            color: #2d3748;
        }

        .search-box input::placeholder {
            color: #a0aec0;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group select {
            padding: 12px 15px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 10px;
            background: white;
            color: #2d3748;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .filter-btn.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .filter-btn.secondary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .match-area {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            min-height: 600px;
        }

        .bg-glass-left,
        .bg-glass-right {
            position: absolute;
            width: 320px;
            height: 480px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            z-index: 1;
        }

        .bg-glass-left {
            left: 50%;
            transform: translateX(-180px) rotate(-5deg);
        }

        .bg-glass-right {
            right: 50%;
            transform: translateX(180px) rotate(5deg);
        }

        .card-container {
            position: relative;
            width: 350px;
            height: 500px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.4s ease;
        }

        .card-container.swipe-left {
            transform: translateX(-100vw) rotate(-30deg);
            opacity: 0;
        }

        .card-container.swipe-right {
            transform: translateX(100vw) rotate(30deg);
            opacity: 0;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
            border: 4px solid rgba(102, 126, 234, 0.2);
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .profile-name {
            font-size: 15px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            text-align: center;
        }

        .profile-details {
            color: #718096;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .skills-list span {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .navigation-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: auto;
        }

        .nav-btn {
            width: 60px;
            height: 60px;
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .dislike-btn {
            background: linear-gradient(135deg, #fc8181, #f56565);
        }

        .like-btn {
            background: linear-gradient(135deg, #68d391, #38a169);
        }


        .nav-btn:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .content-area {
                padding: 20px;
            }

            .search-filters-section {
                padding: 20px;
            }

            .filters {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .filter-buttons {
                flex-direction: column;
            }

            .card-container {
                width: 320px;
                height: 480px;
            }

            .navigation-buttons {
                gap: 15px;
            }

            .nav-btn {
                width: 50px;
                height: 50px;
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
                    <a href="findmatch.php" class="nav-link active">
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
                <h1 class="page-title">Find Match</h1>
                <div class="profile-dropdown">
                    <a href="user_profile.php" class="profile-icon">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <!-- Search and Filters Section -->
                <div class="search-filters-section">
                    <div class="search-container">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search by course, skills, category..." 
                                value="<?= htmlspecialchars($search) ?>" id="searchInput">
                        </div>
                    </div>
                    
                    <form method="GET" id="filterForm">
                        <div class="filters">
                            <div class="filter-group">
                                <label for="category">Category</label>
                                <select name="category" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>" 
                                                <?= (isset($_GET['category']) && $_GET['category'] === $cat) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label for="skill">Specific Skill</label>
                                <select name="skill" id="skillFilter">
                                    <option value="">All Skills</option>
                                    <?php foreach ($skills as $skill): ?>
                                        <option value="<?= htmlspecialchars($skill) ?>" 
                                                <?= (isset($_GET['skill']) && $_GET['skill'] === $skill) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($skill) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label for="proficiency">Proficiency Level</label>
                                <select name="proficiency" id="proficiencyFilter">
                                    <option value="">All Levels</option>
                                    <option value="Beginner" <?= (isset($_GET['proficiency']) && $_GET['proficiency'] === 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                                    <option value="Intermediate" <?= (isset($_GET['proficiency']) && $_GET['proficiency'] === 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                                    <option value="Advanced" <?= (isset($_GET['proficiency']) && $_GET['proficiency'] === 'Advanced') ? 'selected' : '' ?>>Advanced</option>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="submit" class="filter-btn primary">
                                        <i class="fas fa-filter"></i> Apply Filters
                                    </button>
                                    <button type="button" class="filter-btn secondary" onclick="clearFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                    </form>
                </div>

                <div class="match-area">
                    <div class="ghost-card bg-glass-left"></div>
                    <div class="ghost-card bg-glass-right"></div>

                    <div class="card-container" id="card">
                        <div class="profile-pic" id="profilePic">
                            <i class="fas fa-user" style="font-size: 60px; color: #cbd5e0;"></i>
                        </div>
                        <div class="profile-name" id="profileName">Loading...</div>
                        <div class="profile-details" id="profileDetails"></div>
                        <div class="skills-list" id="skillsList"></div>
                        
                        <div class="navigation-buttons">
                            <button class="nav-btn dislike-btn" id="dislikeBtn" title="Dislike">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="nav-btn like-btn" id="likeBtn" title="Like">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
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
    function customLogoutConfirm(e) {
      e.preventDefault();
      showConfirmModal('Are you sure you want to logout?', function(confirmed) {
        if (confirmed) window.location.href = 'logout.php';
      });
      return false;
    }
    </script>
    <script>
        // Debug: Log the matches data
        console.log("PHP Matches data:", <?= json_encode($matches) ?>);
        
        document.addEventListener("DOMContentLoaded", () => {
            const profiles = <?= json_encode($matches) ?>;
            console.log("Loaded profiles:", profiles); // Debug log
            console.log("Number of profiles:", profiles.length);
            
            let filteredProfiles = [...profiles];
            let currentIndex = 0;

            const profilePic = document.getElementById("profilePic");
            const profileName = document.getElementById("profileName");
            const profileDetails = document.getElementById("profileDetails");
            const skillsList = document.getElementById("skillsList");
            const card = document.getElementById("card");
            const likeBtn = document.getElementById("likeBtn");
            const dislikeBtn = document.getElementById("dislikeBtn");
          

            // Load profile data into card
            function loadProfile(index) {
                console.log("Loading profile at index:", index, "Total profiles:", filteredProfiles.length);
                
                if (filteredProfiles.length === 0) {
                    profilePic.innerHTML = `<div style="font-size:24px;color:#777;text-align:center;">No matches found</div>`;
                    profileName.innerHTML = `Try adjusting your filters`;
                    profileDetails.innerHTML = "";
                    skillsList.innerHTML = "";
                    likeBtn.disabled = true;
                    dislikeBtn.disabled = true;
                    likeBtn.style.opacity = 0.3;
                    dislikeBtn.style.opacity = 0.3;
                    return;
                }

                if (index >= filteredProfiles.length) {
                    currentIndex = 0;
                    index = 0;
                }

                if (index < 0) {
                    currentIndex = filteredProfiles.length - 1;
                    index = filteredProfiles.length - 1;
                }

                const p = filteredProfiles[index];
                console.log("Displaying profile:", p);

                profilePic.innerHTML = p.profile_pic && p.profile_pic !== 'uploads/default.jpg' ?
                    `<img src="${p.profile_pic}" onerror="this.style.display='none';this.parentNode.innerHTML='<span style=\'display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:#e0e0e0;color:#888;font-size:60px;border-radius:50%;\'><i class=\\'fas fa-user\\'></i></span>';" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">` :
                    `<span style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:#e0e0e0;color:#888;font-size:60px;border-radius:50%;"><i class='fas fa-user'></i></span>`;
                profileName.innerHTML = `${p.name}`;
                profileDetails.innerHTML = `
                    <strong>${p.age} years old</strong> • ${p.gender}<br>
                    <strong>Course:</strong> ${p.course}<br>
                `;
                skillsList.innerHTML = `
                    <span>📂 Category: ${p.category || 'General'}</span>
                    <span>⚡ Skill: ${p.skill || 'Various Skills'}</span>
                    <span>📊 Level: ${p.proficiency || 'Not Assessed'}</span>
                `;
                
                likeBtn.disabled = false;
                dislikeBtn.disabled = false;
                likeBtn.style.opacity = 1;
                dislikeBtn.style.opacity = 1;
                
         
            }

      
          

            // Like or Dislike action handler
            function swipe(action) {
                const p = filteredProfiles[currentIndex];
                if (!p) return;

                fetch("save_action.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `action=${action}&liked_user_id=${p.user_id}`
                })
                .then(res => res.text())
                .then(() => {
                    card.classList.add(action === 'like' ? "swipe-right" : "swipe-left");
                    setTimeout(() => {
                        card.classList.remove("swipe-left", "swipe-right");
                        filteredProfiles.splice(currentIndex, 1);
                        if (currentIndex >= filteredProfiles.length) currentIndex = 0;
                        loadProfile(currentIndex);
                    }, 400);
                })
                .catch(error => {
                    console.error('Error saving action:', error);
                });
            }

            // Attach click events
            likeBtn.addEventListener("click", () => swipe("like"));
            dislikeBtn.addEventListener("click", () => swipe("dislike"));
       

            // Load first profile
            console.log("Initial load with", filteredProfiles.length, "profiles");
            loadProfile(currentIndex);

            // Clear filters function
            window.clearFilters = function() {
                document.getElementById('categoryFilter').value = '';
                document.getElementById('skillFilter').value = '';
                document.getElementById('proficiencyFilter').value = '';
                document.getElementById('searchInput').value = '';
                window.location.href = 'findmatch.php';
            };

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                // Real-time client-side filtering
                searchInput.addEventListener("input", function() {
                    const term = this.value.toLowerCase();
                    filteredProfiles = profiles.filter(p =>
                        p.course.toLowerCase().includes(term) ||
                        p.gender.toLowerCase().includes(term) ||
                        p.age.toString().includes(term) ||
                        (p.category && p.category.toLowerCase().includes(term)) ||
                        (p.skill && p.skill.toLowerCase().includes(term)) ||
                        (p.proficiency && p.proficiency.toLowerCase().includes(term))
                    );
                    currentIndex = 0;
                    loadProfile(currentIndex);
                });

                // Enter key: trigger server-side search
                searchInput.addEventListener("keypress", function(e) {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        const currentFilters = new URLSearchParams(window.location.search);
                        currentFilters.set('search', this.value);
                        window.location.href = "findmatch.php?" + currentFilters.toString();
                    }
                });
            }

            // Filter form submission
            document.getElementById('filterForm').addEventListener('submit', function(e) {
                const searchValue = document.getElementById('searchInput').value;
                document.querySelector('input[name="search"]').value = searchValue;
            });
        });
    </script>

</body>
</html>
