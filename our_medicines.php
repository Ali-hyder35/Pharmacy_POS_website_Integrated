<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medicines - Our Pharmacy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4fdf8;
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
    .page-header {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                  url('https://img.freepik.com/free-photo/medical-banner-with-stethoscope_23-2149611211.jpg') center/cover no-repeat;
      color: #fff;
      padding: 80px 20px;
      text-align: center;
    }
    .medicine-category {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .medicine-category:hover {
      transform: translateY(-8px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .card-title {
      color: #00796b;
      font-weight: bold;
    }
    footer {
      background-color: #004d40;
      color: white;
      padding: 20px 0;
      text-align: center;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <!-- Header -->
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
  <div class="page-header">
    <h1>Our Medicines</h1>
    <p>We provide a wide range of trusted medicines for your health and wellness needs.</p>
  </div>


  <!-- Medicines Section -->
  <div class="container py-5">
    <div class="row g-4">
      
      <div class="col-md-4">
        <div class="card medicine-category h-100">
          <img src="https://img.freepik.com/free-photo/pharmacy-background-drug-prescription-pills_1150-17813.jpg" class="card-img-top" alt="Prescription Drugs">
          <div class="card-body">
            <h5 class="card-title">Prescription Medicines</h5>
            <p class="card-text">High-quality prescription drugs from trusted pharmaceutical companies to treat chronic and acute conditions.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card medicine-category h-100">
          <img src="https://img.freepik.com/free-photo/flat-lay-medicine-pills-arrangement_23-2148905659.jpg" class="card-img-top" alt="OTC Medicines">
          <div class="card-body">
            <h5 class="card-title">Over-the-Counter (OTC)</h5>
            <p class="card-text">A complete range of non-prescription medicines for common health issues like flu, fever, and pain relief.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card medicine-category h-100">
          <img src="https://img.freepik.com/free-photo/natural-medicine-herbal-leaves_1150-14916.jpg" class="card-img-top" alt="Herbal Medicines">
          <div class="card-body">
            <h5 class="card-title">Herbal & Natural</h5>
            <p class="card-text">Safe and effective herbal medicines to promote natural healing and strengthen immunity.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card medicine-category h-100">
          <img src="https://img.freepik.com/free-photo/dermatology-concept-with-skin-products_23-2149435621.jpg" class="card-img-top" alt="Personal Care">
          <div class="card-body">
            <h5 class="card-title">Personal Care</h5>
            <p class="card-text">Health and wellness essentials including skincare, baby care, and hygiene products.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card medicine-category h-100">
          <img src="https://img.freepik.com/free-photo/modern-medical-supplies-composition_23-2148899288.jpg" class="card-img-top" alt="Medical Devices">
          <div class="card-body">
            <h5 class="card-title">Medical Devices</h5>
            <p class="card-text">We offer medical devices like thermometers, BP monitors, and diabetic care equipment.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card medicine-category h-100">
          <img src="https://img.freepik.com/free-photo/packaging-pills-pharmaceutical-industry_23-2148825754.jpg" class="card-img-top" alt="Supplements">
          <div class="card-body">
            <h5 class="card-title">Vitamins & Supplements</h5>
            <p class="card-text">Wide variety of vitamins, minerals, and dietary supplements to keep you healthy and active.</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Our Pharmacy. All Rights Reserved.</p>
  </footer>

</body>
</html>
