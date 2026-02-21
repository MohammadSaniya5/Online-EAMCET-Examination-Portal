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
</body>

</html>