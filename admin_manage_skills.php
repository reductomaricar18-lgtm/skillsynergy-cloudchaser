<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login_page.php");
    exit();
}

// Add new category
if (isset($_POST['add_category'])) {
    $category = trim($_POST['category']);
    if (!empty($category)) {
        // Prevent duplicate categories
        $check = $conn->prepare("SELECT 1 FROM skills_offer WHERE category = ? LIMIT 1");
        $check->bind_param("s", $category);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            // Insert a placeholder skill to create the category if not exists
            $stmt = $conn->prepare("INSERT INTO skills_offer (category, specific_skill) VALUES (?, ?)");
            $stmt->bind_param("ss", $category, $skill);
            $stmt->execute();
            $stmt->close();
            $success = 'Skill added successfully!';
        } else {
            $success = 'Category already exists!';
        }
        $check->close();
    }
}

// Add new skill
if (isset($_POST['add_skill']) && isset($_POST['category_id'])) {
    $category = $_POST['category_id'];
    $skill = '';
    // If Database, get the specific database skill
    if ($category === 'Database' && isset($_POST['db_type']) && isset($_POST['db_skill'])) {
        $skill = trim($_POST['db_skill']);
    } else if (isset($_POST['skill'])) {
        $skill = trim($_POST['skill']);
    }
    if (!empty($skill) && !empty($category)) {
        // Prevent duplicate skills in the same category
        $check = $conn->prepare("SELECT 1 FROM skills_offer WHERE category = ? AND specific_skill = ? LIMIT 1");
        $check->bind_param("ss", $category, $skill);
        $check->execute();
        $check->store_result();
        if ($check->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO skills_offer (category, specific_skill) VALUES (?, ?)");
            $stmt->bind_param("ss", $category, $skill);
            $stmt->execute();
            $stmt->close();
            $success = 'Skill added successfully!';
        } else {
            $success = 'Skill already exists in this category!';
        }
        $check->close();
    }
}

// Delete category
if (isset($_POST['delete_category'])) {
    $cat = $_POST['delete_category'];
    $stmt = $conn->prepare("DELETE FROM skills_offer WHERE category = ?");
    $stmt->bind_param("s", $cat);
    $stmt->execute();
    $stmt->close();
    $success = 'Category deleted.';
}

// Delete skill
if (isset($_POST['delete_skill'])) {
    $skill = $_POST['delete_skill'];
    $cat = $_POST['skill_category'];
    // Fetch the skill before deleting for undo
    $fetch = $conn->prepare("SELECT * FROM skills_offer WHERE category = ? AND specific_skill = ? LIMIT 1");
    $fetch->bind_param("ss", $cat, $skill);
    $fetch->execute();
    $result = $fetch->get_result();
    $skill_data = $result->fetch_assoc();
    $fetch->close();
    if ($skill_data) {
        $_SESSION['undo_skill'] = [
            'category' => $cat,
            'specific_skill' => $skill,
            'timestamp' => time()
        ];
    }
    $stmt = $conn->prepare("DELETE FROM skills_offer WHERE category = ? AND specific_skill = ?");
    $stmt->bind_param("ss", $cat, $skill);
    $stmt->execute();
    $stmt->close();
    $success = 'Skill deleted.';
}

// Undo skill deletion
if (isset($_POST['undo_skill'])) {
    if (isset($_SESSION['undo_skill'])) {
        $undo = $_SESSION['undo_skill'];
        // Only allow undo within 10 seconds
        if (time() - $undo['timestamp'] <= 10) {
            // Prevent duplicate restore
            $check = $conn->prepare("SELECT 1 FROM skills_offer WHERE category = ? AND specific_skill = ? LIMIT 1");
            $check->bind_param("ss", $undo['category'], $undo['specific_skill']);
            $check->execute();
            $check->store_result();
            if ($check->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO skills_offer (category, specific_skill) VALUES (?, ?)");
                $stmt->bind_param("ss", $undo['category'], $undo['specific_skill']);
                $stmt->execute();
                $stmt->close();
                $success = 'Skill restored!';
            } else {
                $success = 'Skill already exists.';
            }
            $check->close();
        } else {
            $success = 'Undo period expired.';
        }
        unset($_SESSION['undo_skill']);
    }
}

// Fetch categories from skills_offer (distinct)
$categories = $conn->query("SELECT DISTINCT category FROM skills_offer WHERE category != '' ORDER BY category ASC");
// Fetch all unique skills from skills_offer grouped by category
$category_skills = [];
$skills_query = $conn->query("SELECT category, specific_skill FROM skills_offer WHERE specific_skill != '' ORDER BY category, specific_skill");
while ($row = $skills_query->fetch_assoc()) {
    $cat = $row['category'];
    if (!isset($category_skills[$cat])) {
        $category_skills[$cat] = [];
    }
    if (!in_array($row['specific_skill'], $category_skills[$cat])) {
        $category_skills[$cat][] = $row['specific_skill'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Skills - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(120deg, #e0f7fa 0%, #b2ebf2 100%);
            background-image: url('SSbg.jpg'), linear-gradient(120deg, #e0f7fa 0%, #b2ebf2 100%);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden; /* Prevent whole page scrolling */
        }

        .container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            max-width: 1300px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 22px;
            box-shadow: 0 4px 32px rgba(0, 0, 0, 0.10);
            padding: 38px 32px 32px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;         /* This enables inner scrolling */
            height: 70vh;           
        }
        .container {
            scroll-behavior: smooth;
        }

        h2 {
            color: #2d0252;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
        }
        h3 {
            color: #2d0252;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: left;
            width: 100%;
        }
        form {
            margin-bottom: 22px;
            background: #f7fafc;
            padding: 18px 18px 12px 18px;
            border-radius: 12px;
            width: 100%;
            box-sizing: border-box;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        input, select {
            padding: 12px;
            margin: 8px 0 16px 0;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px 0;
            background:rgb(78, 122, 225);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            transition: background 0.2s;
        }
        button:hover { background:rgb(8, 8, 8); }
        .back-btn {
            display: inline-block;
            margin: 30px 0 0 30px;
            color: #fff;
            background:rgb(78, 122, 225);
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: absolute;
            left: 0;
            top: 0;
        }
        .back-btn:hover { background:rgb(11, 11, 11); }
        @media (max-width: 600px) {
            .container {
                max-width: 98vw;
                padding: 18px 4vw 18px 4vw;
            }
            .back-btn {
                margin: 18px 0 0 8px;
                font-size: 0.95rem;
                padding: 8px 14px;
            }
        }
    </style>
</head>
<body>
<a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
<div class="container">
    <h2> Manage Skills</h2>

    <?php if (!empty($success)): ?>
        <div style="background:#e0f7fa;color:#007c91;padding:10px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:600;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <h3>Add New Category</h3>
    <form method="post">
        <input type="text" name="category" placeholder="Category Name" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>

    <h3>Existing Categories</h3>
    <div style="width:100%;margin-bottom:18px;">
        <?php $categories->data_seek(0); while($cat = $categories->fetch_assoc()): ?>
            <form method="post" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;background:#f7fafc;padding:12px 16px;border-radius:10px;">
                <span><?php echo htmlspecialchars($cat['category']); ?></span>
                <button type="submit" name="delete_category" value="<?php echo $cat['category']; ?>" style="background:rgb(8, 8, 8); ;color:rgb(232, 234, 238);;padding:12px 0;border-radius:8px;font-size:1.1rem;border:none;cursor:pointer;width:60%;font-weight:600;">Delete</button>
            </form>
        <?php endwhile; ?>
    </div>

    <h3>Add New Skill</h3>
    <form method="post" id="add-skill-form">
        <input type="text" name="skill" id="skill-input" placeholder="Skill Name" required>
        <select name="category_id" id="category-select" required onchange="handleCategoryChange()">
            <option value="">Select Category</option>
            <?php $categories->data_seek(0); while($cat = $categories->fetch_assoc()) : ?>
                <option value="<?php echo $cat['category']; ?>"><?php echo $cat['category']; ?></option>
            <?php endwhile; ?>
        </select>
        <div id="db-type-group" style="display:none; margin-top:10px;">
            <select name="db_type" id="db-type-select" onchange="handleDbTypeChange()">
                <option value="">Select Database Type</option>
                <option value="relational">Relational Database</option>
                <option value="non-relational">Non-Relational Database</option>
            </select>
        </div>
        <div id="db-skill-group" style="display:none; margin-top:10px;">
            <select name="db_skill" id="db-skill-select">
                <option value="">Select Specific Skill</option>
            </select>
        </div>
        <button type="submit" name="add_skill">Add Skill</button>
    </form>

    <h3>Existing Skills</h3>
    <div style="width:100%;">
        <div style="
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            width: 100%;
        ">
            <?php foreach ($category_skills as $cat => $skills): ?>
                <?php foreach ($skills as $skill): ?>
                    <form method="post" style="background: #f7fafc; padding: 10px; border-radius: 8px; box-sizing: border-box;">
                        <div style="font-size: 0.95rem; margin-bottom: 6px;">
                            <strong><?php echo htmlspecialchars($skill); ?></strong><br>
                            <span style="color: #3f51b5; font-size: 0.85rem;">(<?php echo htmlspecialchars($cat); ?>)</span>
                        </div>
                        <input type="hidden" name="skill_category" value="<?php echo htmlspecialchars($cat); ?>">
                        <button type="submit" name="delete_skill" value="<?php echo htmlspecialchars($skill); ?>" style="width: 100%; padding: 6px 0; background: #000; color: #fff; border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 500; margin-bottom: 6px;">Delete</button>
                        <div style="margin-top: 6px; text-align: center;">
                            <a href="lessons/add_lesson.php?skill=<?php echo urlencode($skill); ?>" 
                               style="display: inline-block; padding: 6px 12px; background: #4caf50; color: white; font-size: 0.85rem; border-radius: 6px; text-decoration: none;">Lesson</a>
                        </div>
                    </form>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php if (empty($category_skills)): ?>
                <div style="color:#888;text-align:center;">No skills found.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function handleCategoryChange() {
    const cat = document.getElementById('category-select').value;
    const dbTypeGroup = document.getElementById('db-type-group');
    const dbSkillGroup = document.getElementById('db-skill-group');
    const skillInput = document.getElementById('skill-input');
    if (cat === 'Database') {
        dbTypeGroup.style.display = '';
        dbSkillGroup.style.display = 'none';
        skillInput.disabled = true;
        skillInput.value = '';  
    } else {
        dbTypeGroup.style.display = 'none';
        dbSkillGroup.style.display = 'none';
        skillInput.disabled = false;
    }
}
function handleDbTypeChange() {
    const dbType = document.getElementById('db-type-select').value;
    const dbSkillGroup = document.getElementById('db-skill-group');
    const dbSkillSelect = document.getElementById('db-skill-select');
    const skillInput = document.getElementById('skill-input');
    dbSkillSelect.innerHTML = '<option value="">Select Specific Skill</option>';
    if (dbType === 'relational') {
        ['SQL', 'MySQL', 'PostgreSQL', 'Oracle Database', 'SQL Server'].forEach(skill => {
            const opt = document.createElement('option');
            opt.value = skill;
            opt.textContent = skill;
            dbSkillSelect.appendChild(opt);
        });
        dbSkillGroup.style.display = '';
    } else if (dbType === 'non-relational') {
        ['MongoDB', 'NoSQL', 'Cassandra', 'Redis', 'DynamoDB'].forEach(skill => {
            const opt = document.createElement('option');
            opt.value = skill;
            opt.textContent = skill;
            dbSkillSelect.appendChild(opt);
        });
        dbSkillGroup.style.display = '';
    } else {
        dbSkillGroup.style.display = 'none';
    }
    skillInput.value = '';
}
document.getElementById('db-skill-select').addEventListener('change', function() {
    document.getElementById('skill-input').value = this.value;
});
document.getElementById('add-skill-form').addEventListener('submit', function(e) {
    const cat = document.getElementById('category-select').value;
    if (cat === 'Database') {
        const dbType = document.getElementById('db-type-select').value;
        const dbSkill = document.getElementById('db-skill-select').value;
        if (!dbType || !dbSkill) {
            e.preventDefault();
            alert('Please select both database type and specific skill.');
            return false;
        }
    }
});
</script>
</body>
</html>
