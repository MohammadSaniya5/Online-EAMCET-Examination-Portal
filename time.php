<?php
session_start();
require 'db.php';
if (!isset($_SESSION['student_id'])) {
  header("Location: login.php");
  exit;
}
$student_id = $_SESSION['student_id'];
$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
$exam_date = $now->format('Y-m-d');
$exam_time = $now->format('H:i:s');
$session = ((int) $now->format('H') < 12) ? 'FN' : 'AN';
$stmt = $pdo->prepare("UPDATE students SET exam_date=?, exam_time=?, session=? WHERE id=?");
$stmt->execute([$exam_date, $exam_time, $session, $student_id]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <title>Exam Starting Countdown</title>
  <style>
    body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-image: url('p1.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      font-family: Arial, sans-serif;
      margin-top: -50px;
    }

    h2 {
      color: white;
      text-shadow: 2px 2px 5px black;
      margin-bottom: 20px;
    }

    .timer-container {
      text-align: center;
      position: relative;
    }

    .timer-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 2.5em;
      font-weight: bold;
      color: rgb(235, 235, 74);
    }

    svg {
      transform: rotate(-90deg);
    }

    circle {
      transition: stroke-dashoffset 1s linear;
    }

    #progress {
      stroke: rgb(236, 208, 51);
      stroke-linecap: round;
    }

    @media (max-width: 480px) {
      .timer-text {
        font-size: 1.8em;
      }

      svg {
        width: 150px;
        height: 150px;
      }
    }
  </style>
</head>

<body>
  <h2> All the Best <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h2>
  <p style="font-size: 1.2em; margin-bottom: 20px; color: white;">Get ready — your exam starts in:</p>
  <div class="timer-container">
    <svg width="200" height="200">
      <circle cx="100" cy="100" r="90" stroke="#ddd" stroke-width="15" fill="none" />
      <circle id="progress" cx="100" cy="100" r="90" stroke-width="15" fill="none" />
    </svg>
    <div class="timer-text" id="timer" aria-live="assertive">10</div>
  </div>

  <script>
    const timerEl = document.getElementById('timer');
    const progressCircle = document.getElementById('progress');
    const radius = 90;
    const circumference = 2 * Math.PI * radius;

    progressCircle.style.strokeDasharray = circumference;
    progressCircle.style.strokeDashoffset = 0;

    let count = 10;

    const interval = setInterval(() => {
      count--;
      timerEl.textContent = count;

      const offset = circumference * (1 - count / 10);
      progressCircle.style.strokeDashoffset = offset;

      if (count <= 0) {
        clearInterval(interval);
        window.location.href = "exam.php";
      }
    }, 1000);

  </script>

</body>

</html>