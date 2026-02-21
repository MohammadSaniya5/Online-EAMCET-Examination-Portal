<?php
session_start();
require 'db.php';

$timeout_duration = 1800;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: admin.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

$login_error = "";
if (!isset($_SESSION['admin_logged_in'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stored_hash = password_hash('vignan', PASSWORD_DEFAULT);
        if (password_verify($_POST['password'], $stored_hash)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin.php");
            exit;
        } else {
            $login_error = "Incorrect password. Please try again.";
        }
    }
    ?>
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
            color: rgb(42,145,229);
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
            background: rgb(75,146,221);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error-msg { color: red; font-size: 14px; }
    </style>

    <div class="login-box">
        <form method="post">
            <h2>Admin Login</h2>
            <?php if ($login_error) echo "<div class='error-msg'>$login_error</div>"; ?>
            <input type="password" name="password" placeholder="Enter Admin Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
    <?php
    exit;
}

$active_tab = $_GET['tab'] ?? 'registered';
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<link rel="icon" href="favicon.ico">
<style>
    body { background-image:url('p1.jpg'); background-size:cover; padding:20px; font-family:Arial; }
    .tabs { display:flex; justify-content:center; gap:10px; margin-bottom:20px; }
    .tab {
        padding:10px 18px;
        background:#ddd;
        cursor:pointer;
        border-radius:5px;
    }
    .tab.active { background:#0d6efd; color:white; }
    h2{text-align: center;}
    section { display:none; background:white; padding:20px; border-radius:8px; }
    section.active { display:block; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th,td { border:1px solid #aaa; padding:8px; text-align:center; }
    .btn { padding:5px 12px; border:none; background:#0d6efd; color:white; border-radius:4px; text-decoration: none; }
    .btn-danger { background:#dc3545; }
</style>
</head>
<body>

<div style="text-align:right;">
    <form method="post"><button name="logout" class="btn-danger btn">Logout</button></form>
</div>

<h1 style="text-align:center;color:white;text-shadow:0 0 4px black;">Admin Dashboard</h1>

<div class="tabs">
    <div class="tab <?= $active_tab=='registered'?'active':'' ?>" onclick="location.href='admin.php?tab=registered'">Registered Students</div>
    <div class="tab <?= $active_tab=='results'?'active':'' ?>" onclick="location.href='admin.php?tab=results'">Results</div>
    <div class="tab <?= $active_tab=='session_report'?'active':'' ?>" onclick="location.href='admin.php?tab=session_report'">Day-wise Report</div>
</div>
 
<section class="<?= $active_tab=='registered'?'active':'' ?>">
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
            <th>Exam Date</th>
            <th>Exam Time</th>
            <th>Session</th>
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
                <td>{$row['exam_date']}</td>
                <td>{$row['exam_time']}</td>
                <td>{$row['session']}</td>
            </tr>";

            $sno++;
        }
        ?>
    </tbody>
</table>
</section>
<section class="<?= $active_tab=='results'?'active':'' ?>">
<h2>Results</h2>

<form method="get" style="margin-bottom:15px; text-align:right;">
    <input type="hidden" name="tab" value="results">
    
    <label>Date:</label>
    <input type="date" name="date" value="<?= $_GET['date'] ?? date('Y-m-d') ?>">

    <label>Session:</label>
    <select name="session">
    <option value="ALL" <?= ($_GET['session']??'ALL')=='ALL'?'selected':'' ?>>ALL</option>
    <option value="FN" <?= ($_GET['session']??'ALL')=='FN'?'selected':'' ?>>FN</option>
    <option value="AN" <?= ($_GET['session']??'ALL')=='AN'?'selected':'' ?>>AN</option>
</select>

    <button class="btn no-print">Show</button>
</form>

<table>
<tr><th>S.No</th><th>Name</th><th>Maths</th><th>Physics</th><th>Chemistry</th><th>Total</th></tr>
<?php
$selSession = $_GET['session'] ?? 'ALL';   
$selDate = $_GET['date'] ?? date('Y-m-d');  

$sessionCondition = ($selSession == 'ALL') ? '1' : "s.session='$selSession'";
$dateCondition = "s.exam_date='$selDate'";

$q = $pdo->query("
SELECT 
    s.id,
    CONCAT(s.firstname,' ',s.lastname) AS name,
    SUM(CASE WHEN sub.name='Maths' THEN (q.correct_option=a.selected_option) ELSE 0 END) AS maths,
    SUM(CASE WHEN sub.name='Physics' THEN (q.correct_option=a.selected_option) ELSE 0 END) AS physics,
    SUM(CASE WHEN sub.name='Chemistry' THEN (q.correct_option=a.selected_option) ELSE 0 END) AS chemistry
FROM students s
LEFT JOIN answers a ON s.id=a.student_id
LEFT JOIN questions q ON a.question_id=q.id
LEFT JOIN subjects sub ON q.subject_id=sub.id
WHERE $sessionCondition AND $dateCondition
GROUP BY s.id
ORDER BY ( 
    SUM(CASE WHEN sub.name='Maths' THEN (q.correct_option=a.selected_option) ELSE 0 END) +
    SUM(CASE WHEN sub.name='Physics' THEN (q.correct_option=a.selected_option) ELSE 0 END) +
    SUM(CASE WHEN sub.name='Chemistry' THEN (q.correct_option=a.selected_option) ELSE 0 END)
) DESC
");


$i=1;
foreach($q as $r){
    $total = $r['maths'] + $r['physics'] + $r['chemistry'];
    echo "<tr>
        <td>$i</td>
        <td>{$r['name']}</td>
        <td>{$r['maths']}</td>
        <td>{$r['physics']}</td>
        <td>{$r['chemistry']}</td>
        <td><b>$total</b></td>
    </tr>";
    $i++;
}
?>
</table>
</section>
<section class="<?= $active_tab=='session_report'?'active':'' ?>">
<h2>Day-wise Report</h2>

<form method="get" style="margin-bottom:15px; text-align:right;">
    <input type="hidden" name="tab" value="session_report">
    <label>Date:</label>
    <input type="date" name="date" value="<?= $_GET['date'] ?? date('Y-m-d') ?>">
    <label>Session:</label>
    <select name="session">
        <option value="FN" <?= ($_GET['session']??'FN')=='FN'?'selected':'' ?>>FN</option>
        <option value="AN" <?= ($_GET['session']??'FN')=='AN'?'selected':'' ?>>AN</option>
    </select>
    <button class="btn no-print">Show</button>
</form>

<?php
$selDate = $_GET['date'] ?? date('Y-m-d');
$selSession = $_GET['session'] ?? 'FN';

$stmt = $pdo->prepare("SELECT * FROM students WHERE exam_date=? AND session=? ORDER BY firstname");
$stmt->execute([$selDate, $selSession]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
<tr>
    <th>S.No</th><th>Hallticket</th><th>Name</th><th>Email</th><th>Phone</th>
    <th>Exam Time</th><th>Session</th>
</tr>
<?php
if(empty($data)){
    echo "<tr><td colspan='7'>No students found.</td></tr>";
} else {
    $i=1;
    foreach($data as $st){
        $name = htmlspecialchars($st['firstname'].' '.$st['lastname']);
        echo "<tr>
            <td>$i</td>
            <td>{$st['hallticket']}</td>
            <td>$name</td>
            <td>{$st['email']}</td>
            <td>{$st['phone']}</td>
            <td>{$st['exam_time']}</td>
            <td>{$st['session']}</td>
        </tr>";
        $i++;
    }
}
?>
</table>
</section>
<div style="text-align:center; margin:20px;">
    <button onclick="printActiveSection()" class="btn no-print">Print</button>
</div>
<script>
function printActiveSection() {
    const activeSection = document.querySelector('section.active');
    if (!activeSection) return;

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print</title>');
    
    printWindow.document.write('<style>\
        body { font-family: Arial; } \
        h2 { text-align: center; } \
        table { width: 100%; border-collapse: collapse; margin-top: 10px; } \
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; } \
        th { background: #ddd; } \
        .no-print { display: none; } \
    </style>');

    printWindow.document.write('</head><body>');
    printWindow.document.write(activeSection.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>


</body>

</html>
