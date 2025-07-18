<?php
// Simple test to verify assessment bank accessibility
header('Content-Type: application/json');

echo "Testing assessment bank accessibility...\n";

$assessmentFile = 'assessments_bank.json';

// Check if file exists
if (!file_exists($assessmentFile)) {
    echo json_encode(['error' => 'Assessment file not found']);
    exit;
}

// Check if file is readable
if (!is_readable($assessmentFile)) {
    echo json_encode(['error' => 'Assessment file not readable']);
    exit;
}

// Try to read the file
$content = file_get_contents($assessmentFile);
if (!$content) {
    echo json_encode(['error' => 'Could not read assessment file']);
    exit;
}

// Try to decode JSON
$data = json_decode($content, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'JSON decode error: ' . json_last_error_msg()]);
    exit;
}

// Check structure
$topics = array_keys($data);
echo json_encode([
    'success' => true,
    'topics' => $topics,
    'topic_count' => count($topics),
    'sample_topic' => $topics[0] ?? 'none',
    'python_available' => isset($data['Python']),
    'php_available' => isset($data['PHP']),
    'python_beginner_questions' => isset($data['Python']['beginner']['multipleChoice']) ? count($data['Python']['beginner']['multipleChoice']) : 0
], JSON_PRETTY_PRINT);
?> 