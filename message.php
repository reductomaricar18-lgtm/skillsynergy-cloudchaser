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
    // Fetch people who liked you but you haven't liked them back
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
    $stmt->bind_param("ii", $currentUserId, $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seen_notifications = [];
    while ($row = $result->fetch_assoc()) {
        if (!isset($seen_notifications[$row['user_id']])) {
            $notifications[] = $row;
            $seen_notifications[$row['user_id']] = true;
        }
    }
    $stmt->close();
    $notif_count = count($notifications);
}

// Fetch matched users for message previews (only those with message history)
$msg_stmt = $conn->prepare("
    SELECT u.user_id, up.first_name, up.last_name, 
           COALESCE(up.profile_pic, 'uploads/default.jpg') AS profile_pic,
           MAX(m.sent_at) AS last_message_time
    FROM users u
    JOIN users_profile up ON u.user_id = up.user_id
    JOIN messages m ON (
        (m.sender_id = ? AND m.receiver_id = u.user_id) OR 
        (m.sender_id = u.user_id AND m.receiver_id = ?)
    )
    GROUP BY u.user_id, up.first_name, up.last_name, up.profile_pic
    ORDER BY last_message_time DESC
");
$msg_stmt->bind_param("ii", $currentUserId, $currentUserId);
$msg_stmt->execute();
$msg_result = $msg_stmt->get_result();

// Add after $currentUserId is set
$chatUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$learningRequest = null;
if ($chatUserId) {
    $stmt = $conn->prepare("SELECT * FROM learning_requests WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?) ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("iiii", $currentUserId, $chatUserId, $chatUserId, $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $learningRequest = $result->fetch_assoc();
    }
    $stmt->close();
}
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
            height: calc(100vh - 160px);
            max-height: 700px;
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
            max-height: 400px;
        }

        .messages-area::-webkit-scrollbar {
            width: 8px;
        }

        .messages-area::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .messages-area::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.6);
            border-radius: 4px;
        }

        .messages-area::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.8);
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

        .attachment-icon {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .attachment-icon:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: scale(1.1);
        }

        .attachment-dropdown {
            position: absolute;
            bottom: 45px;
            left: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 8px 0;
            min-width: 150px;
            display: none;
            z-index: 1000;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .attachment-dropdown.active {
            display: block;
        }

        .attachment-dropdown a {
            display: block;
            padding: 12px 16px;
            color: #4a5568;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .attachment-dropdown a:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
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

        /* Disabled state styles */
        .message-input:disabled {
            background-color: #f0f0f0;
            color: #888;
            cursor: not-allowed;
        }

        .send-button:disabled {
            background: #ccc !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .attachment-icon.disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }


        /* Chat Disabled Message Styles */
        .chat-disabled-message {
            margin-top: auto;
            background-color: #f8f9fb;
            border-radius: 12px 12px 0 0;
            padding: 20px;
            color: #4a5568;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
            width: 100%;
        }

        .chat-disabled-message .disabled-icon {
            font-size: 2rem;
            color: #a0aec0;
        }

        .chat-disabled-message h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .chat-disabled-message p {
            margin: 0;
            font-size: 0.95rem;
            color: #718096;
        }

        .chat-disabled-message .disabled-actions {
            margin-top: 10px;
        }

        .chat-disabled-message .btn-primary {
            background-color: #667eea;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 0.95rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .chat-disabled-message .btn-primary:hover {
            background-color: #5a67d8;
            transform: translateY(-2px);
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

        /* Rating Modal Styles */
        .rating-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .rating-modal.show {
            display: flex;
        }

        .rating-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .rating-content h3 {
            color: #2d3748;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .rating-content p {
            color: #718096;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .rating-categories {
            margin-bottom: 30px;
        }

        .rating-category {
            margin-bottom: 20px;
            text-align: left;
        }

        .rating-category label {
            display: block;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
        }

        .star-rating {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .star {
            font-size: 30px;
            color: #e2e8f0;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star:hover,
        .star.active {
            color: #ffd700;
        }

        .feedback-textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 10px;
            resize: vertical;
            min-height: 100px;
            margin-bottom: 20px;
            font-family: inherit;
        }

        .rating-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .rating-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .rating-btn.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .rating-btn.secondary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .rating-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        /* Assessment Modal Styles */
        .assessment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .assessment-modal.show {
            display: flex;
        }

        .assessment-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            width: 90%;
            max-height: 80%;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .assessment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .assessment-header h3 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .assessment-header p {
            color: #718096;
            font-size: 16px;
        }

        .assessment-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            border-radius: 10px;
        }

        .section-progress {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .section-progress.completed {
            color: #38a169;
        }

        .section-progress.current {
            color: #667eea;
        }

        .section-progress.pending {
            color: #a0aec0;
        }

        .assessment-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 10px;
            text-align: center;
        }

        .question-container {
            margin-bottom: 25px;
            padding: 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 10px;
            background: #f8fafc;
        }

        .question-text {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .question-choices {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .choice-option {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: white;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .choice-option:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .choice-option input[type="radio"] {
            margin-right: 12px;
            accent-color: #667eea;
        }

        .code-question {
            background: #1a202c;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
            margin: 10px 0;
            overflow-x: auto;
        }

        .text-answer {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
        }

        .assessment-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .assessment-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .assessment-btn.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .assessment-btn.secondary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .assessment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .assessment-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Add this CSS to your <style> section or in a <style> tag in the <head> */
        .skill-modal {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100vw;
          height: 100vh;
          background: rgba(20, 20, 20, 0.85);
          z-index: 2000;
          justify-content: center;
          align-items: center;
        }
        .skill-modal-content {
          background: #23272a;
          border-radius: 18px;
          padding: 32px 24px;
          max-width: 350px;
          margin: auto;
          text-align: center;
          color: #fff;
          box-shadow: 0 8px 32px rgba(0,0,0,0.35);
        }
        #skillPromptText {
          color: #fff;
          margin-bottom: 18px;
          font-size: 1.1rem;
        }
        #skillButtons {
          display: flex;
          flex-direction: column;
          gap: 14px;
          margin-bottom: 18px;
        }
        .skill-btn {
          width: 100%;
          padding: 14px 0;
          border-radius: 12px;
          border: none;
          background: #36393f;
          color: #fff;
          font-size: 1rem;
          font-weight: 500;
          margin: 0 auto;
          cursor: pointer;
          transition: background 0.2s;
          box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .skill-btn:hover {
          background: #50545c;
        }
        #skillPromptModal button[onclick*='closeSkillPrompt'] {
          background: #2d2f31;
          color: #fff;
          border: none;
          border-radius: 10px;
          padding: 10px 0;
          width: 100%;
          margin-top: 8px;
          font-size: 1rem;
          cursor: pointer;
          transition: background 0.2s;
        }
        #skillPromptModal button[onclick*='closeSkillPrompt']:hover {
          background: #44474a;
        }
        .lesson-area-panel {
            position: absolute;
            right: 0;
            top: 0;
            width: 500px;
            height: 100%;
            background: #f7fafc;
            border-left: 2px solid #e2e8f0;
            box-shadow: -2px 0 12px rgba(0,0,0,0.06);
            z-index: 10;
            display: flex;
            flex-direction: column;
            padding: 0;
        }
        .lesson-area-panel .lesson-header {
            flex: 0 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            background: #667eea;
            color: #fff;
            border-bottom: 1px solid #e2e8f0;
        }
        .lesson-area-panel .lesson-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }
        .lesson-area-panel .close-lesson-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0 8px;
            transition: color 0.2s;
        }
        .lesson-area-panel .close-lesson-btn:hover {
            color: #fc8181;
        }
        .lesson-area-panel .lesson-scrollable-panel {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 120px); /* Adjusted height to show all content */
            max-height: calc(100vh - 120px); /* Prevent overflow */
            position: relative; /* Ensure proper positioning */
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }
        .lesson-area-panel .lesson-content {
            padding: 20px;
            padding-bottom: 80px; /* Extra padding at bottom for Take Assessment button */
            flex: 1;
            width: 100%;
            overflow-y: auto; /* Allow scrolling within content area */
            max-height: 100%; /* Prevent content from overflowing */
            box-sizing: border-box; /* Include padding in width calculation */
            position: relative; /* Ensure proper positioning */
        }
        @media (max-width: 900px) {
            .lesson-area-panel {
                width: 100vw;
                left: 0;
                right: 0;
                top: 0;
                height: 60vh;
                max-width: 100vw;
                border-left: none;
                border-top: 2px solid #e2e8f0;
                box-shadow: 0 -2px 12px rgba(0,0,0,0.06);
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
                        <?php 
                        // Calculate unread messages for badge
                        $conn = new mysqli('localhost', 'root', '', 'sia1');
                        $unread_msg_count = 0;
                        if ($conn->connect_error === false) {
                            $msgCountQuery = "SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = $currentUserId AND is_read = FALSE";
                            $msgResult = $conn->query($msgCountQuery);
                            if ($msgResult && $msgRow = $msgResult->fetch_assoc()) {
                                $unread_msg_count = $msgRow['unread_count'] ?? 0;
                            }
                            $conn->close();
                        }
                        ?>
                        <?php if ($unread_msg_count > 0): ?>
                            <span class="msg-badge"><?= $unread_msg_count ?></span>
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
                    <h1 class="page-title">Messages</h1>
                    <div class="profile-dropdown">
                        <a href="user_profile.php" class="profile-icon">
                            <i class="fas fa-user"></i>
                        </a>
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
                                        <img src="<?= !empty($contact['profile_pic']) ? htmlspecialchars($contact['profile_pic']) : 'uploads/default.png' ?>" 
                                        alt="Profile" class="contact-avatar"
                                        onerror="this.onerror=null;this.src='uploads/default.png';">
                                    <div class="contact-info">
                                        <div class="contact-name">
                                            <?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?>
                                        </div>
                                        <div class="contact-status">Available to chat</div>
                                        <?php if (!empty($contact['last_message_time'])): ?>
                                            <div class="contact-last-message-time" style="font-size: 12px; color: #888;">
                                                <?= date('M d, H:i', strtotime($contact['last_message_time'])) ?>
                                            </div>
                                        <?php endif; ?>
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

    <!-- Rating Modal -->
    <div class="rating-modal" id="ratingModal">
        <div class="rating-content">
            <h3>Rate Your Experience</h3>
            <p>Please rate your tutoring session and provide feedback</p>
            
            <div class="rating-categories">
                <div class="rating-category">
                    <label>Understanding Rating:</label>
                    <div class="star-rating" data-category="understanding">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                </div>
                
                <div class="rating-category">
                    <label>Knowledge Sharing Rating:</label>
                    <div class="star-rating" data-category="knowledge">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                </div>
                
                <div class="rating-category">
                    <label>Listening Rating:</label>
                    <div class="star-rating" data-category="listening">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                </div>
            </div>
            
            <textarea class="feedback-textarea" id="feedbackText" placeholder="Optional: Share your feedback about this session..."></textarea>
            
            <div class="rating-buttons">
                <button class="rating-btn secondary" onclick="closeRatingModal()">Skip</button>
                <button class="rating-btn primary" onclick="submitRating()">Submit Rating</button>
            </div>
        </div>
    </div>

    <!-- Assessment Modal -->
    <div class="assessment-modal" id="assessmentModal">
        <div class="assessment-content">
            <div class="assessment-header">
                <h3>Tutoring Session Assessment</h3>
                <p>Please complete this assessment based on your <span id="userSkill"></span> knowledge</p>
            </div>
            
            <div class="assessment-progress">
                <div class="section-progress current" id="multipleChoiceProgress">
                    <i class="fas fa-list-ul"></i>
                    Multiple Choice
                </div>
                <div class="section-progress pending" id="debuggingProgress">
                    <i class="fas fa-bug"></i>
                    Debugging
                </div>
                <div class="section-progress pending" id="codingProgress">
                    <i class="fas fa-code"></i>
                    Coding
                </div>
            </div>
            
            <form id="assessmentForm">
                <!-- Multiple Choice Section -->
                <div class="assessment-section" id="multipleChoiceSection">
                    <div class="section-title">Multiple Choice Questions</div>
                    <div id="multipleChoiceQuestions"></div>
                </div>
                
                <!-- Debugging Section -->
                <div class="assessment-section" id="debuggingSection" style="display:none;">
                    <div class="section-title">Debugging Questions</div>
                    <div id="debuggingQuestions"></div>
                </div>
                
                <!-- Coding Section -->
                <div class="assessment-section" id="codingSection" style="display:none;">
                    <div class="section-title">Coding Questions</div>
                    <div id="codingQuestions"></div>
                </div>
                
                <div class="assessment-buttons">
                    <button type="button" class="assessment-btn secondary" onclick="closeAssessmentModal()">Cancel</button>
                    <button type="button" class="assessment-btn secondary" id="previousSectionBtn" onclick="previousSection()" style="display:none;">Previous</button>
                    <button type="button" class="assessment-btn primary" id="nextSectionBtn" onclick="nextSection()">Next Section</button>
                    <button type="button" class="assessment-btn primary" id="submitAssessmentBtn" onclick="submitAssessment()" style="display:none;">Submit Assessment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Rating Display Modal -->
    <div class="rating-modal" id="userRatingModal">
        <div class="rating-content">
            <span class="close" onclick="closeUserRatingModal()" style="position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #aaa;">&times;</span>
            <h3 id="ratingModalTitle">User Rating</h3>
            <div id="userRatingContent">
                <!-- Rating content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Assessment Result Modal -->
    <div class="rating-modal" id="assessmentResultModal">
        <div class="rating-content">
            <span class="close" onclick="closeAssessmentResultModal()" style="position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #aaa;">&times;</span>
            <h3>Assessment Results</h3>
            <div id="assessmentResultContent">
                <!-- Assessment result content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Assessment Result Modal -->
    <div class="assessment-modal" id="assessmentResultModal">
        <div class="assessment-content">
            <span class="close" onclick="closeAssessmentResultModal()" style="position: absolute; right: 20px; top: 15px; font-size: 28px; cursor: pointer; color: #aaa;">&times;</span>
            <h3>Assessment Results</h3>
            <div id="assessmentResultContent">
                <!-- Assessment result content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        let currentChatUserId = null;
        const currentUserId = <?= $currentUserId ?>;

        function selectContact(userId, name, profilePic) {
            console.log('Selecting contact:', userId, name); // Debug log
            
            // Remove socket.io dependency to avoid errors
            currentChatUserId = userId;
            
            // Save current conversation to localStorage for persistence
            localStorage.setItem('lastContactId', userId);
            localStorage.setItem('lastContactName', name);
            localStorage.setItem('lastContactPic', profilePic);
            
            // Update active contact
            document.querySelectorAll('.contact-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Find the clicked contact and make it active
            const clickedContact = event.target.closest('.contact-item');
            if (clickedContact) {
                clickedContact.classList.add('active');
            }
            
            // Load chat interface
            loadChatInterface(userId, name, profilePic);
            
            // Load messages
            loadMessages(userId);
        }

        function loadChatInterface(userId, name, profilePic) {
            const chatArea = document.getElementById('chatArea');
            chatArea.innerHTML = `
                <div class="chat-header">
                    <img src="${profilePic || 'uploads/default.png'}" 
                    alt="Profile" 
                    class="chat-user-avatar" 
                    onerror="this.onerror=null;this.src='uploads/default.png';">
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
                        <div class="attachment-icon" onclick="toggleAttachmentDropdown()">
                            <i class="fas fa-paperclip"></i>
                            <div class="attachment-dropdown" id="attachmentDropdown">
                                <input type="file" id="fileInput" style="display:none;" onchange="handleFileSelect(event)">
                                <a href="#" onclick="triggerFileInput()" id="sendFileBtn"><i class="fas fa-file"></i> Send File</a>
                                <a href="#" onclick="rateUser()" id="rateUserBtn" style="display:none;"><i class="fas fa-star"></i> Rate User</a>
                                <a href="#" onclick="showUserRating()" id="viewUserRatingBtn" style="display:none;"><i class="fas fa-star"></i> View User Rating</a>
                                <a href="#" onclick="endSession()" id="endSessionBtn"><i class="fas fa-sign-out-alt"></i> End Session</a>
                            </div>
                        </div>
                        <input type="text" class="message-input" id="messageInput" 
                               placeholder="Type your message..." 
                               onkeypress="if(event.key==='Enter') sendMessage()">
                        <button class="send-button" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        function loadMessages(userId) {
            if (!userId) return;
            
            // Store current scroll position before loading
            const messagesArea = document.getElementById('messagesArea');
            const wasAtBottom = messagesArea ? 
                (messagesArea.scrollTop + messagesArea.clientHeight >= messagesArea.scrollHeight - 50) : true;
            
            fetch(`fetch_messages.php?receiver_id=${userId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Messages loaded:', data); // Debug log
                const messagesArea = document.getElementById('messagesArea');
                if (!messagesArea) return;
                
                // Store scroll position
                const currentScrollTop = messagesArea.scrollTop;
                
                messagesArea.innerHTML = '';
                
                // Check if session has ended
                if (data.is_session_ended) {
                    const messageInput = document.getElementById('messageInput');
                    const sendButton = document.querySelector('.send-button');
                    const attachmentIcon = document.querySelector('.attachment-icon');
                    const sendFileBtn = document.getElementById('sendFileBtn');
                    const endSessionBtn = document.getElementById('endSessionBtn');
                    const rateUserBtn = document.getElementById('rateUserBtn');
                    const viewUserRatingBtn = document.getElementById('viewUserRatingBtn');
                    
                    if (messageInput && sendButton) {
                        messageInput.disabled = true;
                        messageInput.placeholder = 'Session has ended';
                        sendButton.disabled = true;
                    }
                    
                    // Disable attachment functionality
                    if (attachmentIcon) {
                        attachmentIcon.style.opacity = '0.5';
                        attachmentIcon.style.pointerEvents = 'none';
                        attachmentIcon.title = 'Session has ended - Cannot send files';
                    }
                    
                    // Hide file sending and end session buttons
                    if (sendFileBtn) sendFileBtn.style.display = 'none';
                    if (endSessionBtn) endSessionBtn.style.display = 'none';
                    
                    // Show rating button (will check if user has rated)
                    console.log('Session ended - checking rating and assessment status');
                    checkUserRatingStatus();
                    checkAssessmentStatus();
                } else {
                    // Session is active - ensure UI is enabled
                    const messageInput = document.getElementById('messageInput');
                    const sendButton = document.querySelector('.send-button');
                    const attachmentIcon = document.querySelector('.attachment-icon');
                    const sendFileBtn = document.getElementById('sendFileBtn');
                    const endSessionBtn = document.getElementById('endSessionBtn');
                    const rateUserBtn = document.getElementById('rateUserBtn');
                    const viewUserRatingBtn = document.getElementById('viewUserRatingBtn');
                    
                    if (messageInput && sendButton) {
                        messageInput.disabled = false;
                        messageInput.placeholder = 'Type your message...';
                        sendButton.disabled = false;
                    }
                    
                    // Enable attachment functionality
                    if (attachmentIcon) {
                        attachmentIcon.style.opacity = '1';
                        attachmentIcon.style.pointerEvents = 'auto';
                        attachmentIcon.title = '';
                    }
                    
                    // Show appropriate buttons for active session
                    console.log('Session active - showing file/end session buttons');
                    if (sendFileBtn) sendFileBtn.style.display = 'inline-block';
                    if (endSessionBtn) endSessionBtn.style.display = 'inline-block';
                    if (rateUserBtn) rateUserBtn.style.display = 'none';
                    if (viewUserRatingBtn) viewUserRatingBtn.style.display = 'none';
                    
                    // Show take assessment button
                    checkAssessmentStatus();
                }
                
                if (data.status === 'success' && Array.isArray(data.messages) && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const messageType = msg.sender_id == currentUserId ? 'sent' : 'received';
                        const messageText = msg.message_text || msg.message;
                        const timestamp = msg.sent_at || msg.timestamp;
                        
                        // Pass the full message data for file handling
                        appendMessage(messageText, messageType, timestamp, msg);
                    });
                } else {
                    // Show empty state if no messages
                    messagesArea.innerHTML = `
                        <div style="text-align: center; padding: 50px 20px; color: #718096;">
                            <i class="fas fa-comment" style="font-size: 2rem; margin-bottom: 10px; color: #e2e8f0;"></i>
                            <p>No messages yet</p>
                            <p style="font-size: 14px; margin-top: 5px;">Start the conversation!</p>
                        </div>
                    `;
                }
                
                // Only scroll to bottom if user was already at bottom or if it's the first load
                if (wasAtBottom || currentScrollTop === 0) {
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                } else {
                    // Restore previous scroll position to prevent jumping
                    messagesArea.scrollTop = currentScrollTop;
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                const messagesArea = document.getElementById('messagesArea');
                if (messagesArea) {
                    messagesArea.innerHTML = `
                        <div style="text-align: center; padding: 50px 20px; color: #e53e3e;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Error loading messages</p>
                            <p style="font-size: 14px; margin-top: 5px;">Please try refreshing the page</p>
                        </div>
                    `;
                }
            });
        }

        function appendMessage(message, type, timestamp, messageData = null) {
            const messagesArea = document.getElementById('messagesArea');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            
            const time = new Date(timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            let messageContent = message;
            
            // Handle file messages if messageData is provided
            if (messageData && messageData.file_path) {
                const fileName = messageData.file_name || 'Unknown file';
                const fileType = messageData.file_type || '';
                
                if (fileType.startsWith('image/')) {
                    // Display image
                    messageContent = `
                        <div class="file-message">
                            <img src="${messageData.file_path}" alt="${fileName}" 
                                 style="max-width: 200px; max-height: 200px; border-radius: 10px; cursor: pointer;"
                                 onclick="window.open('${messageData.file_path}', '_blank')">
                            <div style="font-size: 12px; margin-top: 5px; opacity: 0.8;">📷 ${fileName}</div>
                        </div>
                    `;
                } else {
                    // Display file link
                    const fileIcon = getFileIcon(fileType);
                    messageContent = `
                        <div class="file-message">
                            <a href="${messageData.file_path}" target="_blank" download="${fileName}" 
                               style="color: inherit; text-decoration: none; display: flex; align-items: center; padding: 10px; background: rgba(0,0,0,0.1); border-radius: 8px;">
                                <span style="font-size: 20px; margin-right: 10px;">${fileIcon}</span>
                                <div>
                                    <div style="font-weight: 600;">${fileName}</div>
                                    <div style="font-size: 12px; opacity: 0.8;">${formatFileSize(messageData.file_size || 0)}</div>
                                </div>
                            </a>
                        </div>
                    `;
                }
            }
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    ${messageContent}
                    <div class="message-time">${time}</div>
                </div>
            `;
            
            messagesArea.appendChild(messageDiv);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        function getFileIcon(fileType) {
            if (fileType.includes('pdf')) return '📄';
            if (fileType.includes('word') || fileType.includes('document')) return '📝';
            if (fileType.includes('excel') || fileType.includes('sheet')) return '📊';
            if (fileType.includes('powerpoint') || fileType.includes('presentation')) return '📈';
            if (fileType.includes('text')) return '📃';
            if (fileType.includes('zip') || fileType.includes('rar')) return '📦';
            return '📎';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message || !currentChatUserId) {
                console.log('No message or no chat user selected');
                return;
            }
            
            // Immediately add message to UI for better UX
            appendMessage(message, 'sent', new Date().toISOString());
            messageInput.value = '';
            
            fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `receiver_id=${currentChatUserId}&message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(result => {
                console.log('Message send result:', result); // Debug log
                if (result.status !== 'success') {
                    console.error('Failed to send message:', result);
                    
                    // If session has ended, disable input and show appropriate message
                    if (result.message && result.message.includes('Session has ended')) {
                        const messageInput = document.getElementById('messageInput');
                        const sendButton = document.querySelector('.send-button');
                        const attachmentIcon = document.querySelector('.attachment-icon');
                        
                        if (messageInput && sendButton) {
                            messageInput.disabled = true;
                            messageInput.placeholder = 'Session has ended';
                            sendButton.disabled = true;
                        }
                        
                        // Disable attachment functionality
                        if (attachmentIcon) {
                            attachmentIcon.style.opacity = '0.5';
                            attachmentIcon.style.pointerEvents = 'none';
                            attachmentIcon.title = 'Session has ended - Cannot send files';
                        }
                        
                        alert('Session has ended. Cannot send more messages.');
                    } else {
                        alert('Failed to send message: ' + result.message);
                    }
                    
                    // Remove the optimistically added message on error
                    const messagesArea = document.getElementById('messagesArea');
                    const lastMessage = messagesArea.lastElementChild;
                    if (lastMessage && lastMessage.classList.contains('sent')) {
                        messagesArea.removeChild(lastMessage);
                    }
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                // Could show error message to user
            });
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

        // Attachment functionality
        function toggleAttachmentDropdown() {
            const dropdown = document.getElementById('attachmentDropdown');
            dropdown.classList.toggle('active');
        }

        function triggerFileInput() {
            // Check if session has ended
            const messageInput = document.getElementById('messageInput');
            if (messageInput && messageInput.disabled) {
                alert('Session has ended. Cannot send files.');
                toggleAttachmentDropdown();
                return;
            }
            
            document.getElementById('fileInput').click();
            toggleAttachmentDropdown();
        }

        function handleFileSelect(event) {
            // Check if session has ended first
            const messageInput = document.getElementById('messageInput');
            if (messageInput && messageInput.disabled) {
                alert('Session has ended. Cannot send files.');
                event.target.value = '';
                return;
            }
            
            const file = event.target.files[0];
            if (file && currentChatUserId) {
                // Check file size (10MB limit)
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    alert('File too large. Maximum file size is 10MB.');
                    return;
                }

                // Show loading message
                appendMessage(`📎 Uploading ${file.name}...`, 'sent', new Date().toISOString());
                
                const formData = new FormData();
                formData.append('file', file);
                formData.append('receiver_id', currentChatUserId);
                
                fetch('upload_file.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    console.log('File upload result:', result);
                    
                    // Remove the loading message
                    const messagesArea = document.getElementById('messagesArea');
                    const lastMessage = messagesArea.lastElementChild;
                    if (lastMessage && lastMessage.querySelector('.message-content').textContent.includes('Uploading')) {
                        messagesArea.removeChild(lastMessage);
                    }
                    
                    if (result.status === 'success') {
                        // Add proper file message based on file type
                        let displayMessage;
                        if (result.file_type && result.file_type.startsWith('image/')) {
                            displayMessage = `� Photo: ${result.file_name}`;
                        } else {
                            displayMessage = `📎 File: ${result.file_name}`;
                        }
                        appendMessage(displayMessage, 'sent', result.timestamp || new Date().toISOString());
                    } else {
                        alert('Failed to send file: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                    
                    // Remove the loading message
                    const messagesArea = document.getElementById('messagesArea');
                    const lastMessage = messagesArea.lastElementChild;
                    if (lastMessage && lastMessage.querySelector('.message-content').textContent.includes('Uploading')) {
                        messagesArea.removeChild(lastMessage);
                    }
                    
                    alert('Error uploading file. Please try again.');
                });
                
                // Clear the file input
                event.target.value = '';
            }
        }

        function endSession() {
            if (currentChatUserId && confirm('Are you sure you want to end this session?')) {
                fetch('end_session.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `user_id=${currentChatUserId}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        appendMessage('📝 Session ended', 'sent', new Date().toISOString());
                        
                        // Disable further messaging
                        const messageInput = document.getElementById('messageInput');
                        const sendButton = document.querySelector('.send-button');
                        const attachmentIcon = document.querySelector('.attachment-icon');
                        const sendFileBtn = document.getElementById('sendFileBtn');
                        const endSessionBtn = document.getElementById('endSessionBtn');
                        
                        if (messageInput && sendButton) {
                            messageInput.disabled = true;
                            messageInput.placeholder = 'Session has ended';
                            sendButton.disabled = true;
                        }
                        
                        // Disable attachment functionality
                        if (attachmentIcon) {
                            attachmentIcon.style.opacity = '0.5';
                            attachmentIcon.style.pointerEvents = 'none';
                            attachmentIcon.title = 'Session has ended - Cannot send files';
                        }
                        
                        // Hide file sending and end session buttons
                        if (sendFileBtn) sendFileBtn.style.display = 'none';
                        if (endSessionBtn) endSessionBtn.style.display = 'none';
                        
                        // Show rating and assessment buttons based on status
                        checkUserRatingStatus();
                        checkAssessmentStatus();
                        
                        if (result.show_rating) {
                            // Show rating modal immediately
                            showRatingModal(result.other_user_id);
                        }
                    } else {
                        alert('Failed to end session: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error ending session:', error);
                    alert('Error ending session');
                });
            }
            toggleAttachmentDropdown();
        }

        // Assessment Modal Functions
        let currentSection = 0;
        let assessmentData = {};
        let hasCompletedAssessment = false;

        const questionSets = {
            'Python': {
                multipleChoice: [
                    {
                        question: "What is the output of print(type([]))?",
                        choices: ["<class 'list'>", "<class 'dict'>", "<type 'list'>", "list"],
                        answer: "<class 'list'>"
                    },
                    {
                        question: "Which of the following is a valid variable name?",
                        choices: ["2variable", "variable_2", "variable-2", "variable 2"],
                        answer: "variable_2"
                    },
                    {
                        question: "What is the output of print(2 ** 3)?",
                        choices: ["5", "6", "8", "9"],
                        answer: "8"
                    },
                    {
                        question: "Which keyword is used to define a function in Python?",
                        choices: ["function", "def", "fun", "define"],
                        answer: "def"
                    },
                    {
                        question: "What is the output of print(len(\"Hello\"))?",
                        choices: ["4", "5", "6", "Error"],
                        answer: "5"
                    }
                ],
                debugging: [
                    {
                        question: "What is the output of the following Python code?",
                        code: "x = 7\nif x % 2 == 0:\n    print(\"Even\")\nelse:\n    print(\"Odd\")",
                        answer: "Odd"
                    },
                    {
                        question: "What is the output of the following Python code?",
                        code: "for i in range(1, 4):\n    print(i)",
                        answer: "1\n2\n3"
                    },
                    {
                        question: "What is the output of the following Python code?",
                        code: "my_list = [10, 20, 30]\nprint(my_list[1])",
                        answer: "20"
                    }
                ],
                coding: [
                    {
                        question: "Write a Python function that returns the sum of two numbers.",
                        expected: "def add(a, b):\n    return a + b"
                    },
                    {
                        question: "Write a Python function that checks if a number is even.",
                        expected: "def is_even(n):\n    return n % 2 == 0"
                    },
                    {
                        question: "Write a Python loop that prints numbers from 1 to 5.",
                        expected: "for i in range(1, 6):\n    print(i)"
                    }
                ]
            },
            'Java': {
                multipleChoice: [
                    {
                        question: "What is the correct way to declare a variable in Java?",
                        choices: ["int x;", "x int;", "declare int x;", "var x int;"],
                        answer: "int x;"
                    },
                    {
                        question: "Which of the following is a valid main method declaration in Java?",
                        choices: [
                            "public static int main(String[] args)",
                            "public void main(String args)",
                            "public static void main(String[] args)",
                            "static public void main(String args[])"
                        ],
                        answer: "public static void main(String[] args)"
                    },
                    {
                        question: "Which keyword is used to inherit a class in Java?",
                        choices: ["inherits", "extends", "implements", "instanceof"],
                        answer: "extends"
                    },
                    {
                        question: "What is the size of an int data type in Java?",
                        choices: ["4 bytes", "8 bytes", "2 bytes", "Depends on the system"],
                        answer: "4 bytes"
                    },
                    {
                        question: "Which of the following is a valid way to create an object in Java?",
                        choices: [
                            "ClassName object = new ClassName();",
                            "object = ClassName();",
                            "new ClassName object();",
                            "ClassName object();"
                        ],
                        answer: "ClassName object = new ClassName();"
                    }
                ],
                debugging: [
                    {
                        question: "What is the output of the following Java code?",
                        code: "public class Test {\n    public static void main(String[] args) {\n        System.out.println(5 + 3);\n    }\n}",
                        answer: "8"
                    },
                    {
                        question: "What is the output of the following Java code?",
                        code: "int x = 10;\nif (x > 5) {\n    System.out.println(\"Greater\");\n} else {\n    System.out.println(\"Lesser\");\n}",
                        answer: "Greater"
                    },
                    {
                        question: "What is the output of the following Java code?",
                        code: "String str = \"Hello\";\nSystem.out.println(str.length());",
                        answer: "5"
                    }
                ],
                coding: [
                    {
                        question: "Write a Java method that returns the sum of two integers.",
                        expected: "public static int add(int a, int b) {\n    return a + b;\n}"
                    },
                    {
                        question: "Write a Java method that checks if a number is positive.",
                        expected: "public static boolean isPositive(int n) {\n    return n > 0;\n}"
                    },
                    {
                        question: "Write a Java loop that prints numbers from 1 to 3.",
                        expected: "for (int i = 1; i <= 3; i++) {\n    System.out.println(i);\n}"
                    }
                ]
            },
            'C': {
                multipleChoice: [
                    {
                        question: "Which of the following is the correct way to declare a variable in C?",
                        choices: ["int x;", "x int;", "declare int x;", "var x int;"],
                        answer: "int x;"
                    },
                    {
                        question: "What is the correct syntax for a for loop in C?",
                        choices: [
                            "for (i = 0; i < 10; i++)",
                            "for i in range(10)",
                            "for (int i = 0; i < 10; i++)",
                            "for i = 0 to 10"
                        ],
                        answer: "for (int i = 0; i < 10; i++)"
                    },
                    {
                        question: "How do you declare a constant value in C?",
                        choices: ["define PI 3.14", "const float PI = 3.14;", "constant PI = 3.14;", "PI := 3.14;"],
                        answer: "const float PI = 3.14;"
                    },
                    {
                        question: "Which keyword is used to return a value from a function?",
                        choices: ["exit", "return", "break", "continue"],
                        answer: "return"
                    },
                    {
                        question: "How is a function declared in C?",
                        choices: ["function name() {}", "void name() {}", "def name() {}", "sub name() {}"],
                        answer: "void name() {}"
                    }
                ],
                debugging: [
                    {
                        question: "What will printf(\"%d\", 3 + 2 * 2); output?",
                        code: "printf(\"%d\", 3 + 2 * 2);",
                        answer: "7"
                    },
                    {
                        question: "What is the output of the following C code?",
                        code: "int x = 5;\nif (x > 3) {\n    printf(\"Yes\");\n} else {\n    printf(\"No\");\n}",
                        answer: "Yes"
                    },
                    {
                        question: "What is the output of printf(\"%c\", 'A' + 1);?",
                        code: "printf(\"%c\", 'A' + 1);",
                        answer: "B"
                    }
                ],
                coding: [
                    {
                        question: "Write a C function that returns the sum of two integers.",
                        expected: "int add(int a, int b) {\n    return a + b;\n}"
                    },
                    {
                        question: "Write a C function that checks if a number is even.",
                        expected: "int isEven(int n) {\n    return n % 2 == 0;\n}"
                    },
                    {
                        question: "Write a C loop that prints numbers from 1 to 3.",
                        expected: "for (int i = 1; i <= 3; i++) {\n    printf(\"%d\\n\", i);\n}"
                    }
                ]
            }
        };

        function takeAssessment() {
            if (!currentChatUserId) {
                alert('Please select a conversation first.');
                return;
            }

            // Get the user's skill from the database
            fetch(`get_user_skill.php?user_id=${currentChatUserId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('User skill data:', data); // Debug log
                    if (data.status === 'success' && data.skill) {
                        const userSkill = data.skill;
                        console.log('User skill:', userSkill); // Debug log
                        document.getElementById('userSkill').textContent = userSkill;
                        
                        if (questionSets[userSkill]) {
                            console.log('Found question set for skill:', userSkill); // Debug log
                            assessmentData = questionSets[userSkill];
                            currentSection = 0;
                            loadAssessmentSection();
                            document.getElementById('assessmentModal').classList.add('show');
                        } else {
                            console.log('No question set found for skill:', userSkill); // Debug log
                            console.log('Available skills:', Object.keys(questionSets)); // Debug log
                            alert('No assessment available for this skill: ' + userSkill);
                        }
                    } else {
                        alert('Could not determine user skill for assessment.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching user skill:', error);
                    alert('Error loading assessment.');
                });
            
            toggleAttachmentDropdown();
        }

        function loadAssessmentSection() {
            const sections = ['multipleChoice', 'debugging', 'coding'];
            const sectionNames = ['Multiple Choice', 'Debugging', 'Coding'];
            
            // Update progress indicators
            document.querySelectorAll('.section-progress').forEach((el, index) => {
                el.className = 'section-progress';
                if (index < currentSection) {
                    el.classList.add('completed');
                } else if (index === currentSection) {
                    el.classList.add('current');
                } else {
                    el.classList.add('pending');
                }
            });

            // Hide all sections
            document.querySelectorAll('.assessment-section').forEach(section => {
                section.style.display = 'none';
            });

            // Show current section
            const currentSectionName = sections[currentSection];
            const sectionElement = document.getElementById(currentSectionName + 'Section');
            if (sectionElement) {
                sectionElement.style.display = 'block';
                loadQuestions(currentSectionName);
            }

            // Update buttons
            const prevBtn = document.getElementById('previousSectionBtn');
            const nextBtn = document.getElementById('nextSectionBtn');
            const submitBtn = document.getElementById('submitAssessmentBtn');

            prevBtn.style.display = currentSection > 0 ? 'inline-block' : 'none';
            
            if (currentSection === sections.length - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            }
        }

        function loadQuestions(sectionType) {
            const container = document.getElementById(sectionType + 'Questions');
            container.innerHTML = '';

            const questions = assessmentData[sectionType] || [];
            console.log(`Loading ${sectionType} questions:`, questions); // Debug log
            
            questions.forEach((question, index) => {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question-container';
                
                if (sectionType === 'multipleChoice') {
                    questionDiv.innerHTML = `
                        <div class="question-text">${index + 1}. ${question.question}</div>
                        <div class="question-choices">
                            ${question.choices.map((choice, choiceIndex) => `
                                <label class="choice-option">
                                    <input type="radio" name="mc_${index}" value="${choice}">
                                    ${choice}
                                </label>
                            `).join('')}
                        </div>
                    `;
                } else if (sectionType === 'debugging') {
                    questionDiv.innerHTML = `
                        <div class="question-text">${index + 1}. ${question.question}</div>
                        ${question.code ? `<div class="code-question">${question.code}</div>` : ''}
                        <textarea class="text-answer" name="debug_${index}" placeholder="Enter your answer here..."></textarea>
                    `;
                } else if (sectionType === 'coding') {
                    questionDiv.innerHTML = `
                        <div class="question-text">${index + 1}. ${question.question}</div>
                        <textarea class="text-answer" name="code_${index}" placeholder="Write your code here..."></textarea>
                    `;
                }
                
                container.appendChild(questionDiv);
            });
        }

        function nextSection() {
            if (currentSection < 2) {
                currentSection++;
                loadAssessmentSection();
            }
        }

        function previousSection() {
            if (currentSection > 0) {
                currentSection--;
                loadAssessmentSection();
            }
        }

        function submitAssessment() {
            // Collect all answers
            const formData = new FormData(document.getElementById('assessmentForm'));
            const answers = {};
            
            for (let [key, value] of formData.entries()) {
                answers[key] = value;
            }

            // Calculate score
            let totalScore = 0;
            let maxScore = 0;

            // Score multiple choice
            if (assessmentData.multipleChoice) {
                assessmentData.multipleChoice.forEach((question, index) => {
                    maxScore++;
                    const userAnswer = answers[`mc_${index}`];
                    if (userAnswer === question.answer) {
                        totalScore++;
                    }
                });
            }

            // For debugging and coding, we'll give partial credit if answered
            ['debugging', 'coding'].forEach(sectionType => {
                if (assessmentData[sectionType]) {
                    assessmentData[sectionType].forEach((question, index) => {
                        maxScore++;
                        const userAnswer = answers[`${sectionType === 'debugging' ? 'debug' : 'code'}_${index}`];
                        if (userAnswer && userAnswer.trim().length > 0) {
                            totalScore += 0.5; // Partial credit for attempting
                        }
                    });
                }
            });

            const percentage = Math.round((totalScore / maxScore) * 100);
            
            // Save assessment result
            fetch('save_assessment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `user_id=${currentChatUserId}&score=${totalScore}&max_score=${maxScore}&percentage=${percentage}&answers=${encodeURIComponent(JSON.stringify(answers))}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    hasCompletedAssessment = true;
                    alert(`Assessment completed! Score: ${totalScore}/${maxScore} (${percentage}%)`);
                    closeAssessmentModal();
                    
                    // Update button visibility
                    checkAssessmentStatus();
                    
                    // Enable the End Session button if session is still active
                    const endSessionBtn = document.getElementById('endSessionBtn');
                    if (endSessionBtn && endSessionBtn.style.display !== 'none') {
                        // Session is still active, keep end session button visible
                    }
                } else {
                    alert('Failed to save assessment: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error saving assessment:', error);
                alert('Error saving assessment');
            });
        }

        function closeAssessmentModal() {
            document.getElementById('assessmentModal').classList.remove('show');
            currentSection = 0;
            assessmentData = {};
        }

        // Rating Modal Functions
        let ratedUserId = null;
        let ratings = { understanding: 0, knowledge: 0, listening: 0 };

        function showRatingModal(userId) {
            ratedUserId = userId;
            document.getElementById('ratingModal').classList.add('show');
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').classList.remove('show');
            ratedUserId = null;
            ratings = { understanding: 0, knowledge: 0, listening: 0 };
            
            // Reset stars
            document.querySelectorAll('.star').forEach(star => {
                star.classList.remove('active');
            });
            
            // Clear feedback
            document.getElementById('feedbackText').value = '';
        }

        // User Rating Display Functions
        function showUserRating() {
            if (!currentChatUserId) {
                alert('Please select a conversation first.');
                return;
            }

            // Fetch user rating from database
            fetch(`get_user_rating.php?user_id=${currentChatUserId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        displayUserRating(data.rating);
                    } else {
                        alert('No rating found for this user or error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching user rating:', error);
                    alert('Error loading user rating.');
                });
            
            toggleAttachmentDropdown();
        }

        function displayUserRating(rating) {
            const modal = document.getElementById('userRatingModal');
            const title = document.getElementById('ratingModalTitle');
            const content = document.getElementById('userRatingContent');
            
            // Get user name from current chat
            const chatUserName = document.querySelector('.chat-user-info h3')?.textContent || 'User';
            title.textContent = `${chatUserName}'s Rating`;
            
            if (rating && rating.avg_rating > 0) {
                const stars = '★'.repeat(Math.round(rating.avg_rating)) + '☆'.repeat(5 - Math.round(rating.avg_rating));
                content.innerHTML = `
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 2rem; color: #ffd700; margin-bottom: 15px;">
                            ${stars}
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 600; color: #2d3748; margin-bottom: 10px;">
                            ${rating.avg_rating.toFixed(1)}/5.0
                        </div>
                        <div style="color: #718096; margin-bottom: 20px;">
                            Based on ${rating.total_ratings} rating${rating.total_ratings !== 1 ? 's' : ''}
                        </div>
                        
                        <div style="display: flex; justify-content: space-around; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                            <div style="text-align: center; padding: 10px;">
                                <div style="font-weight: 600; color: #4a5568;">Understanding</div>
                                <div style="color: #667eea; font-size: 1.2rem;">${rating.understanding_avg.toFixed(1)}/5</div>
                            </div>
                            <div style="text-align: center; padding: 10px;">
                                <div style="font-weight: 600; color: #4a5568;">Knowledge Sharing</div>
                                <div style="color: #667eea; font-size: 1.2rem;">${rating.knowledge_avg.toFixed(1)}/5</div>
                            </div>
                            <div style="text-align: center; padding: 10px;">
                                <div style="font-weight: 600; color: #4a5568;">Listening</div>
                                <div style="color: #667eea; font-size: 1.2rem;">${rating.listening_avg.toFixed(1)}/5</div>
                            </div>
                        </div>
                        
                        ${rating.recent_feedback ? `
                            <div style="background: #f7fafc; padding: 15px; border-radius: 10px; border-left: 4px solid #667eea;">
                                <div style="font-weight: 600; margin-bottom: 5px; color: #4a5568;">Recent Feedback:</div>
                                <div style="font-style: italic; color: #718096;">"${rating.recent_feedback}"</div>
                            </div>
                        ` : ''}
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #718096;">
                        <i class="fas fa-star" style="font-size: 3rem; margin-bottom: 15px; color: #e2e8f0;"></i>
                        <h4 style="margin-bottom: 10px;">No Ratings Yet</h4>
                        <p>This user hasn't been rated by anyone yet.</p>
                    </div>
                `;
            }
            
            modal.classList.add('show');
        }

        function closeUserRatingModal() {
            document.getElementById('userRatingModal').classList.remove('show');
        }

        function submitRating() {
            if (!ratedUserId) {
                alert('Error: No user selected for rating');
                return;
            }

            // Check if at least one rating is given
            const hasRating = Object.values(ratings).some(rating => rating > 0);
            if (!hasRating) {
                alert('Please provide at least one rating');
                return;
            }

            const feedback = document.getElementById('feedbackText').value;

            fetch('save_rating.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rated_user_id=${ratedUserId}&understanding_rating=${ratings.understanding}&knowledge_sharing_rating=${ratings.knowledge}&listening_rating=${ratings.listening}&feedback=${encodeURIComponent(feedback)}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    alert('Thank you for your rating!');
                    closeRatingModal();
                    
                    // Update button visibility
                    checkUserRatingStatus();
                    
                    // Optionally reload the page or redirect
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert('Failed to save rating: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error saving rating:', error);
                alert('Error saving rating');
            });
        }

        // Star rating functionality
        document.querySelectorAll('.star-rating').forEach(ratingContainer => {
            const category = ratingContainer.dataset.category;
            const stars = ratingContainer.querySelectorAll('.star');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const value = parseInt(star.dataset.value);
                    ratings[category] = value;
                    
                    // Update visual state
                    stars.forEach((s, i) => {
                        if (i < value) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });
                
                star.addEventListener('mouseover', () => {
                    const value = parseInt(star.dataset.value);
                    stars.forEach((s, i) => {
                        if (i < value) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#e2e8f0';
                        }
                    });
                });
            });
            
            ratingContainer.addEventListener('mouseleave', () => {
                stars.forEach((s, i) => {
                    const currentRating = ratings[category];
                    if (i < currentRating) {
                        s.style.color = '#ffd700';
                    } else {
                        s.style.color = '#e2e8f0';
                    }
                });
            });
        });

        // Close attachment dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const attachmentIcon = document.querySelector('.attachment-icon');
            const dropdown = document.getElementById('attachmentDropdown');
            
            if (dropdown && !attachmentIcon.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Auto-select conversation on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for user_id parameter first
            const urlParams = new URLSearchParams(window.location.search);
            const targetUserId = urlParams.get('user_id');
            
            if (targetUserId) {
                const targetContact = document.querySelector(`[onclick*="${targetUserId}"]`);
                if (targetContact) {
                    targetContact.click();
                    return;
                }
            }
            
            // If no URL parameter, try to restore last conversation from localStorage
            const lastContactId = localStorage.getItem('lastContactId');
            if (lastContactId) {
                const lastContact = document.querySelector(`[onclick*="${lastContactId}"]`);
                if (lastContact) {
                    lastContact.click();
                    return;
                }
            }
            
            // If no saved conversation, automatically select the first contact
            const firstContact = document.querySelector('.contact-item');
            if (firstContact) {
                firstContact.click();
            }
        });

        // Auto-refresh messages every 3 seconds for active conversation
        setInterval(function() {
            if (currentChatUserId) {
                loadMessages(currentChatUserId);
            }
        }, 3000);

        // Check if user has already rated the other user
        function checkUserRatingStatus() {
            if (!currentChatUserId) return;
            
            fetch(`check_rating_status.php?rated_user_id=${currentChatUserId}`)
                .then(response => response.json())
                .then(data => {
                    const rateUserBtn = document.getElementById('rateUserBtn');
                    const viewUserRatingBtn = document.getElementById('viewUserRatingBtn');
                    
                    if (data.has_rated) {
                        // User has already rated - show "View User Rating"
                        if (rateUserBtn) rateUserBtn.style.display = 'none';
                        if (viewUserRatingBtn) viewUserRatingBtn.style.display = 'inline-block';
                    } else {
                        // User hasn't rated yet - show "Rate User"
                        if (rateUserBtn) rateUserBtn.style.display = 'inline-block';
                        if (viewUserRatingBtn) viewUserRatingBtn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error checking rating status:', error);
                    // Default to showing rate button on error
                    const rateUserBtn = document.getElementById('rateUserBtn');
                    const viewUserRatingBtn = document.getElementById('viewUserRatingBtn');
                    if (rateUserBtn) rateUserBtn.style.display = 'inline-block';
                    if (viewUserRatingBtn) viewUserRatingBtn.style.display = 'none';
                });
        }

        // Check if user has already taken assessment
        function checkAssessmentStatus() {
            if (!currentChatUserId) return;
            
            fetch(`check_assessment_status.php?user_id=${currentChatUserId}`)
                .then(response => response.json())
                .then(data => {
                    const takeAssessmentBtn = document.getElementById('takeAssessmentBtn');
                    const viewAssessmentBtn = document.getElementById('viewAssessmentBtn');
                    
                    if (data.has_assessment) {
                        // User has already taken assessment - show "View Assessment"
                        if (takeAssessmentBtn) takeAssessmentBtn.style.display = 'none';
                        if (viewAssessmentBtn) viewAssessmentBtn.style.display = 'inline-block';
                    } else {
                        // User hasn't taken assessment yet - show "Take Assessment"
                        if (takeAssessmentBtn) takeAssessmentBtn.style.display = 'inline-block';
                        if (viewAssessmentBtn) viewAssessmentBtn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error checking assessment status:', error);
                    // Default to showing take assessment button on error
                    const takeAssessmentBtn = document.getElementById('takeAssessmentBtn');
                    const viewAssessmentBtn = document.getElementById('viewAssessmentBtn');
                    if (takeAssessmentBtn) takeAssessmentBtn.style.display = 'inline-block';
                    if (viewAssessmentBtn) viewAssessmentBtn.style.display = 'none';
                });
        }

        // Function to rate user (show rating modal)
        function rateUser() {
            if (!currentChatUserId) {
                alert('Please select a conversation first.');
                return;
            }
            
            showRatingModal(currentChatUserId);
            toggleAttachmentDropdown();
        }

        // Function to view assessment results
        function viewAssessment() {
            if (!currentChatUserId) {
                alert('Please select a conversation first.');
                return;
            }
            
            // Fetch and display assessment results
            fetch(`get_assessment_result.php?user_id=${currentChatUserId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        displayAssessmentResult(data.assessment);
                    } else {
                        alert('No assessment found for this user.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching assessment:', error);
                    alert('Error loading assessment results.');
                });
            
            toggleAttachmentDropdown();
        }

        // Function to display assessment results in a modal
        function displayAssessmentResult(assessment) {
            const modal = document.getElementById('assessmentResultModal');
            const content = document.getElementById('assessmentResultContent');
            
            if (assessment) {
                const performanceLevel = assessment.percentage >= 80 ? 'Excellent' : 
                                       assessment.percentage >= 60 ? 'Good' : 
                                       assessment.percentage >= 40 ? 'Fair' : 'Needs Improvement';
                
                const performanceColor = assessment.percentage >= 80 ? '#48bb78' : 
                                       assessment.percentage >= 60 ? '#667eea' : 
                                       assessment.percentage >= 40 ? '#ed8936' : '#f56565';
                
                content.innerHTML = `
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 2rem; color: #667eea; margin-bottom: 15px;">
                            📊 Assessment Results
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 600; color: #2d3748; margin-bottom: 10px;">
                            Score: ${assessment.score}/${assessment.max_score}
                        </div>
                        <div style="font-size: 1.2rem; color: ${performanceColor}; margin-bottom: 20px; font-weight: 600;">
                            ${assessment.percentage}%
                        </div>
                        <div style="color: #718096; margin-bottom: 20px;">
                            Completed on: ${new Date(assessment.created_at).toLocaleDateString()}
                        </div>
                        
                        <div style="background: #f7fafc; padding: 15px; border-radius: 10px; border-left: 4px solid ${performanceColor};">
                            <div style="font-weight: 600; margin-bottom: 5px; color: #4a5568;">Performance Level:</div>
                            <div style="color: ${performanceColor}; font-size: 1.1rem; font-weight: 600;">
                                ${performanceLevel}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            modal.classList.add('show');
        }

        function closeAssessmentResultModal() {
            document.getElementById('assessmentResultModal').classList.remove('show');
        }

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
