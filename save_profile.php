<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // PERSONAL INFO (only save if coming from final_submit or assessment_redirect)
    if (isset($_POST['final_submit']) || isset($_POST['assessment_redirect'])) {
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $middle_initial = $_POST['middle_initial'];
        $suffix = $_POST['suffix'];
        $location = $_POST['location'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $availability = $_POST['availability'];
        $bio = $_POST['bio'];

        $stmt = $conn->prepare("INSERT INTO users_profile (user_id, last_name, first_name, middle_initial, suffix, location, gender, age, availability, bio)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE 
                                last_name=VALUES(last_name), first_name=VALUES(first_name), middle_initial=VALUES(middle_initial), suffix=VALUES(suffix),
                                location=VALUES(location), gender=VALUES(gender), age=VALUES(age), availability=VALUES(availability), bio=VALUES(bio)");
        $stmt->bind_param("issssssiss", $user_id, $last_name, $first_name, $middle_initial, $suffix, $location, $gender, $age, $availability, $bio);
        $stmt->execute();
        $stmt->close();

        // EDUCATION INFO
        $college = $_POST['college'];
        $course = $_POST['course'];
        $status = $_POST['status'];
        $year = $_POST['year'];
        $block = $_POST['block'];

        $stmt = $conn->prepare("INSERT INTO education (user_id, college, course, status, year, block)
                                VALUES (?, ?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE 
                                college=VALUES(college), course=VALUES(course), status=VALUES(status), year=VALUES(year), block=VALUES(block)");
        $stmt->bind_param("isssss", $user_id, $college, $course, $status, $year, $block);
        $stmt->execute();
        $stmt->close();
    }

    // SKILLS OFFERED (run on any POST submit)
    if (isset($_POST['category']) && isset($_POST['specific_skill'])) {
        $categories = $_POST['category'];
        $specificSkills = $_POST['specific_skill'];

        echo "<pre>";
        print_r($categories);
        print_r($specificSkills);
        echo "</pre>";

        if (count($categories) !== count($specificSkills)) {
            die("Mismatch in category and specific skill selections.");
        }

        for ($i = 0; $i < count($categories); $i++) {
            $category = trim($categories[$i]);
            $specific_skill = trim($specificSkills[$i]);

            if ($category !== '' && $specific_skill !== '') {
                $checkExist = $conn->prepare("SELECT skills_id FROM skills_offer WHERE user_id = ? AND category = ? AND specific_skill = ?");
                $checkExist->bind_param("iss", $user_id, $category, $specific_skill);
                $checkExist->execute();
                $checkExist->store_result();

                echo "Checking: $category — $specific_skill<br>";

                if ($checkExist->num_rows === 0) {
                    $stmtSkill = $conn->prepare("INSERT INTO skills_offer (user_id, category, specific_skill) VALUES (?, ?, ?)");
                    $stmtSkill->bind_param("iss", $user_id, $category, $specific_skill);
                    $stmtSkill->execute();

                    if ($stmtSkill->error) {
                        echo "Insert Error: " . $stmtSkill->error;
                    } else {
                        echo "Inserted: $category — $specific_skill<br>";
                    }

                    $stmtSkill->close();
                } else {
                    echo "Already exists: $category — $specific_skill<br>";
                }

                $checkExist->close();
            }
        }
    }


    // INITIAL ASSESSMENT SCORES (if present)
    if (!empty($_SESSION['categories']) && !empty($_SESSION['specific_skills']) && !empty($_POST['assessment_scores'])) {
        $assessmentCategories = $_SESSION['categories'];
        $assessmentSkills = $_SESSION['specific_skills'];
        $assessmentScores = $_POST['assessment_scores'];

        if (count($assessmentCategories) == count($assessmentSkills) && count($assessmentCategories) == count($assessmentScores)) {
            for ($i = 0; $i < count($assessmentCategories); $i++) {
                $category = $conn->real_escape_string($assessmentCategories[$i]);
                $skill = $conn->real_escape_string($assessmentSkills[$i]);
                $score = intval($assessmentScores[$i]);

                // Check if record already exists
                $checkExist = $conn->prepare("SELECT skill_id FROM initial_assessment WHERE user_id = ? AND category = ? AND specific_skill = ?");
                $checkExist->bind_param("iss", $user_id, $category, $skill);
                $checkExist->execute();
                $checkExist->store_result();

                if ($checkExist->num_rows > 0) {
                    // Update if exists
                    $stmtUpdate = $conn->prepare("UPDATE initial_assessment SET assessment_score = ? WHERE user_id = ? AND category = ? AND specific_skill = ?");
                    $stmtUpdate->bind_param("iiss", $score, $user_id, $category, $skill);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                } else {
                    // Insert if not
                    $stmtInsert = $conn->prepare("INSERT INTO initial_assessment (user_id, category, specific_skill, assessment_score) VALUES (?, ?, ?, ?)");
                    $stmtInsert->bind_param("issi", $user_id, $category, $skill, $score);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                }
                $checkExist->close();
            }
        }

        // Clear assessment session data after saving
        unset($_SESSION['categories']);
        unset($_SESSION['specific_skills']);
    }

    // Mark profile as completed (only on full profile submit)
    if (isset($_POST['final_submit']) || isset($_POST['assessment_redirect'])) {
        $updateCompleted = $conn->prepare("UPDATE users SET profile_completed = 1 WHERE user_id = ?");
        $updateCompleted->bind_param("i", $user_id);
        $updateCompleted->execute();
        $updateCompleted->close();
    }

    $conn->close();
    
    // Redirect appropriately
    if (isset($_POST['assessment_redirect'])) {
        header("Location: initial_assessment.php");
        exit();
    } elseif (isset($_POST['final_submit'])) {
        header("Location: profile_page.php");
        exit();
    } else {
        // Optional: redirect back if posted via inline form
        header("Location: profile_page.php");
        exit();
    }
}
?>