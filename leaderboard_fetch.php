<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) die("DB error");

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($me_id);
$stmt->fetch();
$stmt->close();

$skill = $_GET['skill'] ?? '';
$level = $_GET['level'] ?? '';

// Build leaderboard query
$sql = "
SELECT u.user_id, up.points, up.rating, up.skill, up.proficiency,
       p.profile_pic, p.full_name
FROM user_points up
JOIN users_profile p ON up.user_id = p.user_id
JOIN users u ON u.user_id = up.user_id
WHERE 1
";
if ($skill) $sql .= " AND up.skill = '".$conn->real_escape_string($skill)."'";
if ($level) $sql .= " AND up.proficiency = '".$conn->real_escape_string($level)."'";
$sql .= " ORDER BY up.points DESC";

$res = $conn->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

$rank = 1; $me_inserted=false;
$output = '';

// function to get owner’s current points, rating, skill, proficiency, avatar
function getMyStats($conn, $uid) {
    // Set default values
    $pts = $rate = $sk = $prof = $pic = $name = null;

  $q = $conn->prepare("SELECT points, rating, skill, proficiency FROM user_points WHERE user_id=? ORDER BY points DESC LIMIT 1");
  $q->bind_param("i", $uid);
  $q->execute();
  $q->bind_result($pts, $rate, $sk, $prof);
    if (!$q->fetch()) {
        // No row found, keep defaults as null
    }
    $q->close();

  $p = $conn->prepare("SELECT profile_pic, full_name FROM users_profile WHERE user_id=?");
  $p->bind_param("i", $uid);
  $p->execute();
  $p->bind_result($pic, $name);
    if (!$p->fetch()) {
        // No row found, keep defaults as null
    }
    $p->close();

  return [$pts, $rate, $sk, $prof, $pic, $name];
}

function renderRow($rank, $pic, $name, $points, $rating, $skill, $prof, $highlight = false) {
  $stars = str_repeat("★", $rating) . str_repeat("☆", 5-$rating);
  $youLabel = $highlight ? ' (You)' : '';
  return "<tr>"
    ."<td>$rank</td>"
    ."<td><img src='".htmlspecialchars($pic)."' class='avatar'></td>"
    ."<td>".htmlspecialchars($name)."$youLabel</td>"
    ."<td>$points</td>"
    ."<td class='rating'>$stars</td>"
    ."<td>".htmlspecialchars($skill)."</td>"
    ."<td>".htmlspecialchars($prof)."</td>"
    ."</tr>";
}

// loop through leaderboard rows
foreach($rows as $r) {
  if(!$me_inserted && $r['points'] < getMyStats($conn, $me_id)[0]) {
    list($mypoints, $myrate, $myskill, $myprof, $mypic, $myname) = getMyStats($conn, $me_id);
    $output .= renderRow($rank++, $mypic, $myname, $mypoints, $myrate, $myskill, $myprof, true);
    $me_inserted = true;
  }
  $output .= renderRow($rank++, $r['profile_pic'], $r['full_name'], $r['points'], $r['rating'], $r['skill'], $r['proficiency']);
}

// if owner still not inserted (e.g. no leaderboard entries)
if (!$me_inserted) {
  list($mypoints, $myrate, $myskill, $myprof, $mypic, $myname) = getMyStats($conn, $me_id);
  $output .= renderRow($rank++, $mypic, $myname, $mypoints, $myrate, $myskill, $myprof, true);
}

// if no users at all
if (count($rows) == 0) {
  $output .= "<tr><td colspan='7'>No matches yet</td></tr>";
}

echo $output;
?>
