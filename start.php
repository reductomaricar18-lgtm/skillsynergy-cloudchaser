<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillSynergy Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      min-height: 100vh;
      background: linear-gradient(to bottom right, #cce7ff, #e2e2ff);
      background-image: url('SkillSynergy.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      font-family: 'Segoe UI', sans-serif;
    }

    nav {
      position: fixed;
      top: 0;
      width: 100%;
      padding: 20px 50px;
      display: flex;
      justify-content: flex-end;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(6px);
      z-index: 10;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 40px;
    }

    nav ul li a {
      text-decoration: none;
      color: #fff;
      font-size: 20px;
      position: relative;
      transition: color 0.3s;
    }

    nav ul li a:hover {
      color: #ffd700;
    }

    nav ul li a::after {
      content: '';
      position: absolute;
      width: 0%;
      height: 2px;
      background: #ffd700;
      left: 0;
      bottom: -5px;
      transition: 0.4s;
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    .title-container {
      position: absolute;
      top: 350px;
      left: 700px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 20px;
    }

    .title-text {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    h1, h2 {
      font-size: 120px;
      font-weight: 900;
      background: linear-gradient(45deg, #003366, #0077b6, #00b4d8);
      -webkit-background-clip: text;
      color: transparent;
      text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
      margin: 0;
      opacity: 0;
      transform: translateY(50px);
      animation: fadeSlideIn 1s forwards;
    }

    h2 {
      animation-delay: 0.4s;
    }

    h1:hover, h2:hover {
      text-shadow: 0 0 20px rgb(0, 159, 245), 0 0 30px rgb(24, 199, 230);
      transform: scale(1.05);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .get-started-btn {
      padding: 10px 20px;
      font-size: 16px;
      margin-top: 20px;
      margin-left: 300px;
      color: #fff;
      background: #0077b6;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: background 0.3s, transform 0.3s;
    }

    .get-started-btn:hover {
      background: #00b4d8;
      transform: scale(1.05);
    }

    @keyframes fadeSlideIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .title-container {
        left: 50%;
        transform: translateX(-50%);
        top: 300px;
      }
      
      h1, h2 {
        font-size: 80px;
      }
      
      .get-started-btn {
        margin-left: 0;
        align-self: center;
      }
    }

    @media (max-width: 768px) {
      nav {
        padding: 15px 20px;
      }
      
      nav ul {
        gap: 20px;
      }
      
      nav ul li a {
        font-size: 16px;
      }
      
      .title-container {
        top: 250px;
        gap: 15px;
      }
      
      .title-text {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
      
      h1, h2 {
        font-size: 60px;
      }
      
      .get-started-btn {
        font-size: 14px;
        padding: 12px 24px;
      }
    }

    @media (max-width: 480px) {
      nav {
        padding: 10px 15px;
      }
      
      nav ul {
        gap: 15px;
      }
      
      nav ul li a {
        font-size: 14px;
      }
      
      .title-container {
        top: 200px;
        padding: 0 20px;
      }
      
      h1, h2 {
        font-size: 40px;
      }
      
      .get-started-btn {
        font-size: 12px;
        padding: 10px 20px;
      }
    }

    @media (max-width: 360px) {
      nav ul {
        flex-direction: column;
        gap: 10px;
      }
      
      nav ul li a {
        font-size: 12px;
        padding: 5px 10px;
      }
      
      h1, h2 {
        font-size: 32px;
      }
    }
  </style>
</head>
<body>

  <nav>
    <ul>
      <li><a href="start.php"><i class="fa-solid fa-house"></i> Home</a></li>
      <li><a href="about.php"><i class="fa-solid fa-circle-info"></i> About</a></li>
      <li><a href="contactus.php"><i class="fa-solid fa-envelope"></i> Contact</a></li>
    </ul>
  </nav>

  <div class="title-container">
    <div class="title-text">
      <h1>Skill</h1>
      <h2>Synergy</h2>
    </div>
    <button class="get-started-btn" onclick="window.location.href='login.php'">Get Started</button>
  </div>

</body>
</html>




