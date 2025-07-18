<?php
session_start();

// Enable error logging but disable display to prevent HTML in JSON response
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');

try {
    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
        exit();
    }

    $conn = new mysqli('localhost', 'root', '', 'sia1');
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit();
    }

    // Get current user ID
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database prepare failed']);
        exit();
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($current_user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$current_user_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }

    // Check if file and receiver_id are provided
    if (!isset($_FILES['file']) || !isset($_POST['receiver_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing file or receiver ID']);
        exit();
    }

    $receiver_id = intval($_POST['receiver_id']);
    $uploaded_file = $_FILES['file'];

    // Check for upload errors
    if ($uploaded_file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $uploaded_file['error']]);
        exit();
    }

    // Validate file - expanded list of allowed types
    $allowed_types = [
        // Images
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
        // Documents
        'application/pdf', 
        'application/msword', 
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'text/csv',
        'application/zip',
        'application/rar',
        'application/x-zip-compressed',
        'application/x-rar-compressed'
    ];
    
    $max_size = 10 * 1024 * 1024; // 10MB

    if (!in_array($uploaded_file['type'], $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'File type not allowed: ' . $uploaded_file['type']]);
        exit();
    }

    if ($uploaded_file['size'] > $max_size) {
        echo json_encode(['status' => 'error', 'message' => 'File too large (max 10MB)']);
        exit();
    }

    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/messages/';
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
            exit();
        }
    }

    // Generate unique filename
    $file_extension = pathinfo($uploaded_file['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_path = $upload_dir . $unique_filename;

    // Move uploaded file
    if (move_uploaded_file($uploaded_file['tmp_name'], $target_path)) {
        
        // Determine message type
        $message_type = strpos($uploaded_file['type'], 'image/') === 0 ? 'image' : 'file';
        $message_text = 'Sent a ' . ($message_type === 'image' ? 'photo' : 'file');
        
        // Insert message with file using the same structure as send_message.php
        $stmt = $conn->prepare("
            INSERT INTO messages (sender_id, receiver_id, message_text, message_type, file_name, file_path, file_size, file_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database prepare failed: ' . $conn->error]);
            exit();
        }
        
        $stmt->bind_param("iissssis", $current_user_id, $receiver_id, $message_text, $message_type, $uploaded_file['name'], $target_path, $uploaded_file['size'], $uploaded_file['type']);
        
        if ($stmt->execute()) {
            $message_id = $conn->insert_id;
            
            // Update or create conversation (optional - only if table exists)
            $conv_check = $conn->query("SHOW TABLES LIKE 'conversations'");
            if ($conv_check->num_rows > 0) {
                $conv_stmt = $conn->prepare("
                    INSERT INTO conversations (user1_id, user2_id, last_message_id) 
                    VALUES (LEAST(?, ?), GREATEST(?, ?), ?)
                    ON DUPLICATE KEY UPDATE last_message_id = ?, last_activity = CURRENT_TIMESTAMP
                ");
                if ($conv_stmt) {
                    $conv_stmt->bind_param("iiiiii", $current_user_id, $receiver_id, $current_user_id, $receiver_id, $message_id, $message_id);
                    $conv_stmt->execute();
                    $conv_stmt->close();
                }
            }
            
            echo json_encode([
                'status' => 'success', 
                'message_id' => $message_id,
                'file_path' => $target_path,
                'file_name' => $uploaded_file['name'],
                'file_type' => $uploaded_file['type'],
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save message: ' . $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
    }

    $conn->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
