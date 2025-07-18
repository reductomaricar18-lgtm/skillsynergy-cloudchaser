<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) die("Database Error: " . $conn->connect_error);

$email = $_SESSION['email'];

// Get current user ID
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentUserId);
$stmt->fetch();
$stmt->close();

// Default notif count
$notif_count = 0;

if ($currentUserId) {
    // Fetch notification count
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
    $stmt->bind_param("ii", $currentUserId, $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $notif_count = $row['notif_count'] ?? 0;
    $stmt->close();
}

// Fetch matched users for message previews (mutual likes)
$msg_stmt = $conn->prepare("
    SELECT u.user_id, up.first_name, up.last_name, 
           COALESCE(up.profile_pic, 'uploads/default.jpg') AS profile_pic
    FROM users u
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN user_likes ul1 ON ul1.liked_user_id = u.user_id AND ul1.user_id = ?
    JOIN user_likes ul2 ON ul2.user_id = u.user_id AND ul2.liked_user_id = ?
    GROUP BY u.user_id, up.first_name, up.last_name, up.profile_pic
    ORDER BY up.first_name ASC
");
$msg_stmt->bind_param("ii", $currentUserId, $currentUserId);
$msg_stmt->execute();
$msg_result = $msg_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>SkillSynergy - Messages</title>
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

        .messages-container {
            display: flex;
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            margin: 20px 40px 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .contacts-sidebar {
            width: 350px;
            background: rgba(255, 255, 255, 0.5);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .contacts-header {
            padding: 25px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
        }

        .contacts-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 15px;
            padding: 12px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .search-box i {
            color: #a0aec0;
            margin-right: 10px;
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 14px;
        }

        .contacts-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .contact-item:hover {
            background: rgba(102, 126, 234, 0.1);
            border-left-color: #667eea;
        }

        .contact-item.active {
            background: rgba(102, 126, 234, 0.15);
            border-left-color: #667eea;
        }

        .contact-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .contact-info {
            flex: 1;
        }

        .contact-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 2px;
        }

        .contact-status {
            font-size: 12px;
            color: #718096;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 25px;
            background: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .chat-user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid rgba(102, 126, 234, 0.2);
        }

        .chat-user-info h3 {
            color: #2d3748;
            font-weight: 700;
        }

        .chat-user-info p {
            color: #718096;
            font-size: 14px;
        }

        .messages-area {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
        }

        .message {
            display: flex;
            margin-bottom: 20px;
            align-items: flex-end;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.4;
            position: relative;
        }

        .message.sent .message-content {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-bottom-right-radius: 5px;
        }

        .message.received .message-content {
            background: white;
            color: #2d3748;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
        }

        .message-input-area {
            padding: 25px;
            background: rgba(255, 255, 255, 0.8);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .message-input-container {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 25px;
            padding: 12px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .message-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 16px;
            color: #2d3748;
        }

        .send-button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .send-button:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .empty-chat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #718096;
            text-align: center;
        }

        .empty-chat i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #e2e8f0;
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

            .contacts-sidebar {
                width: 100%;
                max-width: 300px;
            }

            .messages-container {
                margin: 20px;
                flex-direction: column;
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
                    <a href="message.php" class="nav-link active">
                        <i class="fas fa-comment-dots"></i>
                        Messages
                    </a>
                </li>
                <li class="nav-item" style="margin-top: 20px;">
                    <a href="logout.php" class="nav-link" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1 class="page-title">Messages</h1>
                <div class="profile-dropdown">
                    <a href="user_profile.php" class="profile-icon">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="user_profile.php">View Profile</a>
                    </div>
                </div>
            </div>

            <div class="messages-container">
                <div class="contacts-sidebar">
                    <div class="contacts-header">
                        <h3 class="contacts-title">Conversations</h3>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search conversations..." id="searchContacts">
                        </div>
                    </div>
                    
                    <div class="contacts-list" id="contactsList">
                        <?php if ($msg_result->num_rows > 0): ?>
                            <?php while ($contact = $msg_result->fetch_assoc()): ?>
                                <div class="contact-item" onclick="selectContact(<?= $contact['user_id'] ?>, '<?= addslashes($contact['first_name'] . ' ' . $contact['last_name']) ?>', '<?= addslashes($contact['profile_pic']) ?>')">
                                    <img src="<?= htmlspecialchars($contact['profile_pic']) ?>" 
                                         alt="Profile" class="contact-avatar"
                                         onerror="this.src='uploads/default.jpg'">
                                    <div class="contact-info">
                                        <div class="contact-name">
                                            <?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?>
                                        </div>
                                        <div class="contact-status">Available to chat</div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 50px 20px; color: #718096;">
                                <i class="fas fa-user-friends" style="font-size: 3rem; margin-bottom: 15px; color: #e2e8f0;"></i>
                                <p>No matches to message yet</p>
                                <p style="font-size: 14px; margin-top: 5px;">Find and match with people first!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chat-area" id="chatArea">
                    <div class="empty-chat">
                        <i class="fas fa-comment-dots"></i>
                        <h3>Select a conversation</h3>
                        <p>Choose a contact from the left to start messaging</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/socket.io/socket.io.js"></script>
    <script>
        const socket = io();
        let currentChatUserId = null;
        const currentUserId = <?= $currentUserId ?>;

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

        socket.on("receive_message", function(data) {
            if (currentChatUserId && data.sender_id == currentChatUserId) {
                appendMessage(data.message, 'received', data.timestamp);
            }
        });

        function selectContact(userId, name, profilePic) {
            currentChatUserId = userId;
            
            // Update active contact
            document.querySelectorAll('.contact-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Load chat interface
            loadChatInterface(userId, name, profilePic);
            
            // Load messages
            loadMessages(userId);
        }

        function loadChatInterface(userId, name, profilePic) {
            const chatArea = document.getElementById('chatArea');
            chatArea.innerHTML = `
                <div class="chat-header">
                    <img src="${profilePic}" alt="Profile" class="chat-user-avatar" onerror="this.src='uploads/default.jpg'">
                    <div class="chat-user-info">
                        <h3>${name}</h3>
                        <p>Online</p>
                    </div>
                </div>
                <div class="messages-area" id="messagesArea">
                    <!-- Messages will be loaded here -->
                </div>
                <div class="message-input-area">
                    <div class="message-input-container">
                        <input type="text" class="message-input" id="messageInput" 
                               placeholder="Type your message..." 
                               onkeypress="if(event.key==='Enter') sendMessage()" disabled>
                        <button class="send-button" id="sendButton" onclick="sendMessage()" disabled>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        function loadMessages(userId) {
            fetch('fetch_messages.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `other_user_id=${userId}`
            })
            .then(response => response.json())
            .then(messages => {
                const messagesArea = document.getElementById('messagesArea');
                messagesArea.innerHTML = '';
                
                messages.forEach(msg => {
                    const messageType = msg.sender_id == currentUserId ? 'sent' : 'received';
                    appendMessage(msg.message, messageType, msg.timestamp);
                });
                
                messagesArea.scrollTop = messagesArea.scrollHeight;
            })
            .catch(error => console.error('Error loading messages:', error));
        }

        function appendMessage(message, type, timestamp) {
            const messagesArea = document.getElementById('messagesArea');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            
            const time = new Date(timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    ${message}
                    <div class="message-time">${time}</div>
                </div>
            `;
            
            messagesArea.appendChild(messageDiv);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message || !currentChatUserId) return;
            
            fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `receiver_id=${currentChatUserId}&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    appendMessage(message, 'sent', new Date().toISOString());
                    messageInput.value = '';
                    
                    // Emit to socket for real-time delivery
                    socket.emit('send_message', {
                        receiver_id: currentChatUserId,
                        sender_id: currentUserId,
                        message: message,
                        timestamp: new Date().toISOString()
                    });
                }
            })
            .catch(error => console.error('Error sending message:', error));
        }

        // Search functionality
        document.getElementById('searchContacts').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const contacts = document.querySelectorAll('.contact-item');
            
            contacts.forEach(contact => {
                const name = contact.querySelector('.contact-name').textContent.toLowerCase();
                contact.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });

        // Check for user_id parameter to auto-select conversation
        const urlParams = new URLSearchParams(window.location.search);
        const targetUserId = urlParams.get('user_id');
        if (targetUserId) {
            const targetContact = document.querySelector(`[onclick*="${targetUserId}"]`);
            if (targetContact) {
                targetContact.click();
            }
        }
    </script>
</body>
</html>
