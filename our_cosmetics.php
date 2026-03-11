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
    <h1>Our Cosmetics</h1>
    <p>We provide a wide range of trusted cosmetics for your beauty and wellness needs.</p>
  </div>


 <!-- Cosmetics Section -->
<div class="container py-5">
  <div class="row g-4">
    
    <div class="col-md-4">
      <div class="card medicine-category h-100">
        <img src="https://img.freepik.com/free-photo/makeup-cosmetics-set-with-powder-brushes_23-2148514951.jpg" class="card-img-top" alt="Makeup Products">
        <div class="card-body">
          <h5 class="card-title">Makeup Products</h5>
          <p class="card-text">Explore our premium range of makeup including foundations, lipsticks, mascaras, and beauty tools for a perfect look.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card medicine-category h-100">
        <img src="https://img.freepik.com/free-photo/various-beauty-products-arrangement_23-2149440016.jpg" class="card-img-top" alt="Skincare Products">
        <div class="card-body">
          <h5 class="card-title">Skincare Products</h5>
          <p class="card-text">High-quality skincare solutions including moisturizers, serums, cleansers, and sunscreens for healthy glowing skin.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card medicine-category h-100">
        <img src="https://img.freepik.com/free-photo/hair-care-products-arrangement_23-2149440008.jpg" class="card-img-top" alt="Hair Care">
        <div class="card-body">
          <h5 class="card-title">Hair Care</h5>
          <p class="card-text">Complete hair care products including shampoos, conditioners, oils, and treatments to keep your hair strong and shiny.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card medicine-category h-100">
        <img src="https://img.freepik.com/free-photo/perfume-bottle-with-beauty-products_23-2149451493.jpg" class="card-img-top" alt="Fragrances">
        <div class="card-body">
          <h5 class="card-title">Fragrances</h5>
          <p class="card-text">Discover a wide range of perfumes, body sprays, and deodorants from top brands to keep you fresh all day.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card medicine-category h-100">
        <img src="https://img.freepik.com/free-photo/beauty-products-with-copy-space_23-2149440001.jpg" class="card-img-top" alt="Beauty Accessories">
        <div class="card-body">
          <h5 class="card-title">Beauty Accessories</h5>
          <p class="card-text">Essential beauty tools including brushes, sponges, mirrors, and grooming kits for everyday beauty routines.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card medicine-category h-100">
        <img src="https://img.freepik.com/free-photo/natural-cosmetics-products_23-2148882607.jpg" class="card-img-top" alt="Organic Cosmetics">
        <div class="card-body">
          <h5 class="card-title">Organic Cosmetics</h5>
          <p class="card-text">Natural and organic cosmetic products made with safe ingredients to nourish your skin and enhance beauty.</p>
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
