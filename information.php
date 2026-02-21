<?php
session_start();
session_regenerate_id(true);
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>About Exam</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('p1.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: transparent;
            padding: 60px;
            border-radius: 12px;
        }

        h1 {
            text-align: center;
            color: white;
        }

        h2 {
            text-align: center;
            color: yellow;
        }

        .exam-info {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #009688;
            color: white;
        }

        td {
            color: white;
        }

        .summary {
            margin-top: 20px;
            font-size: 15px;
            font-weight: bold;
            color: white;
            text-align: center;
        }

        .next-btn {
            display: block;
            width: 160px;
            margin: 30px auto 0;
            padding: 12px;
            background-color: #009688;
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .next-btn:hover {
            background-color: #00796B;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .next-btn {
                width: 140px;
                font-size: 14px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['student_name']) ?>!</h2>
        <h1>About Your Exam</h1>
        <div class="exam-info">
            <div style="overflow-x: auto;">
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                        <th>Time (minutes)</th>
                        <th>No. of Questions</th>
                    </tr>
                    <tr>
                        <td>Mathematics</td>
                        <td>10</td>
                        <td>15</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Physics</td>
                        <td>10</td>
                        <td>15</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Chemistry</td>
                        <td>10</td>
                        <td>15</td>
                        <td>10</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="summary">
            Total Marks: 30 &nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            Total Time: 45 minutes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            Total questions: 30
        </div>

        <a href="instructions.php" class="next-btn" aria-label="Start Exam Page">Start Exam </a>
    </div>
    <script>
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                alert("Session expired due to inactivity.");
                window.location.href = "logout.php";
            }, 10 * 60 * 1000);
        }

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
    </script>
</body>

</html>