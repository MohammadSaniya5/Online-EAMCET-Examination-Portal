<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

$student_id = $_SESSION['student_id'] ?? null;
if (!$student_id) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

try {
    if (isset($_POST['question_id']) && isset($_POST['answer'])) {
        $question_id = (int) $_POST['question_id'];
        $selected_option = trim($_POST['answer']);
        $subject_id = isset($_POST['subject_id']) ? (int) $_POST['subject_id'] : 0;

        if ($question_id <= 0 || $selected_option === '') {
            http_response_code(400);
            echo json_encode(["error" => "Invalid question ID or answer"]);
            exit;
        }

        if ($subject_id <= 0) {
            $stmt = $pdo->prepare("SELECT subject_id FROM questions WHERE id = ?");
            $stmt->execute([$question_id]);
            $subject_id = $stmt->fetchColumn();

            if (!$subject_id) {
                http_response_code(404);
                echo json_encode(["error" => "Question not found"]);
                exit;
            }
        }

        $display_order = isset($_POST['display_order']) ? (int) $_POST['display_order'] : 1;


        $review = isset($_POST['review']) ? (int) $_POST['review'] : 2;

        if (!empty($selected_option) && $review != 1) {
            $review = 0;
        }


        $check = $pdo->prepare("SELECT id FROM answers WHERE student_id = ? AND question_id = ?");
        $check->execute([$student_id, $question_id]);
        if ($check->rowCount()) {
            $update = $pdo->prepare("UPDATE answers SET selected_option = ?, review = ? WHERE student_id = ? AND question_id = ?");
            $update->execute([$selected_option, $review, $student_id, $question_id]);
        } else {
            $insert = $pdo->prepare("INSERT INTO answers (student_id, subject_id, question_id, selected_option, display_order, review) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([$student_id, $subject_id, $question_id, $selected_option, $display_order, $review]);
        }


        $count = $pdo->prepare("SELECT COUNT(*) FROM answers WHERE student_id = ? AND subject_id = ?");
        $count->execute([$student_id, $subject_id]);
        $answered = (int) $count->fetchColumn();

        echo json_encode([
            'status' => 'saved',
            'subject_id' => $subject_id,
            'answered' => $answered
        ]);
        exit;
    } elseif (!empty($_POST)) {
        $subject_id = null;
        $saved = 0;

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'answer_') === 0) {
                $question_id = (int) str_replace('answer_', '', $key);
                $selected_option = trim($value);

                if ($question_id <= 0 || $selected_option === '')
                    continue;

                $stmt = $pdo->prepare("SELECT subject_id FROM questions WHERE id = ?");
                $stmt->execute([$question_id]);
                $subject_id = $stmt->fetchColumn();

                if (!$subject_id)
                    continue;

                $display_order = isset($_POST['display_order']) ? (int) $_POST['display_order'] : 1;

                $check = $pdo->prepare("SELECT id FROM answers WHERE student_id = ? AND question_id = ?");
                $check->execute([$student_id, $question_id]);
                $review = 2;
                if (!empty($selected_option))
                    $review = 0;

                if ($check->rowCount()) {
                    $update = $pdo->prepare("UPDATE answers SET selected_option = ?, review = ? WHERE student_id = ? AND question_id = ?");
                    $update->execute([$selected_option, $review, $student_id, $question_id]);
                } else {
                    $insert = $pdo->prepare("INSERT INTO answers (student_id, subject_id, question_id, selected_option, display_order, review) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert->execute([$student_id, $subject_id, $question_id, $selected_option, $display_order, $review]);
                }


                $saved++;
            }
        }

        $answered = 0;
        if ($subject_id) {
            $count = $pdo->prepare("SELECT COUNT(*) FROM answers WHERE student_id = ? AND subject_id = ?");
            $count->execute([$student_id, $subject_id]);
            $answered = (int) $count->fetchColumn();
        }

        echo json_encode([
            'status' => 'bulk_saved',
            'subject_id' => $subject_id,
            'saved_count' => $saved,
            'answered' => $answered
        ]);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(["error" => "No valid data received."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
