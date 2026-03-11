<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shahnaz Shabbir Noshahi Pharmacy</title>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="CSS/STYLES.css" rel="stylesheet">
  <style>
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

    /* Carousel fade */
    .carousel-item {
      transition: opacity 1s ease-in-out;
    }
    .carousel-fade .carousel-item {
      opacity: 0;
    }
    .carousel-fade .carousel-item.active {
      opacity: 1;
    }
    .carousel-inner img {
      height: 400px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.25);
    }

    /* Vision Section */
    .vision-section {
      position: relative;
      padding: 80px 20px;
      text-align: center;
      color: #fff;
      overflow: hidden;
      background: linear-gradient(135deg, #1b4332, #52b788);
    }
    .vision-section h2 {
      font-size: 1.8rem;
      margin-bottom: 30px;
      z-index: 2;
      position: relative;
    }
    .vision-section video {
      z-index: 2;
      position: relative;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    /* Animated gradient waves */
    .wave {
      position: absolute;
      top: 0;
      left: 0;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.15), transparent 70%),
                  radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1), transparent 70%);
      animation: waveAnim 12s linear infinite alternate;
      z-index: 1;
    }
    @keyframes waveAnim {
      from { transform: translateX(-10%) translateY(-10%); }
      to { transform: translateX(10%) translateY(10%); }
    }

    /* Floating pills */
    .pill {
      position: absolute;
      width: 40px;
      height: 40px;
      opacity: 0.7;
      animation: floatPill 12s ease-in-out infinite;
      z-index: 1;
      transition: filter 0.3s;
    }
    .pill:hover {
      filter: drop-shadow(0 0 12px rgba(255,255,255,0.9));
    }
    @keyframes floatPill {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-25px); }
    }

    .pill:nth-child(1) { top: 20%; left: 10%; animation-duration: 8s; }
    .pill:nth-child(2) { top: 60%; left: 80%; animation-duration: 10s; }
    .pill:nth-child(3) { top: 40%; left: 30%; animation-duration: 12s; }
    .pill:nth-child(4) { top: 70%; left: 60%; animation-duration: 14s; }

    /* Footer */
    footer {
      background: #1b4332;
      color: #f1f1f1;
      padding: 25px 20px;
      text-align: center;
    }
    footer a {
      color: #95d5b2;
      text-decoration: none;
      margin: 0 8px;
    }
    footer a:hover {
      color: #d8f3dc;
      text-decoration: underline;
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

  <!-- Carousel -->
  <div id="pharmacyCarousel" class="carousel slide carousel-fade my-5" data-bs-ride="carousel">
    <div class="carousel-inner container">
      <div class="carousel-item active">
        <img src="slider_1.jpg" class="d-block w-100" alt="Medicine 1">
      </div>
      <div class="carousel-item">
        <img src="slider_2.jpg" class="d-block w-100" alt="Medicine 2">
      </div>
      <div class="carousel-item">
        <img src="slider_3.jpg" class="d-block w-100" alt="Medicine 3">
      </div>
    </div>
  </div>

  <!-- Vision Section -->
  <section class="vision-section">
    <div class="wave"></div>

    <!-- Floating Pill Icons -->
    <svg class="pill" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect x="10" y="20" width="20" height="24" rx="10" fill="#95d5b2"/><rect x="30" y="20" width="20" height="24" rx="10" fill="#40916c"/></svg>
    <svg class="pill" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><circle cx="32" cy="32" r="12" fill="#52b788"/><rect x="28" y="20" width="8" height="24" fill="#fff"/></svg>
    <svg class="pill" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect x="20" y="20" width="24" height="24" rx="12" fill="#74c69d"/></svg>
    <svg class="pill" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path d="M20 20h24v24H20z" fill="#1b4332"/></svg>

    <h2>
      To become the most trusted and accessible pharmacy, delivering quality medicines, personalized care, 
      and innovative health solutions that improve the well-being of every customer we serve
    </h2>
    <video width="80%" controls autoplay muted loop>
      <source src="video.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Pharmacy POS | Designed with ❤️ by Ali Hyder Gill.
      | Contact Number : +92 3015436330 </p>
    <p>
      <a href="#">Privacy Policy</a> | 
      <a href="#">Terms & Conditions</a> | 
      <a href="contact_us.php">Contact Us</a>
    </p>
  </footer>

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
   <script src="JS/main.js"></script>
</body>
</html>
