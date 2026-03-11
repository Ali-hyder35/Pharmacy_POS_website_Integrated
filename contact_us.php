<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Shahnaz Shabbir Noshahi Pharmacy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
    .contact-section {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
      position: relative;
      overflow: hidden;
    }

    .contact-card {
      background: #ffffff;
      color: #333;
      border-radius: 20px;
      padding: 40px;
      max-width: 900px;
      width: 100%;
      box-shadow: 0px 10px 30px rgba(0,0,0,0.2);
      position: relative;
      z-index: 2;
    }

    .contact-card h2 {
      font-weight: bold;
      margin-bottom: 20px;
      color: #0072ff;
    }

    .form-control, .btn {
      border-radius: 10px;
      padding: 12px 15px;
    }

    .btn-primary {
      background: linear-gradient(90deg, #0072ff, #00c6ff);
      border: none;
      transition: 0.3s ease;
    }

    .btn-primary:hover {
      opacity: 0.9;
      transform: scale(1.05);
    }

    /* Floating pill icons */
    .pill-icon {
      position: absolute;
      opacity: 0.15;
      width: 60px;
      animation: float 12s infinite linear;
    }

    .pill1 { top: 10%; left: 5%; animation-delay: 0s; }
    .pill2 { top: 40%; right: 10%; animation-delay: 4s; }
    .pill3 { bottom: 15%; left: 20%; animation-delay: 8s; }

    @keyframes float {
      from { transform: translateY(0) rotate(0deg); }
      to { transform: translateY(-80px) rotate(360deg); }
    }

    footer {
      background: #001f3f;
      color: #fff;
      text-align: center;
      padding: 15px 0;
      margin-top: auto;
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

  <!-- Contact Section -->
  <section class="contact-section">
    <!-- floating pill icons (SVGs) -->
    <img src="https://www.svgrepo.com/show/522607/pill.svg" class="pill-icon pill1" alt="pill">
    <img src="https://www.svgrepo.com/show/522607/pill.svg" class="pill-icon pill2" alt="pill">
    <img src="https://www.svgrepo.com/show/522607/pill.svg" class="pill-icon pill3" alt="pill">

    <div class="contact-card">
      <h2 class="text-center mb-4">Contact Us</h2>
      <div class="row">
        <!-- Left info -->
        <div class="col-md-5 mb-4">
          <h5>📍 Address</h5>
          <p>Main Road,Ranmal Sharif, Phalia, Mandi Bahaudin, Pakistan</p>
          <h5>📞 Phone</h5>
          <p>+92 300 5126574 || +92 300 6053214</p>
          <h5>✉️ Email</h5>
          <p>shahnazshabbirNoshahiPharmacy@gmail.com</p>
          <h5>🕒 Hours</h5>
          <p>Mon - Sun: 9:00 AM - 08:00 PM</p>
        </div>

        <!-- Right form -->
        <div class="col-md-7">
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Your Email" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Subject">
            </div>
            <div class="mb-3">
              <textarea rows="5" class="form-control" placeholder="Your Message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Shahnaz Shabbir Noshahi Pharmacy . All Rights Reserved.</p>
  </footer>

</body>
</html>
