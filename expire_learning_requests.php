<?php
// This script can be run as a cron job or included in page loads
$conn = new mysqli('localhost', 'root', '', 'sia1');
if ($conn->connect_error) {
    die('Database connection failed');
}

$sql = "UPDATE learning_requests SET status = 'expired', responded_at = NOW() WHERE status = 'pending' AND created_at < (NOW() - INTERVAL 24 HOUR)";
$conn->query($sql);
$conn->close(); 