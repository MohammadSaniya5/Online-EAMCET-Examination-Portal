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
    <title>Exam Instructions</title>
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
            max-width: 900px;
            margin: 60px auto;
            background: transparent;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        h2 {
            text-align: center;
            color: yellow;
            text-shadow: 2px 2px 5px black;
            margin-bottom: 25px;
            font-size: 28px;
        }

        ol {
            font-size: 18px;
            line-height: 1.8;
            color: white;
            max-width: 750px;
            margin: 0 auto;
            padding-left: 60px;
        }

        .btn {
            display: block;
            width: 180px;
            margin: 40px auto 0;
            padding: 12px;
            background-color: #e67e22;
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #d35400;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 25px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-buttons button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .confirm-btn {
            background-color: #27ae60;
            color: white;
        }

        .cancel-btn {
            background-color: #c0392b;
            color: white;
        }

        .modal:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Please Read the Instructions Carefully</h2>
        <ol>
            <li>This is an online examination; ensure a stable internet connection.</li>
            <li>Once you begin, the exam timer cannot be paused.</li>
            <li>Leaving or refreshing the page may result in automatic submission.</li>
            <li>Each question carries 1 mark. No negative marking.</li>
            <li>Use of calculators, mobile phones, or any other devices is strictly prohibited.</li>
            <li>Your webcam will be monitored. Do not look away from the screen.</li>
            <li>If any suspicious activity is detected, your exam will be terminated.</li>
            <li>You must complete all sections within the allotted time.</li>
            <li>Only one attempt is allowed per student.</li>
            <li>Clicking the start button means you agree to all terms above.</li>
        </ol>

        <button class="btn" onclick="showModal()" aria-label="Start Exam Button">Start Exam</button>
    </div>
    <div id="confirmModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle"
        onclick="hideModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <h3 id="modalTitle">Are you sure you want to start the exam?</h3>
            <div class="modal-buttons">
                <button class="confirm-btn" onclick="startExam()">Yes, Start</button>
                <button class="cancel-btn" onclick="hideModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById("confirmModal").style.display = "block";
        }

        function hideModal() {
            document.getElementById("confirmModal").style.display = "none";
        }

        function startExam() { 
            window.location.href = "time.php";
        }

    </script>
</body>

</html>