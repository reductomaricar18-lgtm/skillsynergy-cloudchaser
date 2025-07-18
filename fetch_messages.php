<?php
session_start();

// Disable error display to prevent HTML in JSON response
// error_reporting(0);
// ini_set('display_errors', 0);

header('Content-Type: application/json');

function respond($data) {
    echo json_encode($data);
    exit();
}

if (!isset($_SESSION['email'])) {
    respond(['status' => 'error', 'message' => 'Not authenticated']);
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    respond(['status' => 'error', 'message' => 'Database connection failed']);
}

// Get current user ID
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
if (!$stmt) respond(['status' => 'error', 'message' => 'Database error']);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    respond(['status' => 'error', 'message' => 'User not found']);
}

$other_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : null;
$last_message_id = isset($_GET['last_message_id']) ? intval($_GET['last_message_id']) : 0;

if (!$other_id) {
    respond(['status' => 'error', 'message' => 'Receiver ID required']);
}

// Fetch messages between users (with optional last_message_id for real-time)
$query = "
    SELECT m.message_id, m.sender_id, m.receiver_id, m.message_text, m.message_type, 
           m.file_name, m.file_path, m.file_size, m.file_type, m.sent_at, m.is_end_session,
           CASE WHEN m.sender_id = ? THEN 'sent' ELSE 'received' END as direction
    FROM messages m 
    WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)) 
      AND m.is_deleted = FALSE
      AND m.message_id > ?
    ORDER BY m.sent_at ASC
";

// Also check if conversation is locked (has ended session)
$session_check_query = "
    SELECT COUNT(*) as session_ended
    FROM messages m 
    WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)) 
      AND m.is_end_session = TRUE
";

$session_stmt = $conn->prepare($session_check_query);
if (!$session_stmt) respond(['status' => 'error', 'message' => 'Database error']);
$session_stmt->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
$session_stmt->execute();
$session_result = $session_stmt->get_result();
$session_row = $session_result->fetch_assoc();
$is_session_ended = $session_row && $session_row['session_ended'] > 0;
$session_stmt->close();

$stmt = $conn->prepare($query);
if (!$stmt) respond(['status' => 'error', 'message' => 'Database error']);
$stmt->bind_param("iiiiii", $user_id, $user_id, $other_id, $other_id, $user_id, $last_message_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];

 while ($row = $result->fetch_assoc()) {
    $message = [
        'message_id' => $row['message_id'],
        'sender_id' => $row['sender_id'],
        'receiver_id' => $row['receiver_id'],
        'message_text' => $row['message_text'],
        'message_type' => $row['message_type'],
        'direction' => $row['direction'],
        'sent_at' => $row['sent_at'],
         'formatted_time' => date('H:i', strtotime($row['sent_at']))
    ];
    
    // Add file information if it's a file/image message
    if ($row['message_type'] !== 'text') {
        $message['file_name'] = $row['file_name'];
        $message['file_path'] = $row['file_path'];
        $message['file_size'] = $row['file_size'];
        $message['file_type'] = $row['file_type'];
        $message['file_size_formatted'] = formatFileSize($row['file_size']);
    }
    
    $messages[] = $message;
 }

// Mark messages as read
if (!empty($messages)) {
    $read_stmt = $conn->prepare("
        UPDATE messages 
        SET is_read = TRUE 
        WHERE receiver_id = ? AND sender_id = ? AND is_read = FALSE
    ");
    if ($read_stmt) {
        $read_stmt->bind_param("ii", $user_id, $other_id);
        $read_stmt->execute();
        $read_stmt->close();
    }
}

// Fetch thank_you_sent_at and accepted status from sessions table
$thank_you_sent_at = null;
$accepted = false;
$session_info_stmt = $conn->prepare("
    SELECT thank_you_sent_at
    FROM sessions
    WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)
    LIMIT 1
");
if ($session_info_stmt) {
    $session_info_stmt->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
    $session_info_stmt->execute();
    $session_info_stmt->bind_result($thank_you_sent_at);
    $session_info_stmt->fetch();
    $session_info_stmt->close();
}

$stmt->close();
$conn->close();

respond([
    'status' => 'success', 
    'messages' => $messages,
    'total_count' => count($messages),
    'is_session_ended' => $is_session_ended,
    'thank_you_sent_at' => $thank_you_sent_at,
    'accepted' => $accepted
]);

function formatFileSize($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}
?> 