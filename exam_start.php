<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
 
$_SESSION['exam_started'] = true;

echo json_encode(['status' => 'ok']);
