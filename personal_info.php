<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// === Handle profile picture upload if file is uploaded ===
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_tmp  = $_FILES['profile_pic']['tmp_name'];
    $file_ext  = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($file_ext, $allowed_types)) {
        $newFileName = $user_id . "_" . time() . "_" . bin2hex(random_bytes(5)) . "." . $file_ext;
        $file_dest   = $upload_dir . $newFileName;

        if (move_uploaded_file($file_tmp, $file_dest)) {
            $pic_sql = "UPDATE users_profile SET profile_pic = ? WHERE user_id = ?";
            $pic_stmt = $conn->prepare($pic_sql);
            if ($pic_stmt) {
                $pic_stmt->bind_param("si", $file_dest, $user_id);
                $pic_stmt->execute();
                $pic_stmt->close();
            }
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "Invalid file type. Allowed: " . implode(", ", $allowed_types);
    }
}

// === If form was submitted via 'update' button ===
if (isset($_POST['update'])) {
    // Collect form data
    $last_name      = $_POST['last_name'];
    $first_name     = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $suffix         = $_POST['suffix'];
    $age            = $_POST['age'];
    $gender         = $_POST['gender'];
    $location       = $_POST['location'];
    $availability   = $_POST['availability'];
    $bio            = $_POST['bio'];

    $course         = $_POST['course'];
    $year           = $_POST['year'];
    $block          = $_POST['block'];
    $status         = $_POST['status'];

    // === Update users_profile table ===
    $profile_sql = "UPDATE users_profile 
                    SET last_name=?, first_name=?, middle_initial=?, suffix=?, age=?, gender=?, location=?, availability=?, bio=?
                    WHERE user_id=?";
    $profile_stmt = $conn->prepare($profile_sql);
    if ($profile_stmt) {
        $profile_stmt->bind_param(
            "ssssissssi",
            $last_name,
            $first_name,
            $middle_initial,
            $suffix,
            $age,
            $gender,
            $location,
            $availability,
            $bio,
            $user_id
        );
        $profile_stmt->execute();
        $profile_stmt->close();
    }

    // === Update education table ===
    $edu_sql = "UPDATE education 
                SET course=?, year=?, block=?, status=?
                WHERE user_id=?";
    $edu_stmt = $conn->prepare($edu_sql);
    if ($edu_stmt) {
        $edu_stmt->bind_param("ssssi", $course, $year, $block, $status, $user_id);
        $edu_stmt->execute();
        $edu_stmt->close();
    }

    $conn->close();

    // ✅ Redirect with localStorage clear and to dashboard.php via JS
    echo "<script>
        localStorage.clear();
        window.location.href = 'dashboard.php';
    </script>";
    exit();
}

// If no update button was clicked
$conn->close();
header("Location: dashboard.php");
exit();
?>
