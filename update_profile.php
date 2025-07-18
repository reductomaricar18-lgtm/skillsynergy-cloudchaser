<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Personal Info
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $suffix = $_POST['suffix'];
    $location = $_POST['location'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $availability = $_POST['availability'];
    $bio = $_POST['bio'];

    // Update users_profile
    $stmtProfile = $conn->prepare("UPDATE users_profile 
        SET last_name=?, first_name=?, middle_initial=?, suffix=?, location=?, gender=?, age=?, availability=?, bio=?
        WHERE user_id=?");
    if (!$stmtProfile) {
        die("Profile Prepare Error: " . $conn->error);
    }
    $stmtProfile->bind_param("ssssssissi", $last_name, $first_name, $middle_initial, $suffix, $location, $gender, $age, $availability, $bio, $user_id);

    if (!$stmtProfile->execute()) {
        die("Profile Update Error: " . $stmtProfile->error);
    }

    // Get current profile_id
    $result = $conn->query("SELECT profile_id FROM users_profile WHERE user_id = $user_id LIMIT 1");
    $row = $result->fetch_assoc();
    $profile_id = $row['profile_id'];

    $stmtProfile->close();

    // Update education
    $course = $_POST['course'];
    $status = $_POST['status'];
    $year = $_POST['year'];
    $block = $_POST['block'];

    $stmtEdu = $conn->prepare("UPDATE education 
        SET course=?, status=?, year=?, block=?
        WHERE user_id=? AND profile_id=?");
    if (!$stmtEdu) {
        die("Education Prepare Error: " . $conn->error);
    }
    $stmtEdu->bind_param("ssssii", $course, $status, $year, $block, $user_id, $profile_id);

    if (!$stmtEdu->execute()) {
        die("Education Update Error: " . $stmtEdu->error);
    }
    $stmtEdu->close();

    // Update skills_offer
    // First delete existing skills for this profile
    $conn->query("DELETE FROM skills_offer WHERE user_id=$user_id AND profile_id=$profile_id");

    // Then insert new ones
    if (!empty($_POST['category'])) {
        foreach ($_POST['category'] as $category) {
            $stmtSkill = $conn->prepare("INSERT INTO skills_offer (user_id, profile_id, category) VALUES (?, ?, ?)");
            if (!$stmtSkill) {
                die("Skills Prepare Error: " . $conn->error);
            }
            $stmtSkill->bind_param("iis", $user_id, $profile_id, $category);
            if (!$stmtSkill->execute()) {
                die("Skills Insert Error: " . $stmtSkill->error);
            }
            $stmtSkill->close();
        }
    }

    // Success
    echo "<script>alert('Profile successfully updated!'); window.location.href='profile.php';</script>";
    exit();

}
?>
