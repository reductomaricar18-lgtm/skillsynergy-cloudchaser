<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['receiver_id']) && isset($_POST['message'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message_text = trim($_POST['message']);

    if ($message_text !== "") {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message_text);
        if ($stmt->execute()) {
            // Add to notifications
            $notif_msg = "New message from user {$sender_id}";
            $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, message, is_read) VALUES (?, 'message', ?, 0)");
            $stmt_notif->bind_param("is", $receiver_id, $notif_msg);
            $stmt_notif->execute();

            // ✅ Emit Socket.IO event via Node.js server using cURL
            $data = [
                'receiver_id' => $receiver_id,
                'sender_id' => $sender_id,
                'text' => $message_text
            ];

            $ch = curl_init('http://localhost:3000/emit-message'); // Adjust if your Node server is hosted elsewhere
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Message insert failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Empty message']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
