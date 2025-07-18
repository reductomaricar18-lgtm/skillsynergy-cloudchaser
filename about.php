<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About SkillSynergy</title>
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

    .about-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      padding: 40px;
      max-width: 800px;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
      animation: fadeIn 1.5s ease;
    }

    .about-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }

    .about-card h1 {
      font-size: 50px;
      margin-bottom: 20px;
      background: linear-gradient(45deg, #003366, #0077b6, #00b4d8);
      -webkit-background-clip: text;
      color: transparent;
    }

    .about-card p {
      font-size: 20px;
      color: #333;
      line-height: 1.8;
      margin-bottom: 30px;
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
      .about-card {
        max-width: 90%;
        padding: 30px;
      }
      
      .about-card h1 {
        font-size: 40px;
      }
      
      .about-card p {
        font-size: 18px;
      }
    }

    @media (max-width: 768px) {
      body {
        padding: 30px 15px;
      }
      
      .about-card {
        padding: 25px;
      }
      
      .about-card h1 {
        font-size: 32px;
      }
      
      .about-card p {
        font-size: 16px;
        line-height: 1.6;
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
      
      .about-card {
        padding: 20px;
        border-radius: 15px;
      }
      
      .about-card h1 {
        font-size: 28px;
        margin-bottom: 15px;
      }
      
      .about-card p {
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 20px;
      }
      
      .back-btn {
        font-size: 12px;
        padding: 8px 16px;
      }
    }

    @media (max-width: 360px) {
      .about-card h1 {
        font-size: 24px;
      }
      
      .about-card p {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>

  <div class="about-card">
    <h1><i class="fa-solid fa-circle-info"></i> About SkillSynergy</h1>
    <p>
      SkillSynergy is a system integration project developed by the IT 3rd year student called cloud chasers at PLM department of College of Information System and Technology Management.
      This platform enables students to exchange skills based on their skill offers and personal preferences, fostering a collaborative environment among PLM CISTM students.
      The goal is to create connections, promote peer learning, and empower students by leveraging each other's strengths.
    </p>
    <a href="start.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
  </div>

</body>
</html>