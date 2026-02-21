<?php
require 'db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $hallticket = strtoupper(htmlspecialchars(trim($_POST['hallticket'])));
  $password = htmlspecialchars(trim($_POST['password']));

  if (empty($hallticket) || empty($password)) {
    $message = 'Please fill in both fields.';
  } else {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE hallticket = ?");
    $stmt->execute([$hallticket]);

    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch();
      if (password_verify($password, $user['password'])) {
        session_start();
        session_regenerate_id(true);
        $_SESSION['student_id'] = $user['id'];
        $_SESSION['student_name'] = $user['firstname'] . ' ' . $user['lastname'];
        $_SESSION['logged_in'] = true;

        if ($user['submitted_exam'] == 1) {
          header("Location: submit.php");
        } else {
          header("Location: information.php");
        }
        exit();
      } else {
        $message = 'Incorrect password.';
      }
    } else {
      $message = 'Hallticket number not found.';
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Student Login</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <style>
    html {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      font-family: 'Inter', sans-serif;
      background: url('vgnt.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      overflow-y: scroll;
      flex-direction: column;
    }

    body {
      margin: 0;
      padding: 0;
      overflow-y: auto;
      overflow-x: hidden;
      display: block !important;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(14, 5, 5, 0.5);
      z-index: 0;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0px 30px;
      position: relative;
      z-index: 1;
      flex-wrap: nowrap;
      width: 100%;
      height: 100px;
      overflow: hidden;
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
    }

    .left-header {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }

    .left-header.logo-wrapper {
      gap: 0 !important;
    }

    .logo-wrapper img {
      max-height: 170px;
      width: auto;
      margin-right: 0;
    }


    .texts {
      display: flex;
      flex-direction: column;
      font-size: 0.85rem;
      font-weight: bold;
      color: white;
      text-shadow: 1px 1px 3px black;
      line-height: 1.3;
    }

    nav {
      display: flex;
      gap: 40px;
      align-items: center;
      margin-left: 0 !important;
      justify-content: flex-end !important;

    }

    nav a {
      text-decoration: none;
      color: white;
      font-size: 1.3rem;
      font-weight: 600;
      text-shadow: 1px 1px 3px black;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #e0e0e0;
    }

    .vertical-line {
      width: 1.5px;
      background-color: white;
      height: 70px;
      margin-left: 0;
      margin-right: 5px;
    }

    .page-content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }


    .container {
      padding: 27px;
      max-width: 400px;
      border-radius: 10px;
      width: 90%;
      margin-top: 40px;
    }

    .container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: yellow;
      text-shadow: 3px 3px 6px black;
    }

    .container input {
      width: 95%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid rgb(18, 66, 139);
      background-color: rgba(233, 225, 225, 0.8);
      font-size: 16px;
    }

    .container button {
      background-color: #005fa3;
      color: white;
      padding: 12px;
      border-radius: 8px;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
      border: none;
    }

    .message {
      text-align: center;
      margin-bottom: 19px;
      font-weight: bold;
      color: rgb(251, 21, 21);
    }

    p {
      text-align: center;
      color: rgb(248, 247, 241);
      font-size: 18px;
      font-weight: bold;
    }

    footer {
      text-align: center;
      padding: 20px 15px;
      font-size: 1.1rem;
      color: #ffffff;
      text-shadow: 1px 1px 3px black;
      font-weight: bold;
      position: relative;
      z-index: 1;
      margin-top: 170px;
    }

    .footer a {
      color: #ffcc00;
      text-decoration: none;
    }

    @media (max-width: 768px) {
      header {
        flex-direction: column;
        gap: 10px;
      }

      nav {
        justify-content: center;
      }

      .logo-wrapper {
        flex-direction: column;
        align-items: center;
      }

      .texts {
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <div class="overlay"></div>

  <header>
    <div class="left-header logo-wrapper">
      <img src="logo1.png" alt="Vignan Logo" />
      <div class="vertical-line"></div>
      <div class="texts">
        <span>NBA</span>
        <span>AICTE</span>
        <span>NAAC A+</span>
        <span>AUTONOMOUS</span>
      </div>
    </div>

    <nav>
      <a href="index.php">Home</a>
      <a href="register.php">Registration</a>
      <a href="login.php">Login</a>
      <a href="contact.php">Contact Us</a>
      <a href="about.php">About Us</a>
    </nav>
  </header>

  <section style="position: relative; z-index: 1; width: 100%; max-width: 100%;  padding-bottom: 10px;">
    <div class="page-content">
      <div class="container">
        <h2>Student Login</h2>
        <?php if ($message): ?>
          <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
          <input type="text" name="hallticket" placeholder="Hallticket Number" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
          <div class="footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
          </div>

        </form>
      </div>
    </div>

    <footer>
      &copy;2026
    </footer>
  </section>
</body>

</html>