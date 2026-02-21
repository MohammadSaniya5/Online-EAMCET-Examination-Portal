<?php
require 'db.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$student_id = $_SESSION['student_id'] ?? null;
if (!$student_id) {
    header("Location: login.php");
    exit;
}

$check = $pdo->prepare("SELECT submitted_exam FROM students WHERE id = ?");
$check->execute([$student_id]);
$submitted = $check->fetchColumn();

if ($submitted) {

    showResults($pdo, $student_id);
    exit;
}

$subjectStmt = $pdo->query("SELECT id, name FROM subjects");
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

$total_score = 0;
$max_per_subject = [];

$subjectMarksStmt = $pdo->query("SELECT name, total_questions, marks_per_question FROM subjects");
$subjectData = $subjectMarksStmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($subjectData as $sub) {
    $max_per_subject[$sub['name']] = $sub['total_questions'] * $sub['marks_per_question'];
}

$subject_scores = [];

foreach ($subjects as $subject) {
    $subject_id = $subject['id'];
    $subject_name = $subject['name'];
    $max_marks_for_subject = $max_per_subject[$subject_name] ?? 0;
    $score = 0;

    $stmt = $pdo->prepare("
        SELECT a.selected_option, q.correct_option
        FROM answers a
        JOIN questions q ON a.question_id = q.id
        WHERE a.student_id = ? AND q.subject_id = ?
    ");
    $stmt->execute([$student_id, $subject_id]);
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($answers as $ans) {
        if ($ans['selected_option'] === $ans['correct_option']) {
            $score++;
        }
    }

    $pdo->prepare("INSERT INTO results (student_id, subject_id, score, created_at) VALUES (?, ?, ?, NOW())")
        ->execute([$student_id, $subject_id, $score]);

    $subject_scores[$subject_name] = $score;
    $total_score += $score;
}

$pdo->prepare("UPDATE students SET submitted_exam = 1 WHERE id = ?")->execute([$student_id]);

showResults($pdo, $student_id, $subject_scores, $total_score, $max_per_subject);

function showResults($pdo, $student_id, $subject_scores = null, $total_score = null, $max_per_subject = 10)
{
    if (!$subject_scores) {
        $subject_scores = [];
        $subjectStmt = $pdo->query("SELECT id, name FROM subjects");
        $subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

        $total_score = 0;
        foreach ($subjects as $subject) {
            $stmt = $pdo->prepare("SELECT score FROM results WHERE student_id = ? AND subject_id = ?");
            $stmt->execute([$student_id, $subject['id']]);
            $score = $stmt->fetchColumn() ?? 0;
            $subject_scores[$subject['name']] = $score;
            $total_score += $score;
        }
    }

    $max_total = 0;
    foreach ($subject_scores as $subject => $score) {
        $max_total += $max_per_subject[$subject] ?? 10;
    }
    $percentage = $max_total ? round(($total_score / $max_total) * 100) : 0;

    if ($percentage >= 80) {
        $greeting = "Excellent work! You’ve scored very well.";
    } elseif ($percentage >= 60) {
        $greeting = "Good job! You’ve passed with a decent score.";
    } else {
        $greeting = "Keep practicing! You can improve with more effort.";
    }

    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Exam Result</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-image: url('p1.jpg');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
                backdrop-filter: blur(0px);
                padding: 60px;
                text-align: center;
            }

            .result-box {
                display: inline-block;
                background: #fff;
                padding: 40px 60px;
                border-radius: 12px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            h1 {
                color: #2e7d32;
            }

            h2 {
                color: #00796b;
            }

            table {
                width: 100%;
                margin: 20px 0;
                border-collapse: collapse;
            }

            td,
            th {
                padding: 12px 18px;
                border: 1px solid #ccc;
                font-size: 16px;
            }

            th {
                background-color: #c8e6c9;
            }

            .total {
                font-size: 22px;
                margin-top: 25px;
                color: #004d40;
            }

            .greeting {
                font-size: 20px;
                margin-top: 15px;
                color: #333;
            }

            .logout-btn {
                margin-top: 18px;
                padding: 12px 25px;
                font-size: 16px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                background-color: rgb(16, 94, 203);
                color: white;
            }

            @media print {
                .logout-btn {
                    display: none;
                }

                .print-btn {
                    display: none;
                }
            }
        </style>
    </head>

    <body>
        <div class="result-box">
            <h1>Exam Submitted Successfully!</h1>
            <h2>Your Subject-wise Scores</h2>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Score</th>
                    <th>Out of</th>
                </tr>
                <?php foreach ($subject_scores as $subject => $score): ?>
                    <tr>
                        <td><?= htmlspecialchars($subject) ?></td>
                        <td><?= $score ?></td>
                        <td><?= $max_per_subject[$subject] ?? 10 ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="total">Total Score: <strong><?= $total_score ?></strong> / <?= $max_total ?>
                (<?= $percentage ?>%)</div>
            <div class="greeting"><?= $greeting ?></div><br>
            <a href="logout.php"><button class="logout-btn">Logout</button></a>
        </div>
    </body>

    </html>
    <?php
}
?>