<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <title>About Us - Vignan EAMCET Exam Portal</title>
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
      padding: 60px 20px;
    }

    .about-container {
      max-width: 1300px;
      width: 100%;
      background: transparent;
      padding: 27px;
      border-radius: 12px;
      margin-top: -120px;
      margin-bottom: 0px;
    }

    h1 {
      font-size: 2.6rem;
      text-align: center;
      margin-bottom: 20px;
      color: yellow;

    }

    .about-container p {
      font-size: 1.2rem;
      line-height: 1.8;
      font-weight: 500;
      text-align: justify;
      color: white;
      text-shadow: 1px 1px 3px black;
    }

    footer {
      text-align: center;
      padding: 20px 15px;
      font-size: 1.1rem;
      color: #ffffff;
      text-shadow: 1px 1px 3px black;
      font-weight: bold;
      z-index: 1;
      margin-top: 0;
    }

    .about-grid {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      gap: 30px;
    }

    .about-left {
      padding: 25px;
      border-radius: 12px;
      margin-top: 30px;
    }

    .about-right {
      text-align: center;
    }

    .branch-title {
      text-align: center;
      color: yellow;
      margin-bottom: 15px;
      font-size: 1.4rem;
    }

    .branch-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 12px;
    }

    .branch-card {
      border-radius: 12px;
      padding: 12px;
      text-align: center;
      text-decoration: none;
      color: white;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .branch-icon {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      background-color: #FFD700; 
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: black;
      margin: 0 auto 8px auto;
      font-size: 0.9rem;
    }


    .branch-card h4 {
      font-size: 0.95rem;
      margin: 5px 0;
    }

    .branch-card span {
      font-size: 0.85rem;
      opacity: 0.9;
    }

    .branch-card:hover {
      transform: translateY(-4px);
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

  <section
    style="position: relative; z-index: 1; width: 100vw; max-width: 100vw; overflow: hidden; padding-bottom: 10px;">
    <main>
      <div class="about-container">
        <h1>About Us</h1>

        <div class="about-grid">
          <div class="about-left">
            <p>
              Vignan Institute of Technology and Science (Autonomous), established in 1999 by
              Dr. Lavu Rathaiah, has grown from four engineering branches into a leading center of
              quality technical education.
            </p>

            <p>
              With 2500+ students, VGNT offers a strong academic environment with:
              <br><br>
              <strong>UG Programs:</strong> CIVIL, MECH, EEE, ECE, CSE, CSE (AI & ML),
              CSE (Data Science), IT, EIE
              <br>
              <strong>PG Programs:</strong> MCA, MBA, M.Tech
            </p>

            <p>
              The 350-acre campus offers modern infrastructure, advanced labs, experienced faculty,
              and well-equipped hostels amidst serene, green surroundings.
            </p>

            <p>
              For assistance or feedback, visit our
              <a href="contact.php" style="color:white; font-weight:bold; text-decoration:none;">
                Contact Us
              </a> page.
            </p>
          </div>

          <div class="about-right">
            <h3 class="branch-title">B.Tech Courses</h3>

            <div class="branch-grid">
              <a href="https://vignanits.ac.in/departments/civil-engineering" class="branch-card" target="_blank">
                <div class="branch-icon">CE</div>
                <h4>Civil Engineering</h4>
                <span>Intake: 30</span>
              </a>

              <a href="https://vignanits.ac.in/departments/electrical-and-electronics-engineering" class="branch-card"
                target="_blank">
                <div class="branch-icon">EEE</div>
                <h4>Electrical & Electronics</h4>
                <span>Intake: 60</span>
              </a>

              <a href="https://vignanits.ac.in/departments/mechanical-engineering" class="branch-card" target="_blank">
                <div class="branch-icon">ME</div>
                <h4>Mechanical Engineering</h4>
                <span>Intake: 30</span>
              </a>

              <a href="https://vignanits.ac.in/departments/electronics-and-communication-engineering"
                class="branch-card" target="_blank">
                <div class="branch-icon">ECE</div>
                <h4>Electronics & Communication</h4>
                <span>Intake: 120</span>
              </a>

              <a href="https://vignanits.ac.in/departments/computer-science-engineering" class="branch-card"
                target="_blank">
                <div class="branch-icon">CSE</div>
                <h4>Computer Science & Engineering</h4>
                <span>Intake: 240</span>
              </a>

              <a href="https://vignanits.ac.in/artificial-intelligence-and-machine-learning" class="branch-card"
                target="_blank">
                <div class="branch-icon">CSE AI/ML</div>
                <h4>CSE (AI & ML)</h4>
                <span>Intake: 240</span>
              </a>

              <a href="https://vignanits.ac.in/data-science" class="branch-card" target="_blank">
                <div class="branch-icon">CSE DS</div>
                <h4>CSE (Data Science)</h4>
                <span>Intake: 180</span>
              </a>

              <a href="https://vignanits.ac.in/it" class="branch-card" target="_blank">
                <div class="branch-icon">IT</div>
                <h4>Information Technology</h4>
                <span>Intake: 60</span>
              </a>

              <a href="https://vignanits.ac.in/artificial-intelligence_data-science" class="branch-card"
                target="_blank">
                <div class="branch-icon">AI DS</div>
                <h4>AI & Data Science</h4>
                <span>Intake: 60</span>
              </a>

              <a href="https://vignanits.ac.in/artificial-intelligence-and-machine-learning" class="branch-card"
                target="_blank">
                <div class="branch-icon">AI & ML</div>
                <h4>Artificial Intelligence & ML</h4>
                <span>Intake: 60</span>
              </a>

              <a href="https://vignanits.ac.in/departments/electonics-instrumentation-engineering/" class="branch-card"
                target="_blank">
                <div class="branch-icon">EIE</div>
                <h4>Electronics & Instrumentation</h4>
                <span>Intake: 30</span>
              </a>
            </div>
          </div>

        </div>
      </div>

    </main>

    <footer>
      &copy;2026
    </footer>
  </section>
</body>

</html>