<?php
require 'db.php';
session_start();

$student_id = $_SESSION['student_id'] ?? null;
if (isset($_GET['admin_review']) && isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
}
$question_order_map = $_SESSION['question_order'] ?? [];

if (!$student_id) {
    header("Location: login.php");
    exit;
}

$subjectStmt = $pdo->query("SELECT id, name FROM subjects");
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if (isset($_GET['admin_review'])): ?>
    <div class="admin-review-back" style="margin: 20px 0;">
        <a href="developer.php?tab=review" style="
        padding: 10px 20px;
        background-color: #1976d2;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    ">← Back to Review Tab</a>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Review Your Answers</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('p1.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            padding: 30px;
            color: #333;
        }

        .review-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            color: #0d47a1;
            margin-bottom: 40px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 2px;
        }

        h2 {
            color: #0d47a1;
            margin-top: 40px;
        }

        .question {
            margin-bottom: 20px;
            padding: 15px;
            border-left: 5px solid #ccc;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .option {
            padding: 8px 12px;
            margin: 4px 0;
            border-radius: 4px;
            display: inline-block;
            border: none;
            background: #fff;
        }

        .selected {
            font-weight: bold;
            text-decoration: none;
        }

        .answer-message {
            margin-top: 8px;
            font-weight: bold;
        }

        .correct-msg {
            color: #388e3c;
        }

        .wrong-msg {
            color: #c62828;
        }

        @media print {
            .admin-review-back {
                display: none !important;
            }
        }

        @media print {

            .logout-btn,
            .back-btn,
            button,
            .back-btn1 {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="review-container">
        <h1>Answers Review</h1>
        <?php foreach ($subjects as $subject): ?>
            <h2><?= htmlspecialchars($subject['name']) ?></h2>
            <?php
            $subject_id = $subject['id'];
            $questions = [];

            if (isset($question_order_map[$subject_id]) && !empty($question_order_map[$subject_id])) {

                $question_ids = $question_order_map[$subject_id];

                foreach ($question_ids as $qid) {
                    $stmt = $pdo->prepare("
        SELECT q.id AS qid, q.question_image, q.option_a_image, q.option_b_image, q.option_c_image, q.option_d_image,
               q.correct_option, a.selected_option, a.display_order
        FROM answers a
        JOIN questions q ON a.question_id = q.id
        WHERE a.student_id = ? AND q.id = ?
    ");
                    $stmt->execute([$student_id, $qid]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $questions[] = $row;
                    }
                }

            } else {
                $stmt = $pdo->prepare("
                SELECT q.*, a.selected_option, a.review, a.display_order
FROM answers a
JOIN questions q ON q.id = a.question_id
WHERE a.student_id = ? 
  AND a.subject_id = ?
  AND a.selected_option IS NOT NULL
  AND a.selected_option != ''
ORDER BY a.display_order ASC

            ");
                $stmt->execute([$student_id, $subject_id]);
                $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if (empty($questions)) {
                echo "<p>No answers submitted for this subject.</p>";
                continue;
            }

            foreach ($questions as $index => $q):
                $options = [
                    'A' => $q['option_a_image'],
                    'B' => $q['option_b_image'],
                    'C' => $q['option_c_image'],
                    'D' => $q['option_d_image'],
                ];
                $is_correct = ($q['selected_option'] === $q['correct_option']);
                ?>
                <div class="question">
                    <strong>Question <?= intval($q['display_order']) ?>:</strong><br>
                    <img src="<?= htmlspecialchars($q['question_image']) ?>" alt="Question Image"
                        style="max-width:100%; height:auto;"><br><br>

                    <?php foreach ($options as $key => $img):
                        $classes = 'option';
                        $symbol = '';
                        if ($key === $q['correct_option'])
                            $symbol = ' ✅';
                        if ($key === $q['selected_option'] && $q['selected_option'] !== $q['correct_option'])
                            $symbol = ' ❌';
                        if ($key === $q['selected_option'])
                            $classes .= ' selected';
                        ?>
                        <div class="<?= $classes ?>">
                            <?= $key ?>. <img src="<?= htmlspecialchars($img) ?>" alt="Option <?= $key ?>" style="height: 60px;">
                            <?= $symbol ?>
                        </div><br>
                    <?php endforeach; ?>

                    <div class="answer-message <?= $is_correct ? 'correct-msg' : 'wrong-msg' ?>">
                        <?= $is_correct ? 'Correct answer!' : 'Wrong answer. Correct option: ' . htmlspecialchars($q['correct_option']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <center>
            <div><button onclick="window.print()">Print</button></div>
        </center>

        <?php if (isset($_GET['admin_review'])): ?>
            <div class="back-btn1" style="margin: 20px 0; text-align:center;">
                <a href="developer.php?tab=review" style="
                padding: 10px 20px;
                background-color: #1976d2;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                display: inline-block;
            "> Back </a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>