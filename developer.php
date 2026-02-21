<?php
session_start();
require 'db.php';
$status_msg = '';
$timeout_duration = 1800;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: developer.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: developer.php");
    exit;
}
$login_error = '';
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stored_hash = password_hash('vignan', PASSWORD_DEFAULT);
        if (password_verify($_POST['password'], $stored_hash)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: developer.php");
            exit;
        } else {
            $login_error = "Incorrect password. Please try again.";
        }
    }
    echo '
    <style>
        body {
            background-image: url("p1.jpg");
            background-repeat: no-repeat;   
            background-size: cover;  
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color:rgb(42, 145, 229);
        }
        .login-box input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-box button {
            padding: 13px 20px;
            background:rgb(75, 146, 221);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error-msg {
            color: red;
            font-size: 14px;
        }
    </style>

    <div class="login-box">
        <form method="post">
            <h2>Admin Login</h2>';
    if (!empty($login_error)) {
        echo '<div class="error-msg">' . htmlspecialchars($login_error) . '</div>';
    }
    echo '  <input type="password" name="password" placeholder="Enter Admin Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>';
    exit;
}

if (isset($_GET['delete_student'])) {
    $pdo->prepare("DELETE FROM answers WHERE student_id = ?")->execute([$_GET['delete_student']]);
    $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$_GET['delete_student']]);
    $_SESSION['student_msg'] = "Student deleted successfully.";
    $_SESSION['active_tab'] = "students";
    header("Location: developer.php?tab=students");
    exit;
}
if (isset($_GET['edit_subject'])) {
    $edit_sub_id = (int) $_GET['edit_subject'];
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$edit_sub_id]);
    $edit_subject = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['edit_subject_id'] = $edit_sub_id;
    $_SESSION['active_tab'] = "subjects";
    header("Location: developer.php?tab=subjects");
    exit;
}

if (isset($_GET['delete_subject'])) {
    $id = $_GET['delete_subject'];
    $count = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE subject_id = ?");
    $count->execute([$id]);
    if ($count->fetchColumn() > 0) {
        echo "Cannot delete subject: it has questions.";
    } else {
        $pdo->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['subject_msg'] = "Subject deleted successfully.";
        $_SESSION['active_tab'] = "subjects";
        header("Location: developer.php?tab=subjects");
        exit;
    }
}
if (isset($_SESSION['edit_question_id']) && $_GET['tab'] === 'questions') {
    $edit_id = $_SESSION['edit_question_id'];
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_question = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['edit_question'])) {
    $_SESSION['edit_question_id'] = (int) $_GET['edit_question'];
    $_SESSION['active_tab'] = "questions";
    header("Location: developer.php?tab=questions");
    exit;
}

if (isset($_GET['reset']) && $_GET['reset'] == 'questions') {
    header("Location: developer.php");
    exit;
}
if (isset($_GET['delete_question'])) {
    $pdo->prepare("DELETE FROM questions WHERE id = ?")->execute([$_GET['delete_question']]);

    $_SESSION['question_msg'] = "Question deleted successfully.";
    $_SESSION['active_tab'] = "questions";
    header("Location: developer.php?tab=questions");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_subject'])) {
    $pdo->prepare("INSERT INTO subjects (name, total_questions, marks_per_question, time_minutes) VALUES (?, ?, ?, ?)")
        ->execute([$_POST['new_subject'], $_POST['total_questions'], $_POST['marks_per_question'], $_POST['time_minutes']]);
    $_SESSION['subject_msg'] = "Subject added successfully.";
    $_SESSION['active_tab'] = "subjects";
    header("Location: developer.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subject'])) {
    $id = $_POST['subject_id'];
    $name = $_POST['name'];
    $total_questions = $_POST['total_questions'];
    $marks = $_POST['marks_per_question'];
    $time = $_POST['time_minutes'];

    try {
        $stmt = $pdo->prepare("
            UPDATE subjects 
            SET name=?, total_questions=?, marks_per_question=?, time_minutes=? 
            WHERE id=?
        ");
        $success = $stmt->execute([$name, $total_questions, $marks, $time, $id]);

        if ($success) {
            $_SESSION['subject_msg'] = "Subject updated successfully!";
        } else {
            $_SESSION['status_msg'] = "Failed to update subject.";
        }
    } catch (Exception $e) {
        $_SESSION['status_msg'] = "Error: " . $e->getMessage();
    }

    $_SESSION['active_tab'] = "subjects";
    header("Location: developer.php?tab=subjects");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_question'])) {
    $id = $_POST['edit_question_id'];
    $subject_id = $_POST['subject_id'];
    $correct_option = $_POST['correct_option'];


    $cur = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
    $cur->execute([$id]);
    $existing = $cur->fetch(PDO::FETCH_ASSOC);


    $subject_stmt = $pdo->prepare("SELECT name FROM subjects WHERE id = ?");
    $subject_stmt->execute([$subject_id]);
    $subject_name = strtolower($subject_stmt->fetchColumn());

    $uploadDir = 'questions/' . $subject_name . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }


    $fields = ['question_image', 'option_a_image', 'option_b_image', 'option_c_image', 'option_d_image'];
    $updates = [];

    foreach ($fields as $f) {
        if (!empty($_FILES[$f]['name'])) {
            $filename = basename($_FILES[$f]['name']);
            $path = $uploadDir . $filename;
            move_uploaded_file($_FILES[$f]['tmp_name'], $path);
            $updates[$f] = $path;
        } else {

            $updates[$f] = $existing[$f];
        }
    }


    $stmt = $pdo->prepare("UPDATE questions 
        SET subject_id=?, question_image=?, option_a_image=?, option_b_image=?, option_c_image=?, option_d_image=?, correct_option=? 
        WHERE id=?");
    $stmt->execute([
        $subject_id,
        $updates['question_image'],
        $updates['option_a_image'],
        $updates['option_b_image'],
        $updates['option_c_image'],
        $updates['option_d_image'],
        $correct_option,
        $id
    ]);

    $_SESSION['edit_question_id'] = $id;
    $_SESSION['question_msg'] = "Question updated successfully!";
    $_SESSION['active_tab'] = "questions";
    header("Location: developer.php?tab=questions&status=updated");
    exit;

}
$active_tab = $_GET['tab'] ?? $_SESSION['active_tab'] ?? 'students';
unset($_SESSION['active_tab']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['question_image'])) {
    $subject_id = $_POST['subject_id'];


    $subject_stmt = $pdo->prepare("SELECT name FROM subjects WHERE id = ?");
    $subject_stmt->execute([$subject_id]);
    $subject_name = strtolower($subject_stmt->fetchColumn());

    $uploadDir = 'questions/' . $subject_name . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }


    $questionImage = $uploadDir . basename($_FILES['question_image']['name']);
    $optionA = $uploadDir . basename($_FILES['option_a_image']['name']);
    $optionB = $uploadDir . basename($_FILES['option_b_image']['name']);
    $optionC = $uploadDir . basename($_FILES['option_c_image']['name']);
    $optionD = $uploadDir . basename($_FILES['option_d_image']['name']);


    $paths = [$questionImage, $optionA, $optionB, $optionC, $optionD];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            $status_msg = "One or more image files already exist. Please delete the existing question before adding.";
            break;
        }
    }

    function isValidImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        return in_array($file['type'], $allowedTypes);
    }

    foreach (['question_image', 'option_a_image', 'option_b_image', 'option_c_image', 'option_d_image'] as $field) {
        if (!isValidImage($_FILES[$field])) {
            echo "<div style='color:red;'>Invalid file type for $field. Only JPG, PNG, or GIF allowed.</div>";
            return;
        }
        if ($_FILES[$field]['size'] > 2 * 1024 * 1024) {
            echo "<div style='color:red;'>$field is too large. Max 2MB allowed.</div>";
            return;
        }
    }
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE subject_id = ? AND question_image = ?");
    $checkStmt->execute([$subject_id, $questionImage]);
    $questionExists = $checkStmt->fetchColumn();
    if ($questionExists > 0) {
        $status_msg = "This question already exists for the selected subject. Please upload a different one.";
    } else {
        move_uploaded_file($_FILES['question_image']['tmp_name'], $questionImage);
        move_uploaded_file($_FILES['option_a_image']['tmp_name'], $optionA);
        move_uploaded_file($_FILES['option_b_image']['tmp_name'], $optionB);
        move_uploaded_file($_FILES['option_c_image']['tmp_name'], $optionC);
        move_uploaded_file($_FILES['option_d_image']['tmp_name'], $optionD);

        $stmt = $pdo->prepare("INSERT INTO questions (subject_id, question_image, option_a_image, option_b_image, option_c_image, option_d_image, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $subject_id,
            $questionImage,
            $optionA,
            $optionB,
            $optionC,
            $optionD,
            $_POST['correct_option']
        ]);
        $question_msg = "Question added successfully!";
        header("Location: developer.php?tab=questions&status=added");
        exit();
    }
}



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['bulk_mode'])) {

    $mode = $_POST['bulk_mode'];
    $extractPath = "bulk_temp/";

    $summary = [
        1 => ["name" => "Maths", "added" => 0, "updated" => 0, "skipped" => []],
        2 => ["name" => "Physics", "added" => 0, "updated" => 0, "skipped" => []],
        3 => ["name" => "Chemistry", "added" => 0, "updated" => 0, "skipped" => []],
    ];

    if (file_exists($extractPath)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extractPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        rmdir($extractPath);
    }
    mkdir($extractPath, 0777, true);

    $zipTmp = $_FILES['zip_file']['tmp_name'];
    $zip = new ZipArchive;

    if ($zip->open($zipTmp) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
    } else {
        $bulkStatus = "<b>ZIP extraction failed.</b>";
        return;
    }

    $subjectMap = [
        "maths" => 1,
        "physics" => 2,
        "chemistry" => 3
    ];

    $exts = ["png", "jpg", "jpeg", "webp"];

    function findFile($folder, $name, $exts)
    {
        foreach ($exts as $ex) {
            $path = $folder . $name . "." . $ex;
            if (file_exists($path))
                return $path;
        }
        return false;
    }

    foreach ($subjectMap as $folderName => $subjectId) {

        $subjectFolder = $extractPath . $folderName . "/";

        if (!is_dir($subjectFolder)) {
            $summary[$subjectId]["skipped"][] = "Folder '$folderName' missing";
            continue;
        }

        $files = scandir($subjectFolder);

        foreach ($files as $file) {

            if (!preg_match("/^q([0-9]+)\.(png|jpg|jpeg|webp)$/i", $file, $m))
                continue;
            $qNo = intval($m[1]);

            $qImg = findFile($subjectFolder, "q{$qNo}", $exts);
            $aImg = findFile($subjectFolder, "q{$qNo}_a", $exts);
            $bImg = findFile($subjectFolder, "q{$qNo}_b", $exts);
            $cImg = findFile($subjectFolder, "q{$qNo}_c", $exts);
            $dImg = findFile($subjectFolder, "q{$qNo}_d", $exts);

            $ansTxt = $subjectFolder . "q{$qNo}_ans.txt";

            if (!$qImg || !$aImg || !$bImg || !$cImg || !$dImg || !file_exists($ansTxt)) {
                $summary[$subjectId]["skipped"][] = "q{$qNo} (missing file)";
                continue;
            }

            $correct = trim(file_get_contents($ansTxt));

            $saveDir = "questions/$folderName/";
            if (!is_dir($saveDir))
                mkdir($saveDir, 0777, true);

            $qNew = $saveDir . basename($qImg);
            $aNew = $saveDir . basename($aImg);
            $bNew = $saveDir . basename($bImg);
            $cNew = $saveDir . basename($cImg);
            $dNew = $saveDir . basename($dImg);

            copy($qImg, $qNew);
            copy($aImg, $aNew);
            copy($bImg, $bNew);
            copy($cImg, $cNew);
            copy($dImg, $dNew);

            $check = $pdo->prepare("SELECT id FROM questions WHERE subject_id = ? AND q_no = ?");
            $check->execute([$subjectId, $qNo]);

            if ($check->rowCount() > 0) {

                if ($mode === "update") {
                    $qid = $check->fetchColumn();

                    $update = $pdo->prepare("
                        UPDATE questions SET 
                            question_image=?, option_a_image=?, option_b_image=?, 
                            option_c_image=?, option_d_image=?, correct_option=? 
                        WHERE id=?
                    ");
                    $update->execute([$qNew, $aNew, $bNew, $cNew, $dNew, $correct, $qid]);

                    $summary[$subjectId]["updated"]++;

                } else {
                    $summary[$subjectId]["skipped"][] = "q{$qNo} (already exists)";
                }

            } else {

                if ($mode === "add") {
                    $insert = $pdo->prepare("
                        INSERT INTO questions (subject_id, q_no, question_image, option_a_image, option_b_image, option_c_image, option_d_image, correct_option)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $insert->execute([$subjectId, $qNo, $qNew, $aNew, $bNew, $cNew, $dNew, $correct]);

                    $summary[$subjectId]["added"]++;

                } else {
                    $summary[$subjectId]["skipped"][] = "q{$qNo} (not found in DB)";
                }
            }
        }
    }

    $output = "<h3>Bulk Upload Summary</h3><hr>";

    foreach ($summary as $sid => $s) {
        $output .= "<b>{$s['name']}:</b><br>";
        $output .= "✔ Added: {$s['added']}<br>";
        $output .= "✔ Updated: {$s['updated']}<br>";

        if (!empty($s['skipped'])) {
            $output .= "⚠ Skipped:<br><ul>";
            foreach ($s['skipped'] as $msg) {
                $output .= "<li>$msg</li>";
            }
            $output .= "</ul>";
        }

        $output .= "<hr>";
    }

    $bulkStatus = $output;
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("p1.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: rgb(230, 233, 39);
        }

        h2 {
            text-align: center;
        }

        .tabs {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 5px;
        }


        .tab {
            padding: 10px 10px;
            margin: 0 5px;
            background: #ddd;
            display: inline-block;
            cursor: pointer;
            border-radius: 5px;
        }

        .tab.active {
            background: #007BFF;
            color: white;
        }

        section {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        section.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 8px;
            text-align: center;
        }

        form input,
        form select {
            margin: 5px;
            padding: 6px;
            width: 200px;
        }

        .btn {
            padding: 6px 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            margin: 5px;
        }

        .btn-danger {
            background: #dc3545;
            text-decoration: none;
        }

        .status-box {
            background: #f4f4f4;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            section.active,
            section.active * {
                visibility: visible;
            }

            section.active {
                position: absolute;
                top: 0;
                left: 0;
            }

            .tabs,
            button,
            form {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <body>

        <div style="text-align:right; margin-bottom:20px;">
            <form method="post" style="display:inline;">
                <button type="submit" name="logout"
                    style="padding:6px 12px; background:#dc3545; color:white; border:none; border-radius:4px;">
                    Logout
                </button>
            </form>
        </div>
        <h1>Admin Dashboard</h1>

        <div class="tabs">
            <div class="tab active" onclick="showTab('students')">Manage Students</div>
            <div class="tab " onclick="showTab('allstudents')">Registered Students</div>
            <div class="tab" onclick="showTab('subjects')">Manage Subjects</div>
            <div class="tab" onclick="showTab('questions')">Manage Questions</div>
            <div class="tab" onclick="showTab('bulkquestions')">Manage Bulk Questions</div>
            <div class="tab" onclick="showTab('review')">Review Students Answers</div>
            <div class="tab" onclick="showTab('results')">View Results</div>
            <div class="tab <?= $active_tab === 'session_report' ? 'active' : '' ?>"
                onclick="location.href='developer.php?tab=session_report'">
                Day-wise Report
            </div>

        </div>

        <section id="allstudents" class="<?= $active_tab === 'allstudents' ? 'active' : '' ?>">
            <h2>Registered Students</h2>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Hall Ticket</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM students ORDER BY id ASC");
                    $sno = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $fullname = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                        echo "<tr>
                <td>{$sno}</td>
                <td>{$row['hallticket']}</td>
                <td>{$fullname}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
              </tr>";
                        $sno++;
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <section id="students" class="<?= $active_tab === 'students' ? 'active' : '' ?>">
            <h2>Manage Students</h2>
            <?php if (!empty($_SESSION['student_msg'])): ?>
                <p style="color: green; font-weight: bold;">
                    <?= $_SESSION['student_msg'];
                    unset($_SESSION['student_msg']); ?>
                </p>
            <?php endif; ?>

            <?php
            $students = $pdo->query("SELECT * FROM students")->fetchAll();
            $sno = 1;
            echo "<table><tr><th>ID</th><th>Full Name</th><th>Email</th><th>Hall Ticket</th><th>Action</th></tr>";
            foreach ($students as $stu) {
                echo "<tr>
        <td>" . htmlspecialchars($sno) . "</td>
        <td>" . htmlspecialchars($stu['firstname'] . ' ' . $stu['lastname']) . "</td>
        <td>" . htmlspecialchars($stu['email']) . "</td>
        <td>" . htmlspecialchars($stu['hallticket']) . "</td>
        <td><a href='?delete_student=" . $stu['id'] . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete</a></td>
        </tr>";
                $sno++;
            }
            echo "</table>";
            ?>
        </section>
        <section id="subjects" class="<?= $active_tab === 'subjects' ? 'active' : '' ?>">
            <h2>Manage Subjects</h2>
            <?php if (!empty($_SESSION['subject_msg'])): ?>
                <p style="color: green; font-weight: bold;">
                    <?= $_SESSION['subject_msg'];
                    unset($_SESSION['subject_msg']); ?>
                </p>
            <?php endif; ?>

            <form method="post">
                <input type="text" name="new_subject" placeholder="Subject Name" required>
                <input type="number" name="total_questions" placeholder="Total Questions" required>
                <input type="number" name="marks_per_question" placeholder="Marks per Question" required>
                <input type="number" name="time_minutes" placeholder="Time (Minutes)" required>
                <button type="submit" class="btn">Add Subject</button>
            </form>
            <?php
            $subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();
            echo "<table><tr><th>ID</th><th>Name</th><th>Total Qs</th><th>Marks/Q</th><th>Time</th><th>Action</th></tr>";
            foreach ($subjects as $sub) {
                echo "<tr>
        <td>" . htmlspecialchars($sub['id']) . "</td>
        <td>" . htmlspecialchars($sub['name']) . "</td>
        <td>" . htmlspecialchars($sub['total_questions']) . "</td>
        <td>" . htmlspecialchars($sub['marks_per_question']) . "</td>
        <td>" . htmlspecialchars($sub['time_minutes']) . "</td>
        <td><a href='?tab=subjects&edit_subject=" . $sub['id'] . "' class='btn'>Edit</a>
        <a href='?tab=subjects&delete_subject=" . $sub['id'] . "' class='btn btn-danger' 
            onclick=\"return confirm('Delete this subject?');\">Delete</a>
        </td>
    </tr>";
            }
            echo "</table>";
            ?>
            <?php if (isset($_SESSION['edit_subject_id'])):
                $id = $_SESSION['edit_subject_id'];
                $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
                $stmt->execute([$id]);
                $sub = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <hr>
                <h3>Edit Subject</h3>

                <form method="post">
                    <input type="hidden" name="subject_id" value="<?= $sub['id'] ?>">

                    Subject Name:<br>
                    <input type="text" name="name" value="<?= htmlspecialchars($sub['name']) ?>" required><br><br>

                    Total Questions:<br>
                    <input type="number" name="total_questions" value="<?= htmlspecialchars($sub['total_questions']) ?>"
                        required><br><br>

                    Marks per Question:<br>
                    <input type="number" name="marks_per_question"
                        value="<?= htmlspecialchars($sub['marks_per_question']) ?>" required><br><br>

                    Time (Minutes):<br>
                    <input type="number" name="time_minutes" value="<?= htmlspecialchars($sub['time_minutes']) ?>"
                        required><br><br>

                    <button type="submit" name="update_subject" class="btn">Update Subject</button>
                    <a href="developer.php?tab=subjects" class="btn btn-danger">Cancel</a>
                </form>

                <?php unset($_SESSION['edit_subject_id']); endif; ?>

        </section>
        <section id="questions" class="<?= $active_tab === 'questions' ? 'active' : '' ?>">
            <h2>Manage Questions</h2>

            <?php if (!empty($_SESSION['question_msg'])): ?>
                <p style="color: green; font-weight: bold;">
                    <?= $_SESSION['question_msg'];
                    unset($_SESSION['question_msg']); ?>
                </p>
            <?php endif; ?>


            <form method="post" enctype="multipart/form-data">
                <select name="subject_id" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                Upload Question Image: <input type="file" name="question_image" accept="image/*" required><br><br>
                Option A: <input type="file" name="option_a_image" accept="image/*" required><br><br>
                Option B: <input type="file" name="option_b_image" accept="image/*" required><br><br>
                Option C: <input type="file" name="option_c_image" accept="image/*" required><br><br>
                Option D: <input type="file" name="option_d_image" accept="image/*" required><br><br>

                <select name="correct_option" required>
                    <option value="">Correct Option</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select><br><br>

                <button type="submit" class="btn">Add Question</button>
            </form>

            <?php
            if (isset($_GET['edit_question']) || isset($_SESSION['edit_question_id'])) {
                $edit_id = isset($_GET['edit_question']) ? (int) $_GET['edit_question'] : (int) $_SESSION['edit_question_id'];

                $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
                $stmt->execute([$edit_id]);
                $edit_question = $stmt->fetch();

                if ($edit_question):
                    ?>
                    <hr>
                    <h3>Edit Question ID <?= htmlspecialchars($edit_question['id']) ?></h3>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="edit_question_id" value="<?= $edit_question['id'] ?>">

                        <select name="subject_id" required>
                            <?php foreach ($subjects as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $s['id'] == $edit_question['subject_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>

                        Question Image: <input type="file" name="question_image">
                        (Current: <?= basename($edit_question['question_image']) ?>)<br><br>

                        Option A: <input type="file" name="option_a_image">
                        (<?= basename($edit_question['option_a_image']) ?>)<br><br>

                        Option B: <input type="file" name="option_b_image">
                        (<?= basename($edit_question['option_b_image']) ?>)<br><br>

                        Option C: <input type="file" name="option_c_image">
                        (<?= basename($edit_question['option_c_image']) ?>)<br><br>

                        Option D: <input type="file" name="option_d_image">
                        (<?= basename($edit_question['option_d_image']) ?>)<br><br>

                        <select name="correct_option" required>
                            <option value="">Correct Option</option>
                            <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                                <option value="<?= $opt ?>" <?= $opt == $edit_question['correct_option'] ? 'selected' : '' ?>>
                                    <?= $opt ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>

                        <button type="submit" name="update_question" class="btn">Update Question</button>
                    </form>
                    <?php
                endif;
            }
            ?>


            <table>
                <tr>
                    <th>ID</th>
                    <th>Q.No</th>
                    <th>Subject</th>
                    <th>Question</th>
                    <th>Option A</th>
                    <th>Option B</th>
                    <th>Option C</th>
                    <th>Option D</th>
                    <th>Correct Option</th>
                    <th>Action</th>
                </tr>

                <?php
                $questions = $pdo->query("SELECT q.*, s.name AS subject FROM questions q JOIN subjects s ON q.subject_id = s.id")->fetchAll();
                foreach ($questions as $q):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($q['id']) ?></td>
                        <td><?= htmlspecialchars($q['q_no']) ?></td>
                        <td><?= htmlspecialchars($q['subject']) ?></td>
                        <td><img src="<?= htmlspecialchars($q['question_image']) . '?v=' . time() ?>" width="100"></td>
                        <td><img src="<?= htmlspecialchars($q['option_a_image']) . '?v=' . time() ?>" width="100"></td>
                        <td><img src="<?= htmlspecialchars($q['option_b_image']) . '?v=' . time() ?>" width="100"></td>
                        <td><img src="<?= htmlspecialchars($q['option_c_image']) . '?v=' . time() ?>" width="100"></td>
                        <td><img src="<?= htmlspecialchars($q['option_d_image']) . '?v=' . time() ?>" width="100"></td>
                        <td><?= htmlspecialchars($q['correct_option']) ?></td>
                        <td>
                            <a href="?tab=questions&edit_question=<?= $q['id'] ?>" class="btn">Edit</a>
                            <a href="?tab=questions&delete_question=<?= $q['id'] ?>" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <?php $isActive = ($active_tab === 'bulkquestions') ? 'active' : ''; ?>
        <section id="bulkquestions" class="<?php echo $isActive; ?>">
            <h2>Bulk Upload Questions</h2>

            <?php if (!empty($bulkStatus))
                echo $bulkStatus; ?>
            <div style="border:1px solid #ccc;padding:20px;margin-bottom:20px;background:#f9f9f9;">
                <h3 style="margin-bottom:5px;">Add Bulk Questions</h3>
                <p style="margin-top:0;color:#555;">
                    Upload new questions for a subject.
                    <b>No existing questions will be overwritten.</b>
                </p>

                <form action="developer.php?tab=bulkquestions" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="bulk_mode" value="add">

                    <label><strong>Select ZIP File:</strong></label><br>
                    <input type="file" name="zip_file" accept=".zip" required><br><br>

                    <button type="submit" class="btn">Upload (Add New Questions)</button>
                </form>

                <h4 style="margin-top:15px;">ZIP Folder Structure (Correct Format)</h4>

                <pre style="background:#eee;padding:10px;border-radius:5px;">
maths/
    q1.png          ← Question image
    q1_a.png        ← Option A
    q1_b.png        ← Option B
    q1_c.png        ← Option C
    q1_d.png        ← Option D
    q1_ans.txt      ← Correct answer (A/B/C/D)

physics/
chemistry/
</pre>

                <p style="margin-top:5px;color:#777;">
                    ➤ Each subject folder is optional.
                    ➤ You can upload for one or multiple subjects in a single ZIP.
                    ➤ q number (q1, q2, q3 ...) becomes the Question Number.
                </p>
            </div>

            <div style="border:1px solid #ccc;padding:20px;margin-bottom:20px;background:#f9f9f9;">
                <h3 style="margin-bottom:5px;">Update Bulk Questions</h3>
                <p style="margin-top:0;color:#555;">
                    Replace existing questions with new images/answers.
                    <b>Only q numbers already in the database will be updated.</b>
                </p>

                <form action="developer.php?tab=bulkquestions" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="bulk_mode" value="update">

                    <label><strong>Select ZIP File:</strong></label><br>
                    <input type="file" name="zip_file" accept=".zip" required><br><br>

                    <button type="submit" class="btn">Upload (Update Existing Questions)</button>
                </form>

                <p style="margin-top:10px;color:#777;">
                    ➤ q number must already exist in the database.
                    ➤ Missing files or invalid names will be skipped.
                    ➤ Only matching q numbers will be replaced.
                </p>
            </div>

        </section>


        <section id="review" class="<?= $active_tab === 'review' ? 'active' : '' ?>">
            <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                <input type="hidden" name="tab" value="review">
                <label for="student_id">Select Student:</label>
                <select name="student_id" id="student_id" required onchange="this.form.submit()">
                    <option value="">-- Choose Student --</option>
                    <?php
                    $students = $pdo->query("SELECT id, firstname, lastname, hallticket FROM students")->fetchAll();
                    foreach ($students as $stu) {
                        $selected = isset($_GET['student_id']) && $_GET['student_id'] == $stu['id'] ? 'selected' : '';
                        echo "<option value='{$stu['id']}' $selected>{$stu['hallticket']} - {$stu['firstname']} {$stu['lastname']}</option>";
                    }
                    ?>
                </select>

                <?php if (isset($_GET['student_id']) && $_GET['student_id']): ?>
                    <a href="review.php?admin_review=1&student_id=<?= htmlspecialchars($_GET['student_id']) ?>"
                        target="_blank">
                        <button type="button"
                            style="padding: 8px 16px; background-color: #1976d2; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Review Answers
                        </button>
                    </a>
                <?php endif; ?>
            </form>
        </section>
        <section id="results">
            <h2>View Results</h2>
            <table>
                <tr>
                    <th>S.no</th>
                    <th>Student</th>
                    <th>Maths (80 marks)</th>
                    <th>Physics (40 marks)</th>
                    <th>Chemistry (40 marks)</th>
                    <th>Total Score (160 marks)</th>
                </tr>
                <?php
                $stmt = $pdo->query("
            SELECT 
                s.id AS student_id,
                CONCAT(s.firstname, ' ', s.lastname) AS student_name,
                SUM(CASE WHEN sub.name = 'Maths' THEN (q.correct_option = a.selected_option) ELSE 0 END) AS maths_score,
                SUM(CASE WHEN sub.name = 'Physics' THEN (q.correct_option = a.selected_option) ELSE 0 END) AS physics_score,
                SUM(CASE WHEN sub.name = 'Chemistry' THEN (q.correct_option = a.selected_option) ELSE 0 END) AS chemistry_score
            FROM students s
            LEFT JOIN answers a ON s.id = a.student_id
            LEFT JOIN questions q ON a.question_id = q.id
            LEFT JOIN subjects sub ON q.subject_id = sub.id
            GROUP BY s.id
        ");
                $sno = 1;
                foreach ($stmt as $row) {
                    $total = $row['maths_score'] + $row['physics_score'] + $row['chemistry_score'];
                    echo "<tr>
                <td>" . htmlspecialchars($sno) . "</td>
        <td>" . htmlspecialchars($row['student_name']) . "</td>
        <td>" . htmlspecialchars($row['maths_score']) . "</td>
        <td>" . htmlspecialchars($row['physics_score']) . "</td>
        <td>" . htmlspecialchars($row['chemistry_score']) . "</td>
        <td><strong>" . $total . "</strong></td>
    </tr>";
                    $sno++;
                }
                ?>
            </table>
        </section>
        <section id="session_report" class="<?= $active_tab === 'session_report' ? 'active' : '' ?>">
            <h2>Day-wise Report</h2>

            <form method="get" style="display:flex; gap:10px; align-items:center; justify-content:flex-end;">
                <input type="hidden" name="tab" value="session_report">

                <label>Select Date:</label>
                <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? date('Y-m-d')) ?>" required>

                <label>Select Session:</label>
                <select name="session">
                    <option value="FN" <?= (($_GET['session'] ?? 'FN') === 'FN') ? 'selected' : '' ?>>FN</option>
                    <option value="AN" <?= (($_GET['session'] ?? '') === 'AN') ? 'selected' : '' ?>>AN</option>
                </select>

                <button type="submit" class="btn">Show</button>
            </form>

            <?php
            $selDate = $_GET['date'] ?? date('Y-m-d');
            $selSession = $_GET['session'] ?? 'FN';

            $stmt = $pdo->prepare("SELECT * FROM students WHERE exam_date=? AND session=? ORDER BY firstname, lastname");
            $stmt->execute([$selDate, $selSession]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <h3>Showing <?= htmlspecialchars($selSession) ?> students for <?= htmlspecialchars($selDate) ?></h3>

            <table>
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Hallticket</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Exam Time</th>
                        <th>Session</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($students)) {
                        echo "<tr><td colspan='7'>No students found.</td></tr>";
                    } else {
                        $i = 1;
                        foreach ($students as $stu) {
                            $name = htmlspecialchars($stu['firstname'] . ' ' . $stu['lastname']);
                            echo "<tr>
                        <td>{$i}</td>
                        <td>" . htmlspecialchars($stu['hallticket']) . "</td>
                        <td>{$name}</td>
                        <td>" . htmlspecialchars($stu['email']) . "</td>
                        <td>" . htmlspecialchars($stu['phone']) . "</td>
                        <td>" . htmlspecialchars($stu['exam_time']) . "</td>
                        <td>" . htmlspecialchars($stu['session']) . "</td>
                    </tr>";
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <div style="text-align:center; margin-top: 30px;">
            <button onclick="window.print()" style="padding:6px 12px;">Print</button>

        </div>
        <script>
            function showTab(tabId) {
                document.querySelectorAll('section').forEach(section => {
                    section.classList.remove('active');
                });
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
                document.querySelector('.tab[onclick="showTab(\'' + tabId + '\')"]').classList.add('active');
            }
            function confirmDelete(type, id) {
                if (confirm(`Are you sure you want to delete this ${type}?`)) {
                    window.location.href = `?delete_${type}=${id}`;
                }
            }
            window.onload = function () {
                const urlParams = new URLSearchParams(window.location.search);
                const activeTab = urlParams.get('tab') || 'students';
                showTab(activeTab);
            }; 
        </script>
    </body>

</html>