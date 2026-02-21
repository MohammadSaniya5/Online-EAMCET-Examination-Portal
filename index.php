<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <title>Vignan EAMCET Exam Portal</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body,
    html {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      font-family: 'Inter', sans-serif;
      background: url('vgnt.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      display: flex;
      flex-direction: column;
    }

    .overlay {
      position: fixed;
      pointer-events: none;
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
      margin-left: auto;

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
      text-align: center;
      padding: 10px 20px;
      position: relative;
      z-index: 1;
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
      object-fit: fill;
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
      z-index: 3;
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
    (function () {
      const slides = document.querySelectorAll('.slide');
      const prevBtn = document.getElementById('prev');
      const nextBtn = document.getElementById('next');
      let currentIndex = 0;
      let slideInterval;

      function showSlide(index) {
        slides.forEach((slide, i) => {
          slide.classList.toggle('active', i === index);
        });
        currentIndex = index;
      }

      function nextSlide() {
        showSlide((currentIndex + 1) % slides.length);
      }

      function prevSlide() {
        showSlide((currentIndex - 1 + slides.length) % slides.length);
      }

      function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 4000);
      }

      prevBtn.addEventListener('click', () => {
        prevSlide();
        resetInterval();
      });

      nextBtn.addEventListener('click', () => {
        nextSlide();
        resetInterval();
      });

      slideInterval = setInterval(nextSlide, 4000);
    })();
     
    function toggleMenu() {
    const nav = document.getElementById("navMenu");
    nav.classList.toggle("active");
  }

  </script>
</body>

</html>