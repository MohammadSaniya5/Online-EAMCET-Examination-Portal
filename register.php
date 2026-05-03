<?php
require 'db.php';
$message = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firstname = htmlspecialchars(trim($_POST['firstname']));
  $lastname = htmlspecialchars(trim($_POST['lastname']));
  $hallticket = strtoupper(trim($_POST['hallticket']));
  $email = htmlspecialchars(trim($_POST['email']));
  $phone = trim($_POST['phone']);
  $address = htmlspecialchars(trim($_POST['address']));
  $password = trim($_POST['password']);
  $confirm_password = trim($_POST['confirm_password']);
  if (empty($firstname) || empty($lastname) || empty($hallticket) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($confirm_password)) {
    $message = 'Please fill all fields.';
  } elseif ($password !== $confirm_password) {
    $message = 'Passwords do not match.';
  } elseif (strlen($password) < 6) {
    $message = 'Password must be at least 6 characters.';
  } elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@(gmail|yahoo|outlook)\.com$/", $email)) {
    $message = 'Please enter a valid email address.';
  } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
    $message = 'Please enter a valid phone number (10 digits).';
  } elseif (!preg_match("/^[A-Z0-9]{10}$/", strtoupper($hallticket))) {
    $message = 'Invalid Hall Ticket Number. It should be 10 characters (letters and digits only).';
  } else {
    $stmt = $pdo->prepare("SELECT id FROM students WHERE hallticket = ? OR email = ?");
    $stmt->execute([$hallticket, $email]);

    if ($stmt->rowCount() > 0) {
      $message = 'Hall Ticket Number or Email already exists.';
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO students (firstname, lastname, hallticket, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
      if ($stmt->execute([$firstname, $lastname, $hallticket, $email, $phone, $address, $hashed_password])) {
        $success = true;
        $message = 'Registration successful!';
        $firstname = $lastname = $hallticket = $email = $phone = $address = $password = $confirm_password = '';
      } else {
        $message = 'Registration failed. Please try again.';
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Student Registration</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      overflow-x: hidden !important;
      font-family: 'Inter', sans-serif;
      background: url('vgnt.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      display: flex;
      flex-direction: column;
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
      margin-top: -25px;
    }

    h1 {
      color: yellow;
      text-align: center;
      font-size: 28px;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 2px solid rgb(6, 104, 169);
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 16px;
      outline: none;
      box-sizing: border-box;
      background-color: rgba(233, 225, 225, 0.8);
      transition: all 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus {
      border-color: #0077cc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 119, 204, 0.2);
    }

    button {
      width: 95%;
      padding: 12px;
      background-color: #005fa3;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }

    .message {
      text-align: center;
      margin-bottom: 15px;
      color: rgb(247, 248, 249);
      font-weight: bold;
    }

    p {
      text-align: center;
      color: rgb(248, 247, 241);
      font-size: 18px;
      font-weight: bold;
    }

    footer {
      text-align: center;
      position: relative;
      z-index: 1;
      padding: 20px 15px;
      font-size: 1.1rem;
      color: #ffffff;
      text-shadow: 1px 1px 3px black;
      font-weight: bold;
    }

    .footer a {
      color: #ffcc00;
      text-decoration: none;
    }

    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .popup-box {
      background: #ffffff;
      padding: 25px 30px;
      border-radius: 12px;
      text-align: center;
      border: 2px solid #000;
      width: 320px;
      color: #000;
      animation: popupScale 0.3s ease;
    }

    .popup-box h2 {
      margin-bottom: 10px;
      color: #0a7a0a;
    }

    .popup-box button {
      margin-top: 15px;
      padding: 8px 22px;
      background-color: #005fa3;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
    }

    .popup-box button:hover {
      background-color: #004b82;
    }

    html.popup-open,
    body.popup-open {
      overflow: hidden;
      height: 100%;
    }

    @keyframes popupScale {
      from {
        transform: scale(0.7);
        opacity: 0;
      }

      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .menu-toggle {
      display: none;
      font-size: 2rem;
      cursor: pointer;
      color: white;
    }

    .close-btn {
      display: none;
    }

    .menu-toggle.hide {
      display: none !important;
    }

    header {
      z-index: 2000;
      position: relative;
    }

    nav {
      z-index: 3000;
    }

    .menu-toggle {
      z-index: 4000;
      position: relative;
    }

    .popup-overlay {
      z-index: 5000;
    }

    @media (max-width: 992px) {

      body,
      html {
        margin: 0;
        padding: 0;
        overflow-x: hidden !important;
        font-family: 'Inter', sans-serif;

        background: url('vgnt.jpg') no-repeat center center;
        background-size: cover;
        background-attachment: scroll;

        color: white;
        display: flex;
        flex-direction: column;
      }

      * {
        box-sizing: border-box;
        max-width: 100%;
      }

      header {
        padding: 10px 20px;
        height: auto;
      }

      .vertical-line {
        display: none;
      }

      .logo-wrapper img {
        max-height: 75px;
      }

      .menu-toggle {
        display: block;
        margin-left: auto;
        font-size: 2rem;
        cursor: pointer;
        z-index: 3100;
        position: relative;
      }

      nav {
        position: fixed;
        top: 0;
        right: -280px;
        width: 260px;
        height: 100vh;
        background: rgba(20, 10, 10, 0.95);
        backdrop-filter: blur(18px);
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding-left: 30px;
        padding-top: 20px;
        gap: 25px;
        transition: right 0.35s ease;
        z-index: 3000;
      }

      nav.active {
        right: 0;
      }

      nav a {
        font-size: 1.2rem;
        padding: 10px 0;
        text-align: center;
        width: auto;
        transition: all 0.3s ease;
      }

      nav a:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffeb3b;
      }

      .close-btn {
        display: block;
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 1.8rem;
        cursor: pointer;
      }

    }

    @media (max-width:1024px) {

      h1 {
        font-size: 1.9rem;
        margin-top: 120px;
      }

      main p {
        font-size: 1.3rem;
        margin-bottom: 100px;
      }

      nav {
        gap: 25px;
      }

    }

    @media (max-width:480px) {

      header {
        padding: 10px 12px;
      }

      .container {
        padding: 30px;
        max-width: 420px;
        width: 92%;
        margin: 40px auto;
        border-radius: 12px;
        position: relative;
        z-index: 5;
      }

      .logo-wrapper img {
        max-height: 50px;
      }

      .texts {
        font-size: 0.6rem;
      }

      .menu-toggle {
        font-size: 1.7rem;
      }

      h1 {
        font-size: 1.4rem;
        margin-top: 90px;
      }

      main p {
        font-size: 1rem;
        margin-bottom: 60px;
      }

      nav {
        width: 220px;
      }

      nav a {
        font-size: 1rem;
      }

      .buttons button {
        width: 34px;
        height: 34px;
        font-size: 1.6rem;
      }

      .scroll-down-indicator svg {
        width: 32px;
        height: 32px;
      }

      footer {
        font-size: 0.9rem;
      }

    }

    @media screen and (max-width:768px) {

      nav {
        position: fixed !important;
        top: 0;
        right: -280px;
        width: 260px;
        height: 100vh;

        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding-left: 30px;
        justify-content: flex-start;

        padding-top: 20px;
        gap: 25px;

        background: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(6px);
        transition: right 0.4s ease;
      }

      nav a {
        font-size: 1rem;
        padding: 12px 20px;
        text-align: center;
        width: auto;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 8px;
      }

      nav a:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffeb3b;
      }

      .close-btn {
        display: block !important;
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 1.5rem;
        cursor: pointer;
        background: none;
        border: none;
        color: white;
        z-index: 3001;
        padding: 5px;
      }

      .menu-toggle {
        display: block !important;
        margin-left: auto;
        z-index: 3100;
      }

    }

    @media screen and (max-width:480px) {

      nav {
        width: 220px !important;
        right: -220px;
        padding-top: 20px;
      }

      nav.active {
        right: 0 !important;
      }

      nav a {
        font-size: 0.95rem;
        padding: 10px 15px;
        width: 85%;
      }

      .close-btn {
        top: 12px;
        right: 12px;
        font-size: 1.3rem;
      }

    }

    @media screen and (max-width:480px) {

      .container {
        margin-top: 0;
        margin-bottom: 0;
      }

      h1 {
        font-size: 1.35rem;
        margin-top: 0;
      }

      main p {
        font-size: 0.95rem;
        margin-bottom: 25px;
      }


      .buttons button {
        width: 32px;
        height: 32px;
        font-size: 16px;
      }

    }

    @media (max-width:992px) {

      #navMenu {
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        align-items: flex-start !important;
        padding-top: 60px !important;
      }

      #navMenu a {
        display: block !important;
        width: 100% !important;
        text-align: left !important;
        margin: 0 !important;
        padding: 12px 0 !important;
      }

    }

    .overlay {
      pointer-events: none;
    }


    @media (max-width:768px) { 
      .container {
        width: 92% !important;
        max-width: 420px !important;
        margin: 20px auto !important;
        padding: 22px !important;
        height: auto !important;
      }
 
      input[type="text"],
      input[type="email"],
      input[type="password"] {
        width: 100% !important;
        box-sizing: border-box;
      } 
      button {
        width: 100% !important;
      } 
      .page-content {
        padding: 15px !important;
      }

    } 

    @media (max-width:480px) {

      .container {
        width: 95% !important;
        padding: 18px !important;
      }

      h1 {
        font-size: 20px !important;
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

    <div class="menu-toggle" onclick="toggleMenu()">☰</div>

    <nav id="navMenu">
      <div class="close-btn" onclick="toggleMenu()">✖</div>
      <a href="index.php">Home</a>
      <a href="register.php">Registration</a>
      <a href="login.php">Login</a>
      <a href="contact.php">Contact Us</a>
      <a href="about.php">About Us</a>
    </nav>
  </header>

  <section
    style="position: relative; z-index: 1; width: 100%; max-width: 100%; overflow: hidden; padding-bottom: 10px;">
    <div class="page-content">
      <div class="container">
        <h1>Student Registration</h1>
        <?php if ($success): ?>
          <div class="popup-overlay">
            <div class="popup-box">
              <h2>Registration Successful</h2>
              <button onclick="goToLogin()">OK</button>
            </div>
          </div>

          <script>
            document.documentElement.classList.add("popup-open");
            document.body.classList.add("popup-open");

            window.addEventListener("wheel", preventScroll, { passive: false });
            window.addEventListener("touchmove", preventScroll, { passive: false });
            window.addEventListener("keydown", blockKeys);

            function preventScroll(e) {
              e.preventDefault();
            }

            function blockKeys(e) {
              const keys = ["ArrowUp", "ArrowDown", "PageUp", "PageDown", "Home", "End", " "];
              if (keys.includes(e.key)) {
                e.preventDefault();
              }
            }

            function goToLogin() {
              window.location.href = "login.php";
            }
          </script>
        <?php elseif ($message): ?>
          <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" action="register.php">
          <div class="form-group">
            <input type="text" name="firstname" placeholder="First Name"
              value="<?= isset($firstname) ? htmlspecialchars($firstname) : '' ?>" required>
          </div>
          <div class="form-group">
            <input type="text" name="lastname" placeholder="Last Name"
              value="<?= isset($lastname) ? htmlspecialchars($lastname) : '' ?>" required>
          </div>
          <div class="form-group">
            <input type="text" name="hallticket" placeholder="Hall Ticket Number"
              value="<?= isset($hallticket) ? htmlspecialchars($hallticket) : '' ?>" required>
          </div>
          <div class="form-group">
            <input type="email" name="email" placeholder="Email"
              value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
          </div>
          <div class="form-group">
            <input type="text" name="phone" placeholder="Phone Number"
              value="<?= isset($phone) ? htmlspecialchars($phone) : '' ?>" required>
          </div>
          <div class="form-group">
            <input type="text" name="address" placeholder="Address"
              value="<?= isset($address) ? htmlspecialchars($address) : '' ?>" required>
          </div>
          <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>
          <div class="form-group">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          </div>
          <button type="submit">Register</button>
          <div class="footer">
            <p>Already registered? <a href="login.php">Login</a></p>
          </div>
        </form>
      </div>
    </div>
    <footer>
      &copy;2026
    </footer>
  </section>
  <script>
    function toggleMenu() {
      const nav = document.getElementById("navMenu");
      const toggle = document.querySelector(".menu-toggle");

      nav.classList.toggle("active");
      toggle.classList.toggle("hide");
    }

    document.querySelectorAll("#navMenu a").forEach(link => {
      link.addEventListener("click", function () {

        const nav = document.getElementById("navMenu");
        const toggle = document.querySelector(".menu-toggle");

        nav.classList.remove("active");
        toggle.classList.remove("hide");

      });
    });
  </script>
</body>

</html>