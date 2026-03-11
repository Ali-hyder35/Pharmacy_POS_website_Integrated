<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Pharmacy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Color palette: Healthcare theme */
    :root {
      --pharma-green: #2e8b57;   /* main pharmacy green */
      --pharma-light: #e6f9f2;   /* soft green background */
      --pharma-blue: #0077b6;    /* healthcare blue */
      --pharma-gray: #f4f6f9;    /* clean section bg */
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--pharma-light);
      margin: 0;
      padding: 0;
    }
  /* Pharmacy Navbar */
    .navbar {
      background: linear-gradient(90deg, #2d6a4f, #40916c);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .navbar-brand {
      font-weight: bold;
      color: #fff !important;
    }
    .navbar-nav .nav-link {
      color: #f8f9fa !important;
      margin: 0 10px;
      transition: color 0.3s;
    }
    .navbar-nav .nav-link:hover {
      color: #b7e4c7 !important;
    }
    /* Video Hero Section */
    .video-hero {
      position: relative;
      height: 70vh;
      overflow: hidden;
    }
    .video-hero video {
      position: absolute;
      top: 50%;
      left: 50%;
      min-width: 100%;
      min-height: 100%;
      object-fit: cover;
      transform: translate(-50%, -50%);
      z-index: 1;
    }
    .video-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 60, 40, 0.55); /* green overlay */
      z-index: 2;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      text-align: center;
      color: white;
      padding: 20px;
    }
    .video-overlay h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .video-overlay p {
      font-size: 1.2rem;
      max-width: 700px;
      margin: auto;
    }

    /* Mission & Vision Section */
    .mission-vision {
      background-color: white;
      padding: 60px 20px;
    }
    .mission-vision h2 {
      color: var(--pharma-green);
      font-weight: bold;
    }
    .mission-vision p {
      color: #444;
      font-size: 1.1rem;
    }

    /* Stats Section */
    .stats {
      background-color: var(--pharma-gray);
      padding: 50px 20px;
    }
    .stat-box {
      background: white;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s ease-in-out;
    }
    .stat-box:hover {
      transform: translateY(-5px);
    }
    .stat-box h3 {
      font-size: 2.5rem;
      color: var(--pharma-blue);
      font-weight: bold;
    }
    .stat-box p {
      margin-top: 10px;
      color: #666;
      font-size: 1rem;
    }

    /* Team Section */
    .team {
      padding: 60px 20px;
      background-color: white;
    }
    .team h2 {
      color: var(--pharma-green);
      font-weight: bold;
      margin-bottom: 40px;
    }
    .team-member {
      text-align: center;
      padding: 20px;
    }
    .team-member img {
      border-radius: 50%;
      width: 120px;
      height: 120px;
      object-fit: cover;
      border: 4px solid var(--pharma-green);
      margin-bottom: 15px;
    }
    .team-member h5 {
      color: var(--pharma-blue);
      font-weight: 600;
    }
    .team-member p {
      color: #555;
      font-size: 0.95rem;
    }

    /* Footer */
    footer {
      background: var(--pharma-green);
      color: white;
      padding: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#">Shahnaz Shabbir Noshahi Pharmacy</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="main.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="our_medicines.php">Medicines</a></li>
          <li class="nav-item"><a class="nav-link" href="our_cosmetics.php">Cosmetics</a></li>
          <li class="nav-item"><a class="nav-link" href="about_us.php">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Video Hero Section -->
  <section class="video-hero">
    <video autoplay muted loop>
      <source src="https://cdn.pixabay.com/vimeo/269303725/pharmacy-17473.mp4?width=640&hash=0c437c8c03ad447e75e7f1ef8a08d09384bd9795" type="video/mp4">
    </video>
    <div class="video-overlay">
      <h1>About Our Pharmacy</h1>
      <p>Delivering trusted healthcare, expert guidance, and genuine medicines for your well-being.</p>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="mission-vision container text-center">
    <h2>Our Mission</h2>
    <p>To provide safe, affordable, and accessible medicines while ensuring the highest standards of care.</p>
    <h2 class="mt-5">Our Vision</h2>
    <p>To be the most trusted pharmacy partner, embracing innovation and technology for a healthier tomorrow.</p>
  </section>

  <!-- Stats -->
  <section class="stats">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-3">
          <div class="stat-box">
            <h3>15+</h3>
            <p>Years of Service</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-box">
            <h3>50K+</h3>
            <p>Happy Customers</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-box">
            <h3>200+</h3>
            <p>Healthcare Products</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-box">
            <h3>100%</h3>
            <p>Genuine Medicines</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section class="team text-center">
    <div class="container">
      <h2>Meet Our Experts</h2>
      <div class="row">
        <div class="col-md-6 team-member">
          <!-- <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Pharmacist"> -->
          <h5>Dr. Rabbiya Haider</h5>
          <p>Pharmacist</p>
        </div>
        <div class="col-md-6 team-member">
          <!-- <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Pharmacist"> -->
          <h5>Raffiya Haider</h5>
          <p>Microbiologists(Quality Assurance Officer)</p>
        </div>
        
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 Pharmacy Name. All Rights Reserved.</p>
  </footer>

</body>
</html>
