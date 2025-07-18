<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin access check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login_page.php");
    exit();
}

// Handle promote/demote/disable/enable actions
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    $action = $_GET['action'];

    if ($action === 'promote') {
        $conn->query("UPDATE users SET account_type = 'admin' WHERE user_id = $userId");
    } elseif ($action === 'demote') {
        $conn->query("UPDATE users SET account_type = 'user' WHERE user_id = $userId");
    } elseif ($action === 'disable') {
        $conn->query("UPDATE users SET status = 'disabled' WHERE user_id = $userId");
    } elseif ($action === 'enable') {
        $conn->query("UPDATE users SET status = 'active' WHERE user_id = $userId");
    }

    header("Location: admin_manage_accounts.php");
    exit();
}

// Fetch all users with their account types, login/logout times, last login, and status
$sql = "SELECT user_id, email, account_type, status, last_login, login_time, logout_time FROM users ORDER BY email";
$result = $conn->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Accounts - SkillSynergy</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(120deg, #e0f7fa 0%, #b2ebf2 100%);
            background-image: url('SSbg.jpg'), linear-gradient(120deg, #e0f7fa 0%, #b2ebf2 100%);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto 0 auto;
            background: rgba(255,255,255,0.97);
            border-radius: 22px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.10);
            padding: 20px 24px 20px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .scroll-table-wrapper {
            width: 100%;
            max-height: 60vh;
            overflow-y: auto;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-top: 20px;
        }
        h2 {
            color: #2d0252;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background: #f7fafc;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 14px 12px;
            text-align: left;
            font-size: 1.05rem;
        }
        th {
            background-color: #e0f7fa;
            color: #2d0252;
            font-weight: 700;
        }
        tr:nth-child(even) { background: #f7fafc; }
        tr:nth-child(odd) { background: #f0f4f8; }
        .status-active {
            color: #219653;
            font-weight: 700;
        }
        .status-disabled {
            color: #b71c1c;
            font-weight: 700;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropbtn {
            padding: 8px 24px 8px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-family: inherit;
            font-weight: 600;
            background-color: #4caf50;
            color: white;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dropbtn:after {
            content: '\25BC';
            font-size: 0.8em;
            margin-left: 8px;
            color: #fff;
        }
        .dropbtn.open:after {
            content: '\25B2';
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 180px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.13);
            z-index: 1;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 8px;
            animation: fadeIn 0.2s;
            padding: 0;
        }
        .dropdown-content a, .dropdown-content form button {
            color: #2d0252;
            padding: 14px 20px;
            text-decoration: none;
            display: block;
            font-size: 1.08rem;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.18s, color 0.18s;
        }
        .dropdown-content a:hover, .dropdown-content form button:hover {
            background-color: #e0f7fa;
            color: #007c91;
        }
        .dropdown-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 0;
            border: none;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown.show .dropdown-content {
            display: block;
        }
        .demote {
            background-color: #dc3545;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .demote:hover {
            background-color: #a71d2a;
        }
        .back {
            margin: 30px 0 0 0;
            display: inline-block;
            text-decoration: none;
            padding: 12px 28px;
            background: #4a90e2;
            color: #fff;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: background 0.2s;
            align-self: flex-start;
        }
        .back:hover { background: #222; }
        @media (max-width: 700px) {
            .container { max-width: 98vw; padding: 18px 4vw 18px 4vw; }
            table { font-size: 0.95rem; }
            .back { font-size: 1rem; padding: 10px 18px; }
        }
    </style>
</head>
<body>
<div class="container">
    <a href="admin_dashboard.php" class="back" style="align-self: flex-start; margin-bottom: 10px;">&larr; Back to Dashboard</a>
    <h2>👥 Manage Accounts</h2>
    <?php if (!empty($users)) : ?>
    <div class="scroll-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Account Type</th>
                    <th>Status</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['account_type']); ?></td>
                    <td>
                        <?php if (isset($user['status']) && $user['status'] === 'disabled'): ?>
                            <span class="status-disabled">Disabled</span>
                        <?php else: ?>
                            <span class="status-active">Active</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $user['login_time'] ? date('M d, Y H:i', strtotime($user['login_time'])) : 'Never'; ?></td>
                    <td><?php echo $user['logout_time'] ? date('M d, Y H:i', strtotime($user['logout_time'])) : 'Never'; ?></td>
                    <td>
                        <?php if ($user['account_type'] === 'user') : ?>
                            <div class="dropdown">
                                <button class="dropbtn" onclick="toggleDropdown(event, this)">Manage</button>
                                <div class="dropdown-content">
                                    <a href="#" class="view-profile-btn" data-user-id="<?php echo $user['user_id']; ?>">View Profile</a>
                                    <hr class="dropdown-divider">
                                    <form method="get" action="admin_manage_accounts.php" style="margin:0;padding:0;">
                                        <input type="hidden" name="action" value="enable">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit">Enable Account</button>
                                    </form>
                                    <form method="get" action="admin_manage_accounts.php" style="margin:0;padding:0;">
                                        <input type="hidden" name="action" value="disable">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit">Disable Account</button>
                                    </form>
                                </div>
                            </div>
                        <?php elseif ($user['account_type'] === 'admin' && $user['user_id'] != $_SESSION['admin_id']) : ?>
                            <a href="?action=demote&user_id=<?php echo $user['user_id']; ?>" class="btn demote">Demote to User</a>
                        <?php else: ?>
                            <form method="get" action="admin_manage_accounts.php" style="margin:0;padding:0;">
                                <input type="hidden" name="action" value="enable">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <button type="submit" class="btn demote">Enable Account</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>
<!-- Add modal HTML at the end of the container -->
<div id="profileModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
  <div id="profileModalContent" style="background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(31,38,135,0.18);padding:32px 32px 24px 32px;min-width:320px;max-width:95vw;max-height:90vh;overflow-y:auto;position:relative;">
    <button onclick="closeProfileModal()" style="position:absolute;top:12px;right:18px;background:none;border:none;font-size:1.5rem;color:#888;cursor:pointer;">&times;</button>
    <div id="profileModalBody">
      <!-- User info will be loaded here -->
    </div>
  </div>
</div>
<script>
// Dropdown logic: open on click, close on outside click
function toggleDropdown(event, btn) {
    event.preventDefault();
    event.stopPropagation();
    // Close all other dropdowns
    document.querySelectorAll('.dropdown').forEach(function(drop) {
        if (drop.contains(btn)) {
            drop.classList.toggle('show');
            btn.classList.toggle('open');
        } else {
            drop.classList.remove('show');
            var b = drop.querySelector('.dropbtn');
            if (b) b.classList.remove('open');
        }
    });
}
document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown').forEach(function(drop) {
        drop.classList.remove('show');
        var b = drop.querySelector('.dropbtn');
        if (b) b.classList.remove('open');
    });
});

function showProfileModal(userId) {
  const modal = document.getElementById('profileModal');
  const body = document.getElementById('profileModalBody');
  body.innerHTML = '<div style="text-align:center;padding:30px 0;">Loading...</div>';
  modal.style.display = 'flex';
  fetch('get_user_info.php?user_id=' + encodeURIComponent(userId))
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        body.innerHTML = '<div style="color:red;text-align:center;">' + data.error + '</div>';
      } else {
        body.innerHTML = `
          <h2 style="text-align:center;margin-bottom:18px;">User  Profile</h2>
          <div style="font-size:1.08rem;line-height:1.7;">
            <strong>Name:</strong> ${data.name || '-'}<br>
            <strong>User ID:</strong> ${data.user_id || '-'}<br>
            <strong>Status:</strong> ${data.status || '-'}<br>
            <strong>Email:</strong> ${data.email || '-'}<br>
            <strong>Age:</strong> ${data.age || '-'}<br>
            <strong>Course:</strong> ${data.course || '-'}<br>
            <strong>Block:</strong> ${data.block || '-'}<br>
            <strong>Year:</strong> ${data.year || '-'}<br>
            <strong>Skills:</strong> ${data.skills || '-'}
          </div>
        `;
      }
    })
    .catch(() => {
      body.innerHTML = '<div style="color:red;text-align:center;">Failed to load user info.</div>';
    });
}
function closeProfileModal() {
  document.getElementById('profileModal').style.display = 'none';
}
// Attach to all View Profile links
window.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.view-profile-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      showProfileModal(this.getAttribute('data-user-id'));
    });
  });
});
</script>
</body>
</html>        