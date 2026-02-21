<?php
session_start();
if (!isset($_SESSION['exam_started'])) {
    $_SESSION['exam_started'] = false;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['update_time'])) {
    $student_id = $_SESSION['student_id'] ?? 0;
    $remaining = $_POST['remaining_time'] ?? 0;

    $stmt = $pdo->prepare("UPDATE students SET remaining_time = ? WHERE id = ?");
    $stmt->execute([$remaining, $student_id]);
    exit;
}
$studentStmt = $pdo->prepare("SELECT firstname, lastname, hallticket, submitted_exam FROM students WHERE id = ?");
$studentStmt->execute([$_SESSION['student_id']]);
$student = $studentStmt->fetch(PDO::FETCH_ASSOC);

if ($student && $student['submitted_exam'] == 1) {
    header("Location: submit.php");
    exit;
}

$subjects = ['Maths', 'Physics', 'Chemistry'];
if (isset($_GET['subject'])) {
    $_SESSION['current_subject'] = trim($_GET['subject']);
}

$selectedSubject = $_SESSION['current_subject'] ?? $subjects[0];
$selectedSubject = trim($selectedSubject);

$subjectQuery = $pdo->prepare("SELECT id, total_questions, name FROM subjects WHERE LOWER(name) = LOWER(?)");
$subjectQuery->execute([$selectedSubject]);
$subjectRow = $subjectQuery->fetch();

if (!$subjectRow)
    die("Invalid subject selected.");

$subject_id = $subjectRow['id'];
$question_limit = (int) $subjectRow['total_questions'];

$check = $pdo->prepare("SELECT COUNT(*) FROM answers WHERE student_id=? AND subject_id=?");
$check->execute([$_SESSION['student_id'], $subject_id]);
$countExisting = $check->fetchColumn();

if ($countExisting == 0) {
    $stmt = $pdo->prepare("SELECT id FROM questions WHERE subject_id=? ORDER BY q_no ASC");
    $stmt->execute([$subject_id]);
    $qids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    shuffle($qids);
    $qids = array_slice($qids, 0, $question_limit);

    $order = 1;
    foreach ($qids as $qid) {
        $ins = $pdo->prepare("INSERT INTO answers(student_id, subject_id, question_id, display_order) VALUES (?,?,?,?)");
        $ins->execute([$_SESSION['student_id'], $subject_id, $qid, $order++]);
    }
}


$stmt = $pdo->prepare("
    SELECT q.*, a.display_order
    FROM answers a
    JOIN questions q ON q.id = a.question_id
    WHERE a.student_id=? AND a.subject_id=?
    ORDER BY a.display_order ASC
");
$stmt->execute([$_SESSION['student_id'], $subject_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['ajax'])) {
    $subject = $_GET['subject'];
    $subjectQuery = $pdo->prepare("SELECT id FROM subjects WHERE LOWER(name)=LOWER(?)");
    $subjectQuery->execute([$subject]);
    $subjectRow = $subjectQuery->fetch();
    if (!$subjectRow)
        die(json_encode(['error' => 'Invalid subject']));

    $subject_id = $subjectRow['id'];

    $stmt = $pdo->prepare("
        SELECT q.*, a.display_order
        FROM answers a
        JOIN questions q ON q.id = a.question_id
        WHERE a.student_id=? AND a.subject_id=?
        ORDER BY a.display_order ASC
    ");
    $stmt->execute([$_SESSION['student_id'], $subject_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $answers = [];
    $answerStmt = $pdo->prepare("SELECT question_id, selected_option, review FROM answers WHERE student_id=? AND subject_id=?");
    $answerStmt->execute([$_SESSION['student_id'], $subject_id]);
    foreach ($answerStmt as $row) {
        $answers[$row['question_id']] = [
            'selected_option' => $row['selected_option'],
            'review' => (int) $row['review']
        ];
    }

    ob_start();
    foreach ($questions as $index => $q):
        $qid = $q['id'];
        $rev = $answers[$qid]['review'] ?? 2;
        $selected = $answers[$qid]['selected_option'] ?? '';

        if ($rev == 1) {
            $class = 'review';
        } elseif (!empty($selected)) {
            $class = 'answered';
        } else {
            $class = '';
        }

        ?>
        <button class="bubble <?= $class ?>" id="bubble-<?= $qid ?>" onclick="scrollToQuestion(<?= $qid ?>)">
            <?= $index + 1 ?>
        </button>
    <?php endforeach;
    $bubbles = ob_get_clean();

    ob_start();
    foreach ($questions as $index => $q):
        ?>
        <div class="question" data-qid="<?= $q['id'] ?>" style="<?= $index === 0 ? '' : 'display:none;' ?>">
            <h3>Question <?= $index + 1 ?>:</h3>
            <img src="<?= htmlspecialchars($q['question_image']) ?>" alt="Question">
            <div class="options">
                <?php foreach (['A', 'B', 'C', 'D'] as $opt):
                    $optionKey = 'option_' . strtolower($opt) . '_image';
                    $optionPath = $q[$optionKey] ?? null;
                    if ($optionPath && file_exists($optionPath)): ?>
                        <label>
                            <input type="radio" name="option_<?= $q['id'] ?>" value="<?= $opt ?>"
                                <?= (($answers[$q['id']]['selected_option'] ?? '') == $opt) ? 'checked' : '' ?>
                                onchange="selectOption(<?= $q['id'] ?>,'<?= $opt ?>',this)">
                            <img src="<?= htmlspecialchars($optionPath) ?>" data-option="<?= $opt ?>">
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="question-actions">
                <button type="button" class="mark-review" onclick="markAsReview(<?= $q['id'] ?>)">
                    Mark as Review
                </button>

                <?php if ($index < count($questions) - 1): ?>
                    <button type="button" class="next-btn" onclick="nextQuestion()">
                        Next
                    </button>
                <?php endif; ?>
            </div>

            <?php
            $isChemistry = (strtolower($subject) === 'chemistry');
            ?>

            <?php if ($isChemistry): ?>
                <div class="submit-container">
                    <button class="submit-btn" onclick="document.getElementById('confirmModal').style.display='flex'">
                        Submit Exam
                    </button>
                </div>
            <?php endif; ?>


        </div>
    <?php endforeach;

    echo json_encode([
        'content' => ob_get_clean(),
        'bubbles' => $bubbles
    ]);


    exit;
}

$_SESSION['student_name'] = $student ? $student['firstname'] . ' ' . $student['lastname'] : 'Unknown';
$_SESSION['hall_ticket'] = $student ? $student['hallticket'] : 'Not Found';

$answers = [];
$answerStmt = $pdo->prepare("SELECT question_id, selected_option, review FROM answers WHERE student_id=? AND subject_id=?");
$answerStmt->execute([$_SESSION['student_id'], $subject_id]);
foreach ($answerStmt as $row) {
    $answers[$row['question_id']] = [
        'selected_option' => $row['selected_option'],
        'review' => (int) $row['review']
    ];
}

$stmtRT = $pdo->prepare("SELECT remaining_time FROM students WHERE id = ?");
$stmtRT->execute([$_SESSION['student_id']]);
$remaining_time = $stmtRT->fetchColumn();
if ($remaining_time === null) {
    $remaining_time = 45 * 60;
    $stmtInit = $pdo->prepare("UPDATE students SET remaining_time = ? WHERE id = ?");
    $stmtInit->execute([$remaining_time, $_SESSION['student_id']]);
}
$answeredCount = 0;
foreach ($answers as $a) {
    if (!empty($a['selected_option']))
        $answeredCount++;
}
$unansweredCount = count($questions) - $answeredCount;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EAMCET Online Exam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: #005792;
            color: white;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-title {
            margin: 0;
            font-size: 26px;
        }

        .timer {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 18px;
            font-weight: bold;
            background-color: rgb(10, 19, 29);
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: #e0ecf4;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 18px;
            margin-top: 0;
        }

        .subject-links a {
            display: block;
            padding: 8px;
            margin: 5px 0;
            background: #d1e7ff;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .subject-links a.active {
            background: #005792;
            color: white;
        }

        .status-box {
            margin-top: 20px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .question {
            margin-bottom: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .question img {
            max-width: 100%;
            margin-bottom: 15px;
        }

        .options img {
            height: 50px;
            margin: 10px;
            border: 2px solid transparent;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.2s;
        }

        .submit-container {
            text-align: center;
            margin-top: 20px;
            z-index: 100;
        }

        .submit-btn {
            display: inline-block;
            padding: 12px 25px;
            background: #dc3545;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 40px;
        }

        #confirmModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }

        .modal-content h2 {
            margin-bottom: 20px;
        }

        .question-bubbles {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .bubble {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #ccc;
            color: #fff;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .bubble.answered {
            background-color: green;
        }

        .student-info {
            background: #ffffffcc;
            padding: 20px 10px;
            border-radius: 8px;
            text-align: right;
            font-size: 20px;
            color: #333;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin: 5px 0 0 5px;
        }

        .bubble.review {
            background-color: orange;
        }

        .options label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .options label input[type="radio"] {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .options label img {
            max-height: 60px;
            object-fit: contain;
            border: 2px solid transparent;
            border-radius: 6px;
            transition: 0.2s;
        }

        .question-actions button {
            padding: 8px 15px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 12px;
            transition: 0.2s;
        }

        .question-actions button.mark-review {
            background: orange;
            color: white;
        }

        .question-actions button.next-btn {
            background: #007bff;
            color: white;
        }

        .question-actions button.save-btn {
            background: green;
            color: white;
        }
    </style>
</head>

<body>
    <div id="startExamModal" style="
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:#000000cc;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;
    font-size:22px;
    z-index:9999;
">
        <div style="background:#222;padding:30px;border-radius:8px;text-align:center;">
            <p style="margin-bottom:20px;">Click <strong>Start Exam</strong> to begin </p>
            <button id="startExamBtn"
                style="padding:10px 30px;font-size:20px;background:#4CAF50;color:white;border:none;border-radius:5px;cursor:pointer;">
                Start Exam
            </button>
        </div>
    </div>

    <header>
        <div class="header-title">Vignan Examination Portal</div>
        <div class="timer" id="timer"></div>
    </header>
    <div class="student-info">
        <strong>Name: <?= htmlspecialchars($_SESSION['student_name']) ?> <br>
            Hall Ticket: <?= htmlspecialchars($_SESSION['hall_ticket']) ?></strong>
    </div>
    <div class="container">
        <div class="sidebar">
            <h2>Subjects</h2>
            <div class="subject-links">
                <?php foreach ($subjects as $sub): ?>
                    <a href="#" class="subject-link" data-subject="<?= $sub ?>"><?= $sub ?></a>
                <?php endforeach; ?>
            </div>
            <div class="status-box">
                Answered: <?= $answeredCount ?><br>
                Unanswered: <?= $unansweredCount ?>
            </div>
            <div class="question-bubbles">
                <?php foreach ($questions as $index => $q): ?>
                    <?php
                    $qid = $q['id'];
                    $class = '';

                    if (isset($answers[$qid])) {
                        if (!empty($answers[$qid]['review'])) {
                            $class = 'review';
                        } elseif (!empty($answers[$qid]['selected_option'])) {
                            $class = 'answered';
                        }
                    }
                    ?>
                    <button class="bubble <?= $class ?>" id="bubble-<?= $qid ?>" onclick="scrollToQuestion(<?= $qid ?>)">
                        <?= $index + 1 ?>
                    </button>
                <?php endforeach; ?>
            </div>

        </div>
        <div class="content">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question" data-qid="<?= $q['id'] ?>" style="<?= $index === 0 ? '' : 'display:none;' ?>">
                    <h3 style="margin-bottom: 10px;">Question <?= $index + 1 ?>:</h3>
                    <img src="<?= htmlspecialchars($q['question_image']) ?>" alt="Question">
                    <div class="options">
                        <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                            <?php
                            $optionKey = 'option_' . strtolower($opt) . '_image';
                            $optionPath = $q[$optionKey] ?? null;
                            ?>
                            <div style="margin-bottom: 10px;">
                                <?php if ($optionPath && file_exists($optionPath)): ?>
                                    <label style="display: block; cursor: pointer; margin-bottom: 10px;">
                                        <input type="radio" name="option_<?= $q['id'] ?>" value="<?= $opt ?>"
                                            <?= (($answers[$q['id']]['selected_option'] ?? '') == $opt) ? 'checked' : '' ?>
                                            onchange="selectOption(<?= $q['id'] ?>, '<?= $opt ?>', this)">

                                        <img src="<?= htmlspecialchars($optionPath) ?>"
                                            class="option-img <?= ($answers[$q['id']] ?? '') == $opt ? 'selected' : '' ?>"
                                            data-option="<?= $opt ?>">
                                    </label>
                                <?php else: ?>
                                    <div style="color:red;">Image not found for Option <?= $opt ?> (Q<?= $q['id'] ?>)</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="question-actions" style="margin-top: 15px;">
                        <button type="button" class="mark-review" onclick="markAsReview(<?= $q['id'] ?>)">
                            Mark as Review
                        </button>
                        <?php if ($index < count($questions) - 1): ?>
                            <button type="button" class="next-btn" onclick="nextQuestion()">Next</button>
                        <?php endif; ?>

                    </div>
                    <?php
                    $isChemistry = (strtolower($selectedSubject) === 'chemistry');
                    ?>

                    <?php if ($isChemistry): ?>
                        <div class="submit-container">
                            <button class="submit-btn" onclick="document.getElementById('confirmModal').style.display='flex'">
                                Submit Exam
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>



    <div id="confirmModal">
        <div class="modal-content">
            <h2>Confirm Submission</h2>
            <p>Are you sure you want to submit the exam?</p>
            <form action="submit.php" method="post">
                <button type="submit" class="submit-btn">Yes, Submit</button>
                <button type="button" class="submit-btn" style="background:#6c757d;"
                    onclick="document.getElementById('confirmModal').style.display='none'">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        let examStarted = <?= $_SESSION['exam_started'] ? 'true' : 'false' ?>;
        let fullscreenExitAttempts = 0;
        let currentIndex = 0;
        let questionsEls = document.querySelectorAll('.question');


        const startModal = document.getElementById('startExamModal');
        const startBtn = document.getElementById('startExamBtn');

        async function enterFullscreen() {
            try {
                if (document.documentElement.requestFullscreen) await document.documentElement.requestFullscreen();
                else if (document.documentElement.webkitRequestFullscreen) await document.documentElement.webkitRequestFullscreen();
                else if (document.documentElement.msRequestFullscreen) await document.documentElement.msRequestFullscreen();
            } catch (e) {
                console.log("Fullscreen error:", e);
            }
        }
        async function ensureFullscreen() {
            if (!document.fullscreenElement) {
                await enterFullscreen();
            }
        }

        checkFullscreenOnLoad = ensureFullscreen;
        checkFullscreenOnLoad();


        if (!examStarted) {
            startModal.style.display = 'flex';
            startBtn.addEventListener('click', async () => {
                await enterFullscreen();
                startModal.style.display = 'none';
                examStarted = true;

                fetch('exam_start.php', { method: 'POST' })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            console.log("Exam officially started");
                        }
                    })
                    .catch(console.error);
            });
        } else {
            startModal.style.display = 'none';
            enterFullscreen();
        }


        document.addEventListener("fullscreenchange", async () => {
            if (!examStarted) return;
            if (document.fullscreenElement) return;

            fullscreenExitAttempts++;
            localStorage.setItem("fullscreenExitAttempts", fullscreenExitAttempts);

            const warningModal = document.getElementById('fullscreenWarningModal');
            const message = document.getElementById('fullscreenMessage');
            const btn = document.getElementById('fullscreenModalBtn');
            const autoSubmitModal = document.getElementById('autoSubmitModal');

            if (fullscreenExitAttempts === 1) {
                message.innerText = "You exited fullscreen! Please return to fullscreen to continue the exam.";
                warningModal.style.display = "flex";
                btn.onclick = async () => {
                    warningModal.style.display = "none";
                    await enterFullscreen();
                };
            } else {
                autoSubmitModal.style.display = "flex";
                let countdown = 5;
                const countdownEl = document.getElementById('autoSubmitCountdown');
                const interval = setInterval(() => {
                    countdown--;
                    countdownEl.innerText = `Submitting in ${countdown} seconds...`;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        window.location.href = "submit.php";
                    }
                }, 1000);
            }
        });
        document.addEventListener("visibilitychange", () => {
            if (!examStarted) return;
            if (document.hidden) {
                window.location.href = "submit.php";
            }
        });

        let timeLeft = <?= $remaining_time ?>;
        let counter = 0;
        const timerEl = document.getElementById("timer");

        function updateTimer() {
            if (timeLeft <= 0) {
                alert("Time is up! Auto-submitting exam.");
                window.location.href = "submit.php";
                return;
            }
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            timerEl.innerText = `Time Left: ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            counter++;
            if (counter >= 10) {
                counter = 0;
                fetch("exam.php?update_time=1", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "remaining_time=" + timeLeft
                }).catch(e => console.log("Time update failed:", e));
            }
            timeLeft--;
        }
        setInterval(updateTimer, 1000);
        updateTimer();

        function showQuestion(index) {
            questionsEls.forEach((q, i) => q.style.display = i === index ? 'block' : 'none');
            currentIndex = index;
            updateCounts();
        }

        function nextQuestion() {
            if (currentIndex < questionsEls.length - 1) showQuestion(currentIndex + 1);
        }

        function scrollToQuestion(qid) {
            questionsEls.forEach((q, i) => {
                if (q.dataset.qid == qid) showQuestion(i);
            });
        }

        function markBubbleAnswered(qid) {
            const bubble = document.getElementById('bubble-' + qid);
            if (bubble) { bubble.classList.remove('review'); bubble.classList.add('answered'); }
        }

        function updateCounts() {
            const total = questionsEls.length;
            let answered = 0, review = 0;

            document.querySelectorAll('.bubble').forEach(b => {
                if (b.classList.contains('answered')) answered++;
                else if (b.classList.contains('review')) review++;
            });

            document.querySelector(".status-box").innerHTML =
                "Answered: " + answered +
                "<br>Marked for Review: " + review +
                "<br>Unanswered: " + (total - answered - review);
        }

        function selectOption(qid, opt, inputEl) {
            document.querySelectorAll(`[data-qid='${qid}'] .option-img`).forEach(el => el.classList.remove('selected'));
            const selImg = document.querySelector(`[data-qid='${qid}'] img[data-option='${opt}']`);
            if (selImg) selImg.classList.add('selected');
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "answers.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`question_id=${qid}&answer=${opt}&review=0`);
            const bubble = document.getElementById('bubble-' + qid);
            if (bubble) {
                bubble.classList.remove('review');
                bubble.classList.add('answered');
            }

            updateCounts();
        }


        function markAsReview(qid) {
            const selected = document.querySelector(`input[name="option_${qid}"]:checked`);
            const selectedOption = selected ? selected.value : '';

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "answers.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`question_id=${qid}&answer=${selectedOption}&review=1`);

            const bubble = document.getElementById("bubble-" + qid);
            if (bubble) {
                bubble.classList.remove("answered");
                bubble.classList.add("review");
            }

            showSaveButtonFor(qid, true);
            updateCounts();
        }


        function saveReviewed(qid) {
            const selected = document.querySelector(`input[name="option_${qid}"]:checked`);
            const selectedOption = selected ? selected.value : '';

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "answers.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`question_id=${qid}&answer=${selectedOption}&review=0`);

            const bubble = document.getElementById('bubble-' + qid);
            if (bubble) {
                bubble.classList.remove('review');
                bubble.classList.add('answered');
            }

            showSaveButtonFor(qid, false);
            updateCounts();
        }

        function showSaveButtonFor(qid, show) {
            const qEl = document.querySelector(`.question[data-qid='${qid}']`);
            if (!qEl) return;

            let actions = qEl.querySelector('.question-actions');
            let saveBtn = qEl.querySelector(`#save-btn-${qid}`);

            if (show) {
                if (!saveBtn) {
                    saveBtn = document.createElement('button');
                    saveBtn.type = 'button';
                    saveBtn.id = 'save-btn-' + qid;
                    saveBtn.innerText = 'Save';
                    saveBtn.className = 'save-btn';
                    saveBtn.onclick = () => saveReviewed(qid);
                    actions.insertBefore(saveBtn, actions.querySelector('.next-btn') || null);
                } else {
                    saveBtn.style.display = 'inline-block';
                }
            } else if (saveBtn) {
                saveBtn.style.display = 'none';
            }
        }

        function attachQuestionEvents() {
            questionsEls = document.querySelectorAll('.question');

            document.querySelectorAll('.bubble').forEach(b => {
                b.addEventListener('click', () => scrollToQuestion(b.id.replace('bubble-', '')));
            });

            document.querySelectorAll('.question input[type=radio]').forEach(input => {
                input.addEventListener('change', () => selectOption(
                    parseInt(input.name.replace("option_", "")), input.value, input
                ));
            });

            document.querySelectorAll('.bubble.review').forEach(b => {
                showSaveButtonFor(b.id.replace('bubble-', ''), true);
            });

        }

        attachQuestionEvents();
        updateCounts();

        document.querySelectorAll('.subject-link').forEach(link => {
            link.addEventListener('click', async e => {
                e.preventDefault();
                const subject = link.dataset.subject;

                try {
                    const res = await fetch(`exam.php?ajax=1&subject=${subject}`);
                    const data = await res.json();
                    if (data.error) { alert(data.error); return; }

                    document.querySelector('.content').innerHTML = data.content;
                    document.querySelector('.question-bubbles').innerHTML = data.bubbles;

                    document.querySelectorAll('.subject-link').forEach(a => a.classList.remove('active'));
                    link.classList.add('active');

                    attachQuestionEvents();
                    currentIndex = 0;
                    showQuestion(0);
                    updateCounts();
                    ensureFullscreen();
                } catch (err) {
                    console.error("Failed to switch subject:", err);
                }
            });
        });
    </script>


    <div id="fullscreenWarningModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.8); z-index:99999;
            align-items:center; justify-content:center;">

        <div style="background:#fff; color:#000; padding:30px;
                border-radius:8px; text-align:center; width:350px;">
            <h2 style="margin-bottom:15px;">Exam Alert</h2>
            <p id="fullscreenMessage" style="font-size:18px;"></p>

            <button id="fullscreenModalBtn" style="margin-top:20px; padding:10px 25px; font-size:18px;
                       background:#007bff; color:#fff; border:none;
                       border-radius:6px; cursor:pointer;">
                OK
            </button>
        </div>

    </div>
    <div id="reviewWarningModal" style="
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;">
        <div style="background: white; padding: 25px; border-radius: 8px; text-align: center;">
            <h3>Please select an option before marking as review.</h3>
            <button onclick="document.getElementById('reviewWarningModal').style.display='none'"
                style="margin-top: 15px; padding: 8px 20px; background:#007bff; color:white; border:none; border-radius:6px;">
                OK
            </button>
        </div>
    </div>
    <div id="autoSubmitModal" style="
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 99999;
">
        <div
            style="background: #fff; color: #000; padding: 30px; border-radius: 8px; text-align: center; width: 400px;">
            <h2>Exam Alert</h2>
            <p>You exited fullscreen. Your exam will be submitted automatically.</p>
            <p id="autoSubmitCountdown">Submitting in 5 seconds...</p>
        </div>
    </div>

</body>

</html>