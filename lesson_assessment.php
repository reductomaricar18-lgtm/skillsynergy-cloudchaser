<?php
session_start();
include 'includes/errors.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "sia1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count like notifications (people who liked you but you haven't liked them back)
$like_notif_count = 0;
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

// Count message notifications from notifications table
$message_notif_count = 0;
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

// Total notification count (likes only)
$notif_count = $like_notif_count;

// Unread message count (from messages table)
$unread_msg_count = 0;
$msgCountQuery = "SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = ? AND is_read = FALSE";
$stmt = $conn->prepare($msgCountQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_msg_count = $row['unread_count'] ?? 0;
$stmt->close();

// 🧠 Your existing lesson and assessment logic (no changes here)
$lesson_files = [];
$lessons_dir = 'lessons(1)/';
if (is_dir($lessons_dir)) {
    $files = scandir($lessons_dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && $file !== 'add_lesson.php') {
            $lesson_name = str_replace(['_', '.php'], [' ', ''], $file);
            $lesson_name = ucwords($lesson_name);
            $lesson_files[] = [
                'name' => $lesson_name,
                'file' => $file,
                'path' => $lessons_dir . $file
            ];
        }
    }
}

// Load assessments
$assessments = [];
if (file_exists('assessments_bank.json')) {
    $assessments_data = json_decode(file_get_contents('assessments_bank.json'), true);
    if ($assessments_data) {
        foreach ($assessments_data as $language => $levels) {
            foreach ($levels as $level => $types) {
                $assessment_name = ucfirst($language) . ' - ' . ucfirst($level);
                $assessments[] = [
                    'name' => $assessment_name,
                    'language' => $language,
                    'level' => $level,
                    'types' => array_keys($types)
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson & Assessment - SkillSynergy</title>
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

        .notif-badge {
            position: absolute;
            top: 5px;
            right: 10px;
            background: #e53e3e;
            color: white;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 12px;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        .content-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 60px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .profile-icon:hover {
            transform: scale(1.1);
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
        }

        .tab {
            padding: 15px 30px;
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            color: #718096;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab:hover {
            color: #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .card-description {
            color: #718096;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .card-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .assessment-types {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
            justify-content: center;
        }

        .type-badge {
            background: #edf2f7;
            color: #4a5568;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
        }


        .search-box {
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            max-width: 400px;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #cbd5e0;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #4a5568;
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

            .grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<input type="hidden" id="currentUserId" value="<?= $_SESSION['user_id'] ?>">

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
                    <a href="lesson_assessment.php" class="nav-link active">
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
                    <h1 class="page-title">Lesson & Assessment</h1>
                    <div class="profile-dropdown">
                        <a href="user_profile.php" class="profile-icon">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab active" onclick="showTab('lessons')">
                        <i class="fas fa-book-open"></i> Lessons
                    </button>
                    <button class="tab" onclick="showTab('assessments')">
                        <i class="fas fa-clipboard-check"></i> Assessments
                    </button>
                </div>

                <div id="lessons" class="tab-content active">
                    <div class="search-box">
                        <input type="text" class="search-input" id="lessonSearch" placeholder="Search lessons..." onkeyup="filterLessons()">
                    </div>
                    
                    <div class="grid" id="lessonsGrid">
                        <?php if (empty($lesson_files)): ?>
                            <div class="empty-state">
                                <i class="fas fa-book"></i>
                                <h3>No Lessons Available</h3>
                                <p>Lessons will appear here once they are added to the system.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($lesson_files as $lesson): ?>
                                <div class="card lesson-card" data-name="<?= strtolower($lesson['name']) ?>">
                                    <div class="card-header">
                                        <div class="card-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <h3 class="card-title"><?= $lesson['name'] ?></h3>
                                    </div>
                                    <p class="card-description">
                                        Comprehensive learning material for <?= $lesson['name'] ?>. 
                                        Click to start learning and improve your skills.
                                    </p>
                                    <div class="card-actions">
                                        <a href="lessons(1)/<?= $lesson['file'] ?>" class="btn btn-primary" target="_blank">
                                            <i class="fas fa-play"></i> Start Learning
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="assessments" class="tab-content">
                    <div class="search-box">
                        <input type="text" class="search-input" id="assessmentSearch" placeholder="Search assessments..." onkeyup="filterAssessments()">
                    </div>
                    
                    <div class="grid" id="assessmentsGrid">
                        <?php if (empty($assessments)): ?>
                            <div class="empty-state">
                                <i class="fas fa-clipboard-check"></i>
                                <h3>No Assessments Available</h3>
                                <p>Assessments will appear here once they are added to the system.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($assessments as $assessment): ?>
                                <div class="card assessment-card" data-name="<?= strtolower($assessment['name']) ?>">
                                    <div class="card-header">
                                        <div class="card-icon">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <h3 class="card-title"><?= $assessment['name'] ?></h3>
                                    </div>
                                    <p class="card-description">
                                        Test your knowledge in <?= $assessment['language'] ?> at <?= $assessment['level'] ?> level.
                                    
                                    </p>
                                    <div class="assessment-types">
                                        <?php foreach ($assessment['types'] as $type): ?>
                                            <span class="type-badge"><?= ucfirst($type) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="card-actions">
                                        <a href="assessment_system.php?language=<?= urlencode($assessment['language']) ?>&level=<?= urlencode($assessment['level']) ?>" class="btn btn-primary" target="_blank">
                                            <i class="fas fa-play"></i> Start Assessment
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script>
    const socket = io("http://localhost:3000"); // or your actual host

    const userId = document.getElementById('currentUserId').value;

    // Join room based on user ID
    socket.emit("joinRoom", userId);

    // Listen for new notifications
    socket.on("newNotification", data => {
        if (data.userId == userId) {
            const notifBadge = document.querySelector(".nav-item a[href='notificationtab.php'] .notif-badge");
            if (notifBadge) {
                notifBadge.textContent = parseInt(notifBadge.textContent) + 1;
            } else {
                const newBadge = document.createElement("span");
                newBadge.className = "notif-badge";
                newBadge.textContent = "1";
                document.querySelector(".nav-item a[href='notificationtab.php']").appendChild(newBadge);
            }
        }
    });

    // Listen for new messages
    socket.on("newMessage", data => {
        if (data.receiverId == userId) {
            const msgBadge = document.querySelector(".nav-item a[href='message.php'] .notif-badge");
            if (msgBadge) {
                msgBadge.textContent = parseInt(msgBadge.textContent) + 1;
            } else {
                const newBadge = document.createElement("span");
                newBadge.className = "notif-badge";
                newBadge.textContent = "1";
                document.querySelector(".nav-item a[href='message.php']").appendChild(newBadge);
            }
        }
    });
</script>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }


        function filterLessons() {
            const searchTerm = document.getElementById('lessonSearch').value.toLowerCase();
            const lessonCards = document.querySelectorAll('.lesson-card');

            lessonCards.forEach(card => {
                const lessonName = card.getAttribute('data-name');
                card.style.display = lessonName.includes(searchTerm) ? 'block' : 'none';
            });
        }

        function filterAssessments() {
            const searchTerm = document.getElementById('assessmentSearch').value.toLowerCase();
            const assessmentCards = document.querySelectorAll('.assessment-card');
            
            assessmentCards.forEach(card => {
                const assessmentName = card.getAttribute('data-name');
                if (assessmentName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function showAssessmentDetails(language, level) {
            // You can implement a modal or redirect to show detailed assessment information
            alert(`Assessment Details:\nLanguage: ${language}\nLevel: ${level}\n\nThis assessment contains multiple question types to test your knowledge.`);
        }
    </script>


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