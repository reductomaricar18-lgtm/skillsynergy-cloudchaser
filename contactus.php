<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | SkillSynergy</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      min-height: 100vh;
      background: linear-gradient(to right, #cce7ff, #e2e2ff);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 50px 20px;
      background-image: url('SSbg.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      margin: 0px;
    }

    h1 {
      font-size: 60px;
      margin-bottom: 30px;
      background: linear-gradient(45deg, #003366, #0077b6, #00b4d8);
      -webkit-background-clip: text;
      color: transparent;
      text-align: center;
    }

    .team-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      max-width: 800px;
      width: 100%;
      margin-bottom: 50px;
      animation: fadeIn 1.5s ease;
    }

    .team-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      text-align: center;
      padding: 25px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .team-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }

    .team-card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-bottom: 15px;
      object-fit: cover;
    }

    .team-card h3 {
      font-size: 22px;
      color: #003366;
      margin-bottom: 8px;
    }

    .team-card p {
      font-size: 16px;
      color: #555;
      margin-bottom: 15px;
    }

    .team-card a {
      color: #0077b6;
      text-decoration: none;
      font-size: 16px;
      transition: color 0.3s;
    }

    .team-card a:hover {
      color: #00b4d8;
    }

    .back-btn {
      padding: 12px 25px;
      background: #0077b6;
      color: #fff;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      font-size: 16px;
      transition: background 0.3s, transform 0.3s;
    }

    .back-btn:hover {
      background: #00b4d8;
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 900px) {
      h1 {
        font-size: 50px;
      }
      
      .team-container {
        max-width: 90%;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
      }
    }

    @media (max-width: 768px) {
      body {
        padding: 30px 15px;
      }
      
      h1 {
        font-size: 40px;
        margin-bottom: 25px;
      }
      
      .team-container {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      
      .team-card {
        padding: 20px;
      }
      
      .team-card img {
        width: 80px;
        height: 80px;
      }
      
      .team-card h3 {
        font-size: 18px;
      }
      
      .team-card p {
        font-size: 14px;
      }
      
      .team-card a {
        font-size: 14px;
      }
      
      .back-btn {
        font-size: 14px;
        padding: 10px 20px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 20px 10px;
      }
      
      h1 {
        font-size: 32px;
        margin-bottom: 20px;
      }
      
      .team-container {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-bottom: 30px;
      }
      
      .team-card {
        padding: 15px;
        border-radius: 15px;
      }
      
      .team-card img {
        width: 70px;
        height: 70px;
      }
      
      .team-card h3 {
        font-size: 16px;
      }
      
      .team-card p {
        font-size: 13px;
      }
      
      .team-card a {
        font-size: 13px;
      }
      
      .back-btn {
        font-size: 12px;
        padding: 8px 16px;
      }
    }

    @media (max-width: 360px) {
      h1 {
        font-size: 28px;
      }
      
      .team-card img {
        width: 60px;
        height: 60px;
      }
      
      .team-card h3 {
        font-size: 14px;
      }
      
      .team-card p {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>

  <h1><i class="fa-solid fa-users"></i> Meet the SkillSynergy Team</h1>

  <div class="team-container">
    <div class="team-card">
      <img src="Babyjoy.jpg" alt="Baby Joy Gomez">
      <h3>Baby Joy Gomez</h3>
      <p>Project Lead</p>
      <a href="mailto:member1@plm.edu.ph"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>

    <div class="team-card">
      <img src="Maricar.jpg" alt="Maricar Reducto">
      <h3>Maricar Reducto</h3>
      <p>Backend Developer</p>
      <a href="mailto:member2@plm.edu.ph"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>

    <div class="team-card">
      <img src="Amanda.jpg" alt="Amanda Duyan">
      <h3>Amanda Duyan</h3>
      <p>Frontend Developer</p>
      <a href="mailto:member3@plm.edu.ph"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>

    <div class="team-card">
      <img src="Ralph.jpg" alt="Ralph Estanislao">
      <h3>Ralph Estanislao</h3>
      <p>Backend Developer</p>
      <a href="mailto:member4@plm.edu.ph"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>

    <div class="team-card">
      <img src="Aldrin.jpg" alt="Aldrin Manay">
      <h3>Aldrin Manay</h3>
      <p>Frontend Developer</p>
      <a href="mailto:member5@plm.edu.ph"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>

    <div class="team-card">
      <img src="Shahania.jpg" alt="Shahania Ignacio">
      <h3>Shahania Ignacio</h3>
      <p>Tester</p>
      <a href="mailto:member6@plm.edu.ph"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>
  </div>

  <a href="start.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>

</body>
</html>
