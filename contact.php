<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <title>Contact Us - Vignan EAMCET Exam Portal</title>
  <style>
    * {
      box-sizing: border-box;
    }

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

    main {
      display: flex;
      justify-content: center;
      padding: 70px 20px;
    }

    .contact-container {
      max-width: 800px;
      width: 100%;
      background: transparent;
      padding: 20px;
      border-radius: 12px;
      margin-top: -90px;
    }

    h1 {
      font-size: 2.6rem;
      text-align: center;
      margin-bottom: 20px;
      color: yellow;
    }

    p {
      font-size: 1.2rem;
      line-height: 1.8;
      font-weight: 500;
      text-align: justify;
      color: white;
      text-shadow: 1px 1px 3px black;
      font-weight: bold;
    }

    .contact-info {
      margin-top: 30px;
      color: white;
      margin-bottom: 35px;

    }

    .contact-info h3 {
      color: white;
      font-weight: bold;
      margin-bottom: 10px;
      font-size: 20px;
    }

    .contact-info p {
      margin: 6px 0;
      font-weight: 600;
    }

    .contact-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      background: rgba(0, 0, 0, 0.4);
      border-radius: 10px;
      overflow: hidden;
    }

    .contact-table th,
    .contact-table td {
      padding: 12px 15px;
      text-align: center;
      font-size: 1.1rem;
      color: white;
      text-shadow: 1px 1px 3px black;
    }

    .contact-table thead {
      background-color: rgba(255, 255, 0, 0.85);
    }

    .contact-table thead th {
      color: black;
      font-weight: bold;
    }

    .contact-table tbody tr {
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .contact-table tbody tr:last-child {
      border-bottom: none;
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
      margin-top: 100px;
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
    style="position: relative; z-index: 1; width: 100vw; max-width: 100vw; overflow: hidden; padding-bottom: 10px;">
    <main>
      <div class="contact-container">
        <h1>Contact Us</h1>

        <div class="contact-info">
          <h3>For Admissions Contact :</h3>

          <table class="contact-table">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Contact No.</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Mr. R. Ramanjan Prasad</td>
                <td>9000555097</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Dr. Govinda Chowdary</td>
                <td>7702551269</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Mr. K. Vishnu</td>
                <td>9949782303</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </main>
  </section>
  <footer>
    &copy;2026
  </footer>
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