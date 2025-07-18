<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli('localhost', 'root', '', 'sia1');
$conn->set_charset("utf8mb4");

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (empty($user_id)) {
    die("Invalid session. No user ID found for this email.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name       = $conn->real_escape_string($_POST['first_name'] ?? '');
    $last_name        = $conn->real_escape_string($_POST['last_name'] ?? '');
    $middle_initial   = $conn->real_escape_string($_POST['middle_initial'] ?? '');
    $suffix           = $conn->real_escape_string($_POST['suffix'] ?? '');
    $location         = $conn->real_escape_string($_POST['location'] ?? '');
    $gender           = $conn->real_escape_string($_POST['gender'] ?? '');
    $age              = (int)($_POST['age'] ?? 0);
    $availability     = $conn->real_escape_string($_POST['availability'] ?? '');
    $bio              = $conn->real_escape_string($_POST['bio'] ?? '');

    $college = $conn->real_escape_string($_POST['college'] ?? '');
    $course  = $conn->real_escape_string($_POST['course'] ?? '');
    $status  = $conn->real_escape_string($_POST['status'] ?? '');
    $year    = $conn->real_escape_string($_POST['year'] ?? '');
    $block   = $conn->real_escape_string($_POST['block'] ?? '');

    $result = $conn->query("SELECT 1 FROM users_profile WHERE user_id = $user_id LIMIT 1");
    if ($result->num_rows == 0) {
        $conn->query("INSERT INTO users_profile (user_id, first_name, last_name, middle_initial, suffix, location, gender, age, availability, bio)
                      VALUES ($user_id, '$first_name', '$last_name', '$middle_initial', '$suffix', '$location', '$gender', $age, '$availability', '$bio')");
    } else {
        $conn->query("UPDATE users_profile 
                      SET first_name='$first_name',
                          last_name='$last_name',
                          middle_initial='$middle_initial',
                          suffix='$suffix',
                          location='$location',
                          gender='$gender',
                          age=$age,
                          availability='$availability',
                          bio='$bio'
                      WHERE user_id=$user_id");
    }

    $result = $conn->query("SELECT 1 FROM education WHERE user_id = $user_id LIMIT 1");
    if ($result->num_rows == 0) {
        $conn->query("INSERT INTO education (user_id, college, course, status, year, block)
                      VALUES ($user_id, '$college', '$course', '$status', '$year', '$block')");
    } else {
        $conn->query("UPDATE education 
                      SET college='$college',
                          course='$course',
                          status='$status',
                          year='$year',
                          block='$block'
                      WHERE user_id=$user_id");
    }

    if (!empty($_POST['category'])) {
        $categories = $_POST['category'];
        $specific_skills = $_POST['specific_skill'];
        $database_types = $_POST['database_type_skills'] ?? [];
        $specific_database_skills = $_POST['specific_database_skill_offer'] ?? [];

        $conn->query("DELETE FROM initial_assessment WHERE user_id = $user_id");

        foreach ($categories as $index => $category) {
            $category = $conn->real_escape_string($category);
            $skill    = $conn->real_escape_string($specific_skills[$index] ?? '');
            $db_type  = $conn->real_escape_string($database_types[$index] ?? '');
            $db_skill = $conn->real_escape_string($specific_database_skills[$index] ?? '');

            if (!empty($category) && !empty($skill)) {
                $skill_result = $conn->query("SELECT skills_id FROM skills_offer WHERE user_id = $user_id AND specific_skill = '$skill' LIMIT 1");
                if ($skill_result->num_rows > 0) {
                    $skills_id = $skill_result->fetch_assoc()['skills_id'];
                } else {
                    $conn->query("INSERT INTO skills_offer (user_id, category, specific_skill)
                                  VALUES ($user_id, '$category', '$skill')");
                    $skills_id = $conn->insert_id;
                }

                $conn->query("INSERT INTO initial_assessment (user_id, skills_id, category, skill, score, total_items, proficiency)
                              VALUES ($user_id, $skills_id, '$category', '$skill', , , 'Beginner')");
            }
        }
    }

    if (!empty($_POST['want_to_learn'])) {
        $want_to_learn      = $conn->real_escape_string($_POST['want_to_learn']);
        $proficiency_level  = $conn->real_escape_string($_POST['proficiency_level']);
        $db_type            = $conn->real_escape_string($_POST['database_type'] ?? '');
        $db_skill           = $conn->real_escape_string($_POST['specific_database_skill'] ?? '');

        $goal_result = $conn->query("SELECT * FROM learning_goals WHERE user_id = $user_id LIMIT 1");
        if ($goal_result->num_rows > 0) {
            $conn->query("UPDATE learning_goals 
                          SET want_to_learn='$want_to_learn',
                              database_type=" . (!empty($db_type) ? "'$db_type'" : "NULL") . ",
                              specific_database_skill=" . (!empty($db_skill) ? "'$db_skill'" : "NULL") . ",
                              proficiency_level='$proficiency_level'
                          WHERE user_id=$user_id");
        } else {
            $conn->query("INSERT INTO learning_goals (user_id, want_to_learn, database_type, specific_database_skill, proficiency_level)
                          VALUES ($user_id, '$want_to_learn', " . (!empty($db_type) ? "'$db_type'" : "NULL") . ", " . (!empty($db_skill) ? "'$db_skill'" : "NULL") . ", '$proficiency_level')");
        }
    }

    if (isset($_POST['final_submit']) && !isset($_POST['assessment_redirect'])) {
        $conn->query("UPDATE users SET profile_completed = 1 WHERE user_id = $user_id");
        header("Location: dashboard.php");
        exit();
    }

    if (isset($_POST['assessment_redirect'])) {
        header("Location: initial_assessment.php");
        exit();
    }
}

$skills_offered = [];
$result = $conn->query("SELECT category, skill FROM initial_assessment WHERE user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $skills_offered[] = $row;
}

$assessments = [];
$result = $conn->query("SELECT category, skill, proficiency FROM initial_assessment WHERE user_id = $user_id");
while ($row = $result->fetch_assoc()) {
    $assessments[$row['category']][$row['skill']] = $row['proficiency'];
}

$profile = [];
$result = $conn->query("SELECT * FROM users_profile WHERE user_id = $user_id");
if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
}

$education = [];
$result = $conn->query("SELECT * FROM education WHERE user_id = $user_id");
if ($result->num_rows > 0) {
    $education = $result->fetch_assoc();
}

$result = $conn->query("SELECT * FROM learning_goals WHERE user_id = $user_id LIMIT 1");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profile['want_to_learn'] = $row['want_to_learn'];
    $profile['proficiency_level'] = $row['proficiency_level'];
    $profile['database_type'] = $row['database_type'];
    $profile['specific_database_skill'] = $row['specific_database_skill'];
}

$philippines_places = [
    "Manila", "Quezon City", "Caloocan", "Pasig", "Makati", "Taguig", "Pasay", "Parañaque",
    "Las Piñas", "San Juan", "Mandaluyong", "Marikina", "Muntinlupa", "Navotas", "Valenzuela"
];

$needs_reset = false;
$profile_check = $conn->query("SELECT 1 FROM users_profile WHERE user_id = $user_id LIMIT 1");
if ($profile_check->num_rows === 0) {
    $needs_reset = true;
}

// Fetch all categories and their skills from skills_offer
$categories_skills = [];
$result = $conn->query("SELECT category, specific_skill FROM skills_offer WHERE category != '' AND specific_skill != '' ORDER BY category, specific_skill");
while ($row = $result->fetch_assoc()) {
    $cat = $row['category'];
    if (!isset($categories_skills[$cat])) {
        $categories_skills[$cat] = [];
    }
    if (!in_array($row['specific_skill'], $categories_skills[$cat])) {
        $categories_skills[$cat][] = $row['specific_skill'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkillSynergy Profile Set-up</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background: url('S9.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .container {
      max-width: 900px;
      margin: 20px 0 20px auto;
      display: flex;
      align-items: flex-start;
      justify-content: flex-end;
      padding-right: 35px;
      padding-top: 70px;
      margin-top: -50px;
      margin-bottom: 40px;
      min-height: 100vh;
    }

    .form-section {
      background: rgba(255, 243, 243, 0.35);
      border-radius: 18px;
      min-height: 850px;
      padding: 20px;
      width: 830px;
      box-shadow: 0 3px 14px rgba(0, 0, 0, 0.25);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      overflow-y: auto;
      max-height: 90vh;
    }

    .card {
      background: rgba(255, 243, 243, 0.35);
      border-radius: 18px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.18);
    }

    .card h2 {
      font-size: 22px;
      margin-bottom: 14px;
      border-bottom: 2px solid #007acc;
      display: inline-block;
      padding-bottom: 4px;
    }

    .card h2 span {
      color: #007acc;
      font-size: 26px;
    }

    .form-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 12px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      flex: 1 1 130px;
      padding: 8px 14px;
      border-radius: 18px;
      border: 1px solid #ccc;
      font-size: 14px;
      color: #666;
    }

    .form-group select option:first-child {
      color: #999;
    }

    .form-group textarea {
      flex: 1 1 100%;
      resize: none;
      height: 60px;
      border-radius: 15px;
    }

    .inline-group {
      display: flex;
      gap: 10px;
      flex: 1 1 130px;
    }

    .inline-group input {
      padding: 8px 14px;
      border-radius: 18px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .submit-btn, .skills-btn, .add-btn {
      border: none;
      border-radius: 25px;
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .submit-btn {
      background-color: #28a745;
      color: #fff;
      padding: 12px 40px;
      display: block;
      margin: 20px auto 0;
      font-weight: 600;
      font-size: 16px;
      min-width: 200px;
    }

    .submit-btn:hover {
      background-color: #218838;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .submit-btn:disabled {
      background-color: #bbb;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .skills-btn {
      padding: 8px 16px;
      background-color: #28a745;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      text-align: center;
      transition: background 0.3s ease;
    }

    .skills-btn:hover {
      background-color: #005f9e;
    }

    .fixed-submit-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 20px;
    }

    .fixed-submit-btn #form-error-message {
      color: #dc3545;
      font-weight: bold;
      margin-top: 10px;
      text-align: center;
    }

    .add-btn:hover {
      background-color: #3e8e41;
    }

    .age-select {
      flex: 1 1 130px;
      padding: 8px 14px;
      border-radius: 18px;
      border: 1px solid #ccc;
      font-size: 14px;
      color: #666;
    }

    .add-btn {
      width: 40px;
      height: 40px;
      background-color: #bdc3c7;
      color: #2c3e50;
      border: none;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 12px;
    }

    .add-btn:hover {
      background-color: #95a5a6;
    }

    .add-btn:active {
      background-color: #7f8c8d;
      transform: scale(0.95);
    }

    .add-btn:disabled {
      background-color: #ecf0f1;
      color: #bdc3c7;
      cursor: not-allowed;
    }

    .skills-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 8px;
      position: relative;
    }

    .bio-textarea {
      flex: 1 1 320px;
      padding: 8px 14px;
      border-radius: 15px;
      border: 1px solid #ccc;
      font-size: 14px;
      color: #666;
      height: 60px;
      resize: none;
    }

    .bio-textarea:valid,
    .form-group input {
      color: #000;
    }

    select {
      color: #999;
    }

    /* Turn select text black after selecting a valid option */
    select:valid {
      color: #000;
    }

    /* Highlight required fields that haven't been filled */
    input:required:invalid,
    select:required:invalid,
    textarea:required:invalid {
      border-color: #ffc107;
      box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    /* Valid field styling */
    input:required:valid,
    select:required:valid,
    textarea:required:valid {
      border-color: #28a745;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    button.removeSkill {
      margin-left: 10px;
      color: white;
      background: red;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 5px;
    }

    .assessment-button-container span {
      display: inline-block;
      margin-top: 10px;
    }

    #main-assessment-btn {
      display: none;
      justify-content: center;
      align-items: center;
      margin-top: 20px;
      text-align: center;
    }

    #main-assessment-btn .badge {
      font-size: 14px;
      padding: 8px 16px;
    }

    #main-assessment-btn button {
      padding: 8px 16px;
    }

    /* Proficiency badge style */
    .proficiency-label {
      display: inline-block;
      padding: 8px 16px;
      background-color: #007bff;
      color: #fff;
      font-weight: 600;
      border-radius: 8px;
      font-size: 14px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    /* Badge hover effect */
    .proficiency-label:hover {
      background-color: #0056b3;
      cursor: default;
    }

    .assessment-button-container {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .remove-btn {
      background: transparent;
      border: none;
      color: #dc3545;
      font-size: 20px;
      cursor: pointer;
      padding: 4px 8px;
      transition: color 0.2s ease;
    }

    .remove-btn:hover {
      color: #a71d2a;
    }

    /* Proficiency result badge when assessment done */
    .badge {
      display: inline-block;
      padding: 6px 14px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 50px;
      color: #fff;
      background-color: #007bff;
    }

    /* Result badge specific style */
    .badge-primary {
      background-color: #007bff;
    }

    /* Disable select appearance */
    .skills-offer select:disabled {
      background-color: #e9ecef;
      cursor: not-allowed;
      opacity: 0.9;
    }

    /* Smooth fade-in animation for adding skill set */
    .skills-offer {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
      opacity: 0;
      transform: translateY(10px);
      animation: fadeIn 0.4s ease forwards;
    }

    /* Database subcategory styling */
    .database-subcategory-skills select,
    .specific-database-skills-offer select {
      flex: 1 1 130px;
      padding: 8px 14px;
      border-radius: 18px;
      border: 1px solid #ccc;
      font-size: 14px;
      color: #666;
      height: 42px;
      min-width: 130px;
      background-color: #fff;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
      margin-bottom: 0;
    }

    .database-subcategory-skills select:focus,
    .specific-database-skills-offer select:focus,
    #database-subcategory select:focus,
    #specific-database-skills select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
      outline: none;
    }

    /* Make selects black on valid */
    .database-subcategory-skills select:valid,
    .specific-database-skills-offer select:valid {
      color: #000;
    }

    /* Default light gray placeholder color */
    .database-subcategory-skills select,
    .specific-database-skills-offer select {
      color: #999;
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .skill-select {
      flex: 1 1 130px;
      padding: 8px 14px;
      border-radius: 18px;
      border: 1px solid #ccc;
      font-size: 14px;
      color: #666;
      height: 42px;
      min-width: 130px;
      background-color: #fff;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .skill-select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
      outline: none;
    }

    .skill-select option:first-child {
      color: #999;
    }

    .skill-select:valid {
      color: #000;
    }

    .database-assessment-wrapper {
      display: flex;
      justify-content: center;
      margin-top: 10px;
      gap: 8px;
      margin-left: 280px;
    }

    .form-group-inline {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
    }

    .form-group-inline label {
      flex: 0 0 180px;
      font-weight: 500;
    }

    .form-control {
      flex: 1;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

<div class="container">
  <form id="profile-setup-form" action="profile_setup.php" method="POST" class="form-section">
    <div class="card">
      <h2><span>P</span>ersonal</h2>
      <div class="form-group">
        <input type="text" name="last_name" placeholder="Last Name" required value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>">
        <input type="text" name="first_name" placeholder="First Name" required value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>">
        <div class="inline-group">
          <input type="text" name="middle_initial" placeholder="M.I" value="<?= htmlspecialchars($profile['middle_initial'] ?? '') ?>">
          <input type="text" name="suffix" placeholder="Suffix" value="<?= htmlspecialchars($profile['suffix'] ?? '') ?>">
        </div>
      </div>
  
      <div class="form-group">
        <select name="location" required>
          <option disabled <?= empty($profile['location']) ? 'selected' : '' ?> value="">Select Location</option>
          <?php foreach ($philippines_places as $place): ?>
            <option value="<?= $place ?>" <?= ($profile['location'] ?? '') === $place ? 'selected' : '' ?>>
              <?= $place ?>
            </option>
          <?php endforeach; ?>
        </select>
        <select name="gender" required>
          <option disabled <?= empty($profile['gender']) ? 'selected' : '' ?> value="">Gender</option>
          <option value="Male" <?= ($profile['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= ($profile['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Prefer not to say" <?= ($profile['gender'] ?? '') === 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
        </select>
          <input type="number" name="age" placeholder="Age" required value="<?= htmlspecialchars($profile['age'] ?? '') ?>">
      </div>
      <div class="form-group">
        <select name="availability" required>
          <option disabled <?= empty($profile['availability']) ? 'selected' : '' ?> value="">Availability</option>
          <option value="Weekdays" <?= ($profile['availability'] ?? '') === 'Weekdays' ? 'selected' : '' ?>>Weekdays</option>
          <option value="weekends" <?= ($profile['availability'] ?? '') === 'weekends' ? 'selected' : '' ?>>Weekends</option>
        </select>
          <textarea name="bio" class="bio-textarea" placeholder="Write something about yourself and your skills..." style="flex: 1 1 320px; height: 60px; resize: none; border-radius: 15px;" required><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
      </div><br>
    </div>

    <div class="card">
      <h2><span>E</span>ducation</h2>
      <div class="form-group">
        <input type="email" name="plm_email" value="<?php echo $email ?>" readonly required>
        <input type="text" name="college" value="<?= htmlspecialchars($education['college'] ?? 'College of Information System & Technology Management') ?>" readonly required>
      </div>
      <div class="form-group">
        <select name="course" class="course-select" required>
          <option disabled <?= empty($profile['course']) ? 'selected' : '' ?> value="">Course</option>
          <option value="Bachelor of Science in Information Technology" <?= ($education['course'] ?? '') === 'Bachelor of Science in Information Technology' ? 'selected' : '' ?>>Bachelor of Science in Information Technology</option>
          <option value="Bachelor of Science in Computer Science" <?= ($education['course'] ?? '') === 'Bachelor of Science in Computer Science' ? 'selected' : '' ?>>Bachelor of Science in Computer Science</option>
        </select>
        <select name="status" required>
            <option disabled <?= empty($profile['status']) ? 'selected' : '' ?> value="">Status</option>
            <option value="Regular" <?= ($education['status'] ?? '') === 'Regular' ? 'selected' : '' ?>>Regular</option>
            <option value="Irregular" <?= ($education['status'] ?? '') === 'Irregular' ? 'selected' : '' ?>>Irregular</option> 
        </select>
        <select name="year" required>
          <option disabled <?= empty($profile['year']) ? 'selected' : '' ?> value="">Year</option>
          <?php
            $years = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', '6th Year'];
            foreach ($years as $y) {
              $selected = ($education['year'] ?? '') === $y ? 'selected' : '';
              echo "<option value=\"$y\" $selected>$y</option>";
            }
          ?>
        </select>

        <select name="block" required>
          <option disabled <?= empty($profile['block']) ? 'selected' : '' ?> value="">Block</option>
          <?php
            for ($i = 1; $i <= 6; $i++) {
              $selected = ($education['block'] ?? '') == $i ? 'selected' : '';
              echo "<option value=\"$i\" $selected>$i</option>";
            }
          ?>
        </select>
      </div>
    </div><br>

    <div class="card" id="skills-offer-card">
      <h2><span>S</span>kills Offer</h2>

      <div id="skills-offer-container">
        <div class="form-group skills-offer" id="primary-skill-group">
          <select name="category[]" id="primary-category" onchange="handleSkillCategoryChange(this); updateAssessmentButtons();" required>
            <option disabled selected value="">Skill Category</option>
            <option value="Programming">Programming</option>
            <option value="Web Development">Web Development</option>
            <option value="Database">Database</option>
          </select>

          <select name="specific_skill[]" id="primary-skill" onchange="updateAssessmentButtons();" required>
            <option disabled selected value="">Specific Skill</option>
          </select>

          <!-- Database subcategory selection for Skills Offer -->
          <div class="database-subcategory-skills" style="display: none; margin-top: 10px;">
            <select name="database_type_skills[]" class="database-type-skills" onchange="handleSkillDatabaseTypeChange(this)">
              <option value="">Select Database Type</option>
              <option value="relational">Relational Databases</option>
              <option value="non-relational">Non-Relational Databases</option>
            </select>
          </div>
          
          <!-- Specific database skills for Skills Offer -->
          <div class="specific-database-skills-offer" style="display: none; margin-top: 10px;">
            <select name="specific_database_skill_offer[]" class="specific-database-skill-offer">
              <option value="">Select Specific Skill</option>
            </select>
          </div>

          <!-- Inline individual assessment button area -->
          <div class="assessment-button-container"></div>
        </div>
      </div>

      <div id="main-assessment-btn">
        <button type="button" id="assessment-btn" class="skills-btn" disabled>Take Initial Assessment</button>
      </div>

      <div id="add-skill-btn-container">
        <button type="button" id="add-skill-btn" class="add-btn" onclick="addSkillSet()">+</button>
      </div>
    </div>

  <div class="card">
    <h2><span>L</span>earning Goals</h2>

    <!-- What do you want to learn -->
    <div class="form-group-inline">
      <label for="want_to_learn_select">What do you want to learn?</label>
      <select name="want_to_learn" id="want_to_learn_select" class="form-control" onchange="handleLearningGoalChange()" required>
        <option disabled <?= empty($profile['want_to_learn']) ? 'selected' : '' ?> value="">Select Skill</option>
        <?php
        foreach ($categories_skills as $cat => $skills) {
            foreach ($skills as $skill) {
                $selected = ($profile['want_to_learn'] ?? '') === $skill ? 'selected' : '';
                echo "<option value=\"$skill\" $selected>$skill</option>";
            }
        }
        ?>
      </select>
    </div>

    <!-- If Database is selected -->
    <div class="form-group-inline" id="database-subcategory" style="display: none;">
      <label for="database_type_select">Database Type</label>
      <select name="database_type" id="database_type_select" class="form-control" onchange="handleDatabaseTypeChange()">
        <option value="">Select Type</option>
        <option value="relational" <?= (isset($profile['database_type']) && $profile['database_type'] === 'relational') ? 'selected' : '' ?>>Relational</option>
        <option value="non-relational" <?= (isset($profile['database_type']) && $profile['database_type'] === 'non-relational') ? 'selected' : '' ?>>Non-Relational</option>
      </select>
    </div>

    <!-- Specific Database Skills -->
    <div class="form-group-inline" id="specific-database-skills" style="display: none;">
      <label for="specific_database_skill_select">Specific Skill</label>
      <select name="specific_database_skill" id="specific_database_skill_select" class="form-control">
        <option value="">Select Specific Skill</option>
      </select>
    </div>

    <!-- Proficiency Level -->
    <div class="form-group-inline">
      <label for="proficiency_level_select">Proficiency Level</label>
      <select name="proficiency_level" id="proficiency_level_select" class="form-control" required>
        <option value="">Select Level</option>
        <?php
        $levels = ["Beginner", "Intermediate", "Advanced"];
        foreach ($levels as $level) {
            $selected = ($profile['proficiency_level'] ?? '') === $level ? 'selected' : '';
            echo "<option value=\"$level\" $selected>$level</option>";
        }
        ?>
      </select>
    </div>
  </div>

<div class="fixed-submit-btn">
  <button id="submit-btn" type="submit" name="final_submit" class="submit-btn">Submit Profile</button>
  <div id="form-error-message" style="color: #dc3545; font-weight: bold; margin-top: 10px; text-align: center;"></div>
</div>

<script>
  const currentUserID = <?= json_encode($_SESSION['user_id']) ?>;

// Remove old data for this user if ?success=1 in URL
  if (window.location.search.includes("success=1")) {
    for (let key in localStorage) {
      if (key.startsWith(currentUserID + "_")) {
        localStorage.removeItem(key);
      }
    }
  }

  // Dynamically generated from PHP
  const skillsByCategory = <?= json_encode($categories_skills) ?>;

  const assessmentResults = <?= json_encode($assessments) ?>;
  const skillsOffered = <?= json_encode($skills_offered) ?>;

  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profile-setup-form');
    const errorContainer = document.getElementById('form-error-message');
    const skillsContainer = document.getElementById('skills-offer-container');
    const submitBtn = document.getElementById('submit-btn');

    // Load stored form data per user
    form.querySelectorAll('input, select, textarea').forEach(input => {
      const value = localStorage.getItem(currentUserID + "_" + input.name);
      if (value !== null) input.value = value;
      input.addEventListener('change', () => {
        localStorage.setItem(currentUserID + "_" + input.name, input.value);
        validateFormRealTime();
      });
    });

    const savedSkills = JSON.parse(localStorage.getItem(currentUserID + '_skills-offer-data') || "[]");
    skillsContainer.innerHTML = "";
    if (savedSkills.length) {
      savedSkills.forEach(({ category, skill }, index) => addSkillSet(category, skill, index === 0));
    } else {
      addSkillSet("", "", true);
    }

    validateFormRealTime();

    form.addEventListener('submit', (e) => {
      errorContainer.textContent = "";

      if (e.submitter && e.submitter.name === 'final_submit') {
        const requiredFields = form.querySelectorAll('input[required]:not([disabled]), select[required]:not([disabled]), textarea[required]:not([disabled])');
        let missingFields = [];
        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            missingFields.push(field.name || field.placeholder || 'Unknown field');
          }
        });
        if (missingFields.length > 0) {
          e.preventDefault();
          errorContainer.textContent = "⚠️ Please fill in all required fields: " + missingFields.join(', ');
          return;
        }
        const wantToLearn = form.querySelector('select[name="want_to_learn"]').value;
        if (!wantToLearn) {
          e.preventDefault();
          errorContainer.textContent = "⚠️ Please select what you want to learn.";
          return;
        }
        return;
      }

      const skillGroups = document.querySelectorAll('.skills-offer');
      let missingSkillSelection = false;

      skillGroups.forEach(group => {
        const category = group.querySelector('select[name="category[]"]').value;
        let skill = group.querySelector('select[name="specific_skill[]"]').value;

        if (category === 'Database') {
          const databaseTypeSelect = group.querySelector('.database-type-skills');
          const specificDatabaseSelect = group.querySelector('.specific-database-skill-offer');
          if (!databaseTypeSelect.value || !specificDatabaseSelect.value) {
            missingSkillSelection = true;
          } else {
            skill = specificDatabaseSelect.value;
            group.querySelector('select[name="specific_skill[]"]').value = skill;
          }
        }

        if (!category || !skill) {
          missingSkillSelection = true;
        }
      });

      if (missingSkillSelection) {
        e.preventDefault();
        errorContainer.textContent = "⚠️ Please complete all skill selections before assessment.";
        return;
      }
    });
  });

// Add a skill set
function addSkillSet(selectedCategory = "", selectedSkill = "", isPrimary = false) {
  const container = document.getElementById('skills-offer-container');
  const group = document.createElement('div');
  group.className = 'form-group skills-offer';

  const categorySel = document.createElement('select');
  categorySel.name = 'category[]';
  categorySel.required = true;
  categorySel.innerHTML = `<option disabled selected value="">Skill Category</option>` +
    Object.keys(skillsByCategory).map(cat => `<option value="${cat}">${cat}</option>`).join("");
  if (selectedCategory) categorySel.value = selectedCategory;

  const skillSel = document.createElement('select');
  skillSel.name = 'specific_skill[]';
  skillSel.required = true;
  skillSel.innerHTML = `<option disabled selected value="">Specific Skill</option>`;

  const databaseSubcategory = document.createElement('div');
  databaseSubcategory.className = 'database-subcategory-skills';
  databaseSubcategory.style.display = 'none';
  databaseSubcategory.style.marginTop = '10px';

  const databaseTypeSelect = document.createElement('select');
  databaseTypeSelect.name = 'database_type_skills[]';
  databaseTypeSelect.className = 'database-type-skills';
  databaseTypeSelect.innerHTML = `
    <option value="">Select Database Type</option>
    <option value="relational">Relational Databases</option>
    <option value="non-relational">Non-Relational Databases</option>
  `;
  databaseSubcategory.appendChild(databaseTypeSelect);

  const specificDatabaseSkills = document.createElement('div');
  specificDatabaseSkills.className = 'specific-database-skills-offer';
  specificDatabaseSkills.style.display = 'none';
  specificDatabaseSkills.style.marginTop = '10px';

  const specificSkillSelect = document.createElement('select');
  specificSkillSelect.name = 'specific_database_skill_offer[]';
  specificSkillSelect.className = 'specific-database-skill-offer';
  specificSkillSelect.innerHTML = '<option value="">Select Specific Skill</option>';
  specificDatabaseSkills.appendChild(specificSkillSelect);

  categorySel.addEventListener('change', () => {
    handleSkillCategoryChange(categorySel);
    saveSkillsToStorage();
    updateAssessmentButtons();
    validateFormRealTime();
  });

  skillSel.addEventListener('change', () => {
    saveSkillsToStorage();
    updateAssessmentButtons();
    validateFormRealTime();
  });

  databaseTypeSelect.addEventListener('change', () => {
    handleSkillDatabaseTypeChange(databaseTypeSelect);
    saveSkillsToStorage();
    validateFormRealTime();
  });

  const buttonContainer = document.createElement('div');
  buttonContainer.className = 'assessment-button-container';
  buttonContainer.style.display = 'flex';
  buttonContainer.style.gap = '8px';

  group.append(categorySel, skillSel, databaseSubcategory, specificDatabaseSkills, buttonContainer);
  container.appendChild(group);

  if (selectedCategory) {
    handleSkillCategoryChange(categorySel);
    if (selectedSkill && selectedCategory !== 'Database') {
      skillSel.value = selectedSkill;
    }
  }

  saveSkillsToStorage();
  updateAssessmentButtons();
  validateFormRealTime();
}

function populateSpecificSkills(categorySelect, skillSelect, selectedSkill = "") {
  const category = categorySelect.value;
  const selectedSkills = getSelectedSkills(category);
  skillSelect.innerHTML = '<option disabled selected value="">Specific Skill</option>';
  
  (skillsByCategory[category] || []).forEach(skill => {
    const opt = document.createElement('option');
    opt.value = skill;
    opt.text = skill;

    if (selectedSkills.includes(skill) && skill !== selectedSkill) {
      opt.disabled = true;
      opt.text += " (Taken)";
    }
    if (skill === selectedSkill) opt.selected = true;
    skillSelect.add(opt);
  });
}

function handleSkillCategoryChange(categorySelect) {
  const skillGroup = categorySelect.closest('.skills-offer');
  const skillSelect = skillGroup.querySelector('select[name="specific_skill[]"]');
  const databaseSubcategory = skillGroup.querySelector('.database-subcategory-skills');
  const specificDatabaseSkills = skillGroup.querySelector('.specific-database-skills-offer');

  if (categorySelect.value === 'Database') {
    skillSelect.style.display = 'none';
    databaseSubcategory.style.display = 'block';
    specificDatabaseSkills.style.display = 'none';
    skillSelect.value = '';
    skillGroup.querySelector('.database-type-skills').value = '';
    skillGroup.querySelector('.specific-database-skill-offer').value = '';
  } else {
    skillSelect.style.display = 'block';
    databaseSubcategory.style.display = 'none';
    specificDatabaseSkills.style.display = 'none';
    populateSpecificSkills(categorySelect, skillSelect);
    skillGroup.querySelector('.database-type-skills').value = '';
    skillGroup.querySelector('.specific-database-skill-offer').value = '';
  }
}

function handleSkillDatabaseTypeChange(databaseTypeSelect) {
  const skillGroup = databaseTypeSelect.closest('.skills-offer');
  const specificDatabaseSkills = skillGroup.querySelector('.specific-database-skills-offer');
  const specificSkillSelect = skillGroup.querySelector('.specific-database-skill-offer');
  const regularSkillSelect = skillGroup.querySelector('select[name="specific_skill[]"]');

  if (databaseTypeSelect.value) {
    specificDatabaseSkills.style.display = 'block';
    specificSkillSelect.innerHTML = '<option value="">Select Specific Skill</option>';

    const relationalSkills = ['SQL', 'MySQL', 'PostgreSQL', 'Oracle Database', 'SQL Server'];
    const nonRelationalSkills = ['MongoDB', 'NoSQL', 'Cassandra', 'Redis', 'DynamoDB'];

    const skills = databaseTypeSelect.value === 'relational' ? relationalSkills : nonRelationalSkills;
    const selectedSkills = getSelectedSkills('Database');

    skills.forEach(skill => {
      const option = document.createElement('option');
      option.value = skill;
      option.textContent = skill;

      if (selectedSkills.includes(skill)) {
        option.disabled = true;
        option.textContent += " (Taken)";
      }

      specificSkillSelect.appendChild(option);
    });

    specificSkillSelect.onchange = function () {
      if (this.value) {
        regularSkillSelect.value = this.value;
        updateAssessmentButtons();
        saveSkillsToStorage();
      }
    };
  } else {
    specificDatabaseSkills.style.display = 'none';
    specificSkillSelect.value = '';
    regularSkillSelect.value = '';
  }
}

function updateAssessmentButtons() {
  console.log('updateAssessmentButtons called');
  const skillGroups = document.querySelectorAll('.skills-offer');
  const mainBtnContainer = document.getElementById('main-assessment-btn');
  const mainBtn = document.getElementById('assessment-btn');

  console.log('Skill groups found:', skillGroups.length);
  // Show/hide bottom button for single skill group
  mainBtnContainer.style.display = skillGroups.length === 1 ? 'block' : 'none';

  skillGroups.forEach((group) => {
    const category = group.querySelector('select[name="category[]"]').value;
    const regularSkillSelect = group.querySelector('select[name="specific_skill[]"]');
    const dbSkillSelect = group.querySelector('.specific-database-skill-offer');
    const btnContainer = group.querySelector('.assessment-button-container');
    btnContainer.innerHTML = '';

    let skill = '';
    if (category === 'Database') {
      skill = dbSkillSelect ? dbSkillSelect.value : '';
    } else {
      skill = regularSkillSelect ? regularSkillSelect.value : '';
    }

    if (category && skill) {
      console.log('Skill selected:', category, skill);
      if (assessmentResults[category]?.[skill]) {
        console.log('Assessment already completed for:', category, skill);
        // Show result badge only
        const badge = document.createElement('span');
        badge.className = 'badge badge-primary';
        badge.textContent = assessmentResults[category][skill];
        btnContainer.appendChild(badge);

        // Disable selects for completed assessment
        group.querySelectorAll('select').forEach(s => s.disabled = true);

        // ✅ Revalidate form after adding badge
        validateFormRealTime();

      } else if (skillGroups.length > 1) {
        // Show assessment and remove buttons if no result
        const assessBtn = document.createElement('button');
        assessBtn.type = 'button';
        assessBtn.className = 'skills-btn individual-assessment-btn';
        assessBtn.textContent = 'Take Initial Assessment';
        assessBtn.onclick = () => {
          console.log('Individual assessment button clicked for:', category, skill);
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = 'initial_assessment.php';
          form.innerHTML = `<input type="hidden" name="category[]" value="${category}">
                            <input type="hidden" name="specific_skill[]" value="${skill}">`;
          document.body.appendChild(form);
          form.submit();
        };

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn';
        removeBtn.textContent = '❌';
        removeBtn.onclick = () => {
          group.remove();
          if (document.querySelectorAll('.skills-offer').length === 0) addSkillSet();
          saveSkillsToStorage();
          updateAssessmentButtons();
          validateFormRealTime();  // ✅ Revalidate form after removal
        };

        if (category === 'Database') {
          btnContainer.appendChild(removeBtn);
          let dbBtnWrapper = group.querySelector('.database-assessment-wrapper');
          if (dbBtnWrapper) dbBtnWrapper.remove();
          dbBtnWrapper = document.createElement('div');
          dbBtnWrapper.className = 'database-assessment-wrapper';
          dbBtnWrapper.appendChild(assessBtn);
          group.appendChild(dbBtnWrapper);
        } else {
          btnContainer.appendChild(assessBtn);
          btnContainer.appendChild(removeBtn);
        }

        // ✅ Revalidate form after button changes
        validateFormRealTime();
      }
    }
  });

  // Logic for main bottom assessment button for single skill group
  if (skillGroups.length === 1) {
    const group = skillGroups[0];
    const category = group.querySelector('select[name="category[]"]').value;
    const skill = category === 'Database'
      ? group.querySelector('.specific-database-skill-offer')?.value
      : group.querySelector('select[name="specific_skill[]"]').value;

    console.log('Assessment button debug:', { category, skill, assessmentResults: assessmentResults[category]?.[skill] });

    if (category && skill && !assessmentResults[category]?.[skill]) {
      mainBtn.disabled = false;
      mainBtn.onclick = () => {
        console.log('Assessment button clicked for:', category, skill);
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'initial_assessment.php';
        form.innerHTML = `<input type="hidden" name="category[]" value="${category}">
                          <input type="hidden" name="specific_skill[]" value="${skill}">`;
        document.body.appendChild(form);
        form.submit();
      };
    } else {
      mainBtn.disabled = true;
      mainBtn.onclick = null;
      if (assessmentResults[category]?.[skill]) {
        mainBtnContainer.style.display = 'none';
      }
    }
  }

  // Final form validation after all assessment button updates
  validateFormRealTime();
}

function checkIfFormCanBeSubmitted() {
  // Always allow form submission - assessments are optional for profile completion
  document.getElementById('submit-btn').disabled = false;
}

function validateFormRealTime() {
  const form = document.getElementById('profile-setup-form');
  const submitBtn = document.getElementById('submit-btn');

  let allValid = true;

  // 1️⃣ Check required form inputs
  const requiredFields = form.querySelectorAll('input[required]:not([disabled]), select[required]:not([disabled]), textarea[required]:not([disabled])');
  requiredFields.forEach(field => {
    if (!field.value.trim()) {
      allValid = false;
    }
  });

  // 2️⃣ Check Skills Offer fields (assessments are optional)
  const skillGroups = document.querySelectorAll('.skills-offer');
  if (skillGroups.length === 0) {
    allValid = false;
  } else {
    skillGroups.forEach(group => {
      const category = group.querySelector('select[name="category[]"]').value;
      const skill = group.querySelector('select[name="specific_skill[]"]').value;

      if (!category) {
        allValid = false;
      }

      if (category === 'Database') {
        const databaseType = group.querySelector('.database-type-skills').value;
        const specificDatabaseSkill = group.querySelector('.specific-database-skill-offer').value;

        if (!databaseType || !specificDatabaseSkill) {
          allValid = false;
        }
        // Note: Assessment is optional, so we don't require it here
      } else {
        if (!skill) {
          allValid = false;
        }
        // Note: Assessment is optional, so we don't require it here
      }
    });
  }

  // 3️⃣ Check Learning Goals fields
  const wantToLearn = document.getElementById('want_to_learn_select').value;
  const proficiencyLevel = document.getElementById('proficiency_level_select').value;
  if (!wantToLearn || !proficiencyLevel) {
    allValid = false;
  }

  // If Learning Goal is 'Database', check its sub-fields
  if (wantToLearn === 'Database') {
    const databaseType = document.getElementById('database_type_select').value;
    const specificSkill = document.getElementById('specific_database_skill_select').value;
    if (!databaseType || !specificSkill) {
      allValid = false;
    }
  }

  // 4️⃣ Enable/Disable Submit Button based on allValid
  submitBtn.disabled = !allValid;
  submitBtn.style.backgroundColor = allValid ? '#28a745' : '#bbb';
  submitBtn.style.cursor = allValid ? 'pointer' : 'not-allowed';
}

function attachValidationListeners() {
  document.querySelectorAll('input, select, textarea').forEach(input => {
    input.removeEventListener('input', validateFormRealTime);
    input.removeEventListener('change', validateFormRealTime);
    input.addEventListener('input', validateFormRealTime);
    input.addEventListener('change', validateFormRealTime);
  });
}

// Run initially
window.addEventListener('load', () => {
  validateFormRealTime();
  attachValidationListeners();
});


// Redirect assessment button logic
  function redirectToAssessment() {
    const category = document.getElementById('primary-category').value;
    const skill = document.getElementById('primary-skill').value;
    if (category && skill) {
      const form = document.getElementById('profile-setup-form');
      form.querySelectorAll('input, select, textarea').forEach(input => {
        localStorage.setItem(currentUserID + "_" + input.name, input.value);
      });
      const assessmentForm = document.createElement('form');
      assessmentForm.method = 'POST';
      assessmentForm.action = 'initial_assessment.php';
      assessmentForm.innerHTML = `
        <input type="hidden" name="category[]" value="${category}">
        <input type="hidden" name="specific_skill[]" value="${skill}">
      `;
      document.body.appendChild(assessmentForm);
      assessmentForm.submit();
    }
  }

  function saveSkillsToStorage() {
    const skills = [];
    document.querySelectorAll('.skills-offer').forEach(group => {
      skills.push({
        category: group.querySelector('select[name="category[]"]').value,
        skill: group.querySelector('select[name="specific_skill[]"]').value
      });
    });
    localStorage.setItem(currentUserID + '_skills-offer-data', JSON.stringify(skills));
  }

// Handle Database category selection in learning goals
function handleLearningGoalChange() {
  const wantToLearn = document.getElementById('want_to_learn_select').value;
  const databaseSubcategory = document.getElementById('database-subcategory');
  const specificDatabaseSkills = document.getElementById('specific-database-skills');
  
  if (wantToLearn === 'Database') {
    databaseSubcategory.style.display = 'block';
    specificDatabaseSkills.style.display = 'none';
  } else {
    databaseSubcategory.style.display = 'none';
    specificDatabaseSkills.style.display = 'none';
    // Reset selections when not Database
    document.getElementById('database_type_select').value = '';
    document.getElementById('specific_database_skill_select').value = '';
  }
}

function getSelectedSkills(category) {
  const selectedSkills = [];
  document.querySelectorAll('.skills-offer').forEach(group => {
    const selectedCategory = group.querySelector('select[name="category[]"]').value;
    let selectedSkill = '';
    if (selectedCategory === 'Database') {
      const dbSkillSelect = group.querySelector('.specific-database-skill-offer');
      selectedSkill = dbSkillSelect ? dbSkillSelect.value : '';
    } else {
      const skillSelect = group.querySelector('select[name="specific_skill[]"]');
      selectedSkill = skillSelect ? skillSelect.value : '';
    }
    if (selectedCategory === category && selectedSkill) {
      selectedSkills.push(selectedSkill);
    }
  });
  return selectedSkills;
}

// Handle Database type selection (relational/non-relational)
function handleDatabaseTypeChange() {
  const databaseType = document.getElementById('database_type_select').value;
  const specificSkillsDiv = document.getElementById('specific-database-skills');
  const specificSkillSelect = document.getElementById('specific_database_skill_select');
  
  if (databaseType) {
    specificSkillsDiv.style.display = 'block';
    
    // Clear previous options
    specificSkillSelect.innerHTML = '<option value="">Select Specific Skill</option>';
    
    // Add options based on database type
    if (databaseType === 'relational') {
      const relationalSkills = ['SQL', 'MySQL', 'PostgreSQL', 'Oracle Database', 'SQL Server'];
      relationalSkills.forEach(skill => {
        const option = document.createElement('option');
        option.value = skill;
        option.textContent = skill;
        specificSkillSelect.appendChild(option);
      });
    } else if (databaseType === 'non-relational') {
      const nonRelationalSkills = ['MongoDB', 'NoSQL', 'Cassandra', 'Redis', 'DynamoDB'];
      nonRelationalSkills.forEach(skill => {
        const option = document.createElement('option');
        option.value = skill;
        option.textContent = skill;
        specificSkillSelect.appendChild(option);
      });
    }
  } else {
    specificSkillsDiv.style.display = 'none';
    specificSkillSelect.value = '';
  }
}

// Initialize Database selection on page load
document.addEventListener('DOMContentLoaded', function() {
  handleLearningGoalChange();
});
</script>


</body>
</html>

