<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <title>Vignan EAMCET Exam Portal</title>
  <style>
    * {
      box-sizing: border-box;
    }

    .menu-toggle.hide {
      display: none !important;
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

    main {
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      min-height: 100vh; 
      padding: 20px;
      position: relative;
    }

    main>div {
      max-width: 900px;
      width: 100%;
      margin-bottom: 10px;
      margin-top: 30px;
    }

    h1 {
      font-size: 2.1rem;
      color: white;
      text-shadow: 2px 2px 5px #000, 0 0 10px #ffffff;
      margin-bottom: 20px;
      margin-top: 150px;
    }

    main p {
      font-size: 1.5rem;
      color: #ffffff;
      text-shadow: 2px 2px 4px #000, 0 0 6px #ffffff;
      margin-bottom: 140px;

    }

    .container {
      width: 100%;
      height: 100vh;
      margin: 0;
      padding: 0;
      position: relative;
      overflow: hidden;
      border-radius: 20px;
    }

    .slide {
      display: none;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .slide.active {
      display: block;
    }

    .buttons {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      transform: translateY(-50%);
      pointer-events: none;
    }

    .buttons button {
      background: rgba(0, 0, 0, 0.35);
      border: none;
      color: white;
      font-size: 2.2rem;
      border-radius: 50%;
      width: 44px;
      height: 44px;
      cursor: pointer;
      pointer-events: all;
      display: flex;
      justify-content: center;
      align-items: center;
      padding-left: 6px;
    }

    #prev {
      margin-left: 10px;
    }

    #next {
      margin-right: 20px;
    }

    .caption {
      position: relative;
    }

    .buttons button:hover {
      background: rgba(0, 0, 0, 0.65);
    }

    .scroll-down-indicator {
      display: inline-block;
      font-size: 4rem;
      animation: bounce 1.5s infinite;
      color: white;
      text-shadow: 1px 1px 5px #000;
      margin: 90px auto;
      text-align: center;
      cursor: pointer;
      z-index: 0;
    }

    body.menu-open::before {
      content: "";
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 2500;
      pointer-events: none;
    }

    header,
    footer {
      position: relative;
      z-index: 10;
    }

    main {
      position: relative;
      z-index: 0;
    }

    .menu-toggle.hide {
      display: none;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(12px);
      }
    }

    footer {
      text-align: center;
      padding: 20px 15px;
      font-size: 1.1rem;
      color: #ffffff;
      text-shadow: 1px 1px 3px black;
      font-weight: bold;
      z-index: 1;
      margin-top: auto;
    }

    .menu-toggle {
      display: none;
      font-size: 2rem;
      cursor: pointer;
      color: white;
      text-shadow: 1px 1px 3px black;
      background: none;
      border: none;
      padding: 0;
      margin: 0;
    }

    .close-btn {
      display: none;
      background: none;
      border: none;
      color: white;
      font-size: 1.8rem;
      cursor: pointer;
      padding: 0;
      margin: 0;
    }

    @media (max-width: 992px) {

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
        height: 28vh;
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
        height: 28vh;
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

      .scroll-down-indicator svg {
        width: 24px;
        height: 24px;
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
    <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
    <nav id="navMenu">
      <div class="close-btn" onclick="toggleMenu()">✖</div>
      <a href="index.php">Home</a>
      <a href="register.php">Registration</a>
      <a href="login.php">Login</a>
      <a href="contact.php">Contact Us</a>
      <a href="about.php">About Us</a>
    </nav>
  </header>

  <main>
    <div>
      <h1>Welcome to the Mock EAMCET Examination Portal </h1>
      <p>Please register or log in to proceed with your exam preparation.</p>
      <a href="#carousel" class="scroll-down-indicator"><svg width="40" height="40" viewBox="0 0 24 24" fill="white">
          <path d="M12 5v14m0 0l-7-7m7 7l7-7" stroke="white" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg></a>
    </div>
  </main>


  <section class="container" id="carousel">
    <img src="pic1.jpg" alt="Campus Image 1" class="slide active">
    <img src="pic2.jpg" alt="Campus Image 2" class="slide">
    <img src="pic3.jpg" alt="Campus Image 3" class="slide">
    <img src="pic4.jpg" alt="Campus Image 4" class="slide">
    <img src="pic5.jpg" alt="Campus Image 5" class="slide">
    <img src="pic6.jpg" alt="Campus Image 6" class="slide">
    <img src="pic7.jpg" alt="Campus Image 7" class="slide">

    <div class="buttons">
      <button id="prev">&#10094;</button>
      <button id="next">&#10095;</button>
    </div>
  </section>
  <section>
    <p class="caption"
      style="font-size: 1.6rem; margin: 20px 0 10px; color: #ffffff; text-shadow: 1px 1px 4px #000; text-align: center;">
      "Empowering Students, Creating Futures"
    </p>
  </section>
  </section>


  <footer>
    &copy; 2026
  </footer>

  <script>
    function toggleMenu() {
      const nav = document.getElementById("navMenu");
      const body = document.body;
      const toggle = document.querySelector(".menu-toggle");

      nav.classList.toggle("active");
      body.classList.toggle("menu-open");
      toggle.classList.toggle("hide");
    } 
    document.querySelectorAll("#navMenu a").forEach(link => {
      link.addEventListener("click", function (e) {

        const url = this.getAttribute("href");

        const nav = document.getElementById("navMenu");
        const toggle = document.querySelector(".menu-toggle");

        nav.classList.remove("active");
        document.body.classList.remove("menu-open");
        toggle.classList.remove("hide"); 
        window.location.href = url;
      });
    });
  </script>
</body>

</html>