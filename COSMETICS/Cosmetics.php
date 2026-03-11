

<?php
session_start();
// require_once __DIR__ . '/config/db.php';
include "../config/db.php";


include "../includes/header.php"; 
// include "../includes/navbar.php";




if (isset($_POST['add'])) {

    $stmt = $conn->prepare("
        CALL PRC_COSMETICS_ADD(
          ? , ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            @PCODE, @PDESC, @PMSG
        )
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssssss",
        $_POST['name'],

        $_POST['category'],
        $_POST['rack'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['remaining_stock'],
        $_POST['MFG'],
        $_POST['EXP'],
        $_POST['Batch_No'],
        $_POST['Vendor'],
        $_POST['Company_Name'],
        $_POST['Purchase_Date'],
    
    );

    // Execute procedure
    if (!$stmt->execute()) {
        die("Procedure execution failed: " . $stmt->error);
    }

    // VERY IMPORTANT: clear result sets
    $stmt->close();
    $conn->next_result();

    // Fetch OUT parameters
    $result = $conn->query("SELECT @PCODE AS PCODE, @PDESC AS PDESC, @PMSG AS PMSG");
    $out = $result->fetch_assoc();

   if ($out['PCODE'] === '00') {
    $_SESSION['message'] = [
        'type' => 'success',
        'text' => $out['PDESC']   
    ];
} else {
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => $out['PDESC']
    ];
}


    header("Location: Cosmetics.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product | Pharmacy POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body 
{
    background: #f0f2f5;
    font-family: 'Segoe UI', sans-serif;
}

.card 
{
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.card:hover 
{
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.section-title 
{
    font-size: 1rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 5px;
}

label 
{
    font-weight: 500;
    color: #495057;
}

input.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 8px rgba(13,110,253,0.25);
}

.btn-primary, .btn-success {
    border-radius: 8px;
    padding: 10px 18px;
    font-weight: 500;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.btn-primary:hover {
    background-color: #0b5ed7;
}

.btn-success:hover {
    background-color: #157347;
}

.card-summary {
    border-radius: 12px;
    transition: 0.3s;
    text-align: center;
    padding: 20px;
    margin-bottom: 20px;
}

.card-summary h6 {
    font-weight: 600;
    color: #495057;
}

.card-summary .fs-3 {
    font-weight: 700;
}

.form-floating > input {
    height: 45px;
}



.section-title {
    font-weight: 600;
    color: #0d6efd;
    border-left: 4px solid #0d6efd;
    padding-left: 10px;
    margin-bottom: 10px;
}

.form-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.form-control:focus {
    box-shadow: none;
    border-color: #0d6efd;
}

.badge-section {
    background: #f8f9fa;
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 600;
}


/* NAV BAR*/

.navbar {
    font-size: 15px;
}

.navbar-nav .nav-link {
    padding: 6px 14px;
    border-radius: 20px;
    transition: 0.2s ease;
}

.navbar-nav .nav-link:hover {
    background: rgba(255,255,255,0.1);
}

.navbar-brand img {
    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
}

.btn-outline-light:hover {
    background: #fff;
    color: #000;
}
/* card styling */
.card-blue
{
    background:linear-gradient(135deg,#1f3c88,#2f5aa8);
    color:#fff;
    border-radius:18px;
    transition:all .25s ease;
}
.card-blue:hover
{
    transform:translateY(-3px);
    box-shadow:0 12px 25px rgba(31,60,136,.35);
}
</style>


</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid px-4">

    <!-- LEFT: Logo -->
    <a class="navbar-brand d-flex align-items-center" href="../index.php">
        <img src="../images/logo.png" alt="Logo" height="34" class="me-2 bg-white p-1 rounded">
        <span class="fw-bold fs-5">SSN Pharmacy POS</span>
    </a>

    <!-- Toggle (mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- CENTER + RIGHT -->
    <div class="collapse navbar-collapse" id="navMenu">

      <!-- CENTER MENU -->
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="../Medicines/add_medicines.php">Medicines</a></li>
        <li class="nav-item"><a class="nav-link" href="../Cosmetics/Cosmetics.php">Cosmetics</a></li>
        <li class="nav-item"><a class="nav-link" href="../Customer/customers.php">Customers</a></li>
        <li class="nav-item"><a class="nav-link" href="../pos.php">POS</a></li>
        <li class="nav-item"><a class="nav-link" href="../Sales/sales.php">Sales</a></li>
        <li class="nav-item"><a class="nav-link" href="../Reports/reports.php">Reports</a></li>

        <?php if(($_SESSION['role'] ?? '')==='admin'): ?>
          <li class="nav-item"><a class="nav-link" href="../Admin/admin_users.php">Admin</a></li>
        <?php endif; ?>
      </ul>

      <!-- RIGHT USER -->
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3 text-light small">
          WELCOME, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="../logout.php">Logout</a>
        </li>
      </ul>

    </div>
  </div>
</nav>

<div class="container py-5">


<?php if(isset($_SESSION['message'])): ?>
<div class="alert alert-<?=$_SESSION['message']['type']?> alert-dismissible fade show">
    <?=$_SESSION['message']['text']?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['message']); endif; ?>

<div class="row">
    <!-- LEFT NAV / ACTIONS -->
  <div class="col-lg-3 mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-body bg-dark text-white rounded card-blue">

            <h5 class="mb-3 text-center fw-bold">
                <i class="bi bi-box-seam"></i> Product Actions
            </h5>

            <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-light text-start">
                    <i class="bi bi-plus-circle me-2"></i> Add Products
                </a>

                <a href="SEARCH_COSMETICS.php" class="btn btn-outline-light text-start">
                    <i class="bi bi-search me-2"></i> Search Products
                </a>

                <a href="low_stock_prd_alert.php" class="btn btn-outline-light text-start">
                    <i class="bi bi-exclamation-triangle me-2"></i> Low Stock Alerts
                </a>

                <a href="expiry_products_yearly_quarter.php" class="btn btn-outline-light text-start">
                    <i class="bi bi-calendar3 me-2"></i> Expiry Yearly Report
                </a>

                <a href="near_expiry_prd_alert.php" class="btn btn-outline-light text-start">
                    <i class="bi bi-graph-up me-2"></i> Soon Expire Chart
                </a>

                <a href="expiry_prd_alert.php" class="btn btn-outline-light text-start">
                    <i class="bi bi-x-circle me-2"></i> Expired Products
                </a>

                <a href="../index.php" class="btn btn-light text-dark fw-semibold text-start">
                    <i class="bi bi-house-door me-2"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Section -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-body">

            <h5 class="text-primary fw-bold mb-2">
                <i class="bi bi-cloud-upload me-1"></i> Bulk Upload Products
            </h5>

            <p class="text-muted small">
                Upload Excel file (.xlsx, .xls, .csv) to import products.
            </p>

            <form action="upload_medicines.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                </div>

                <button type="submit" name="upload" class="btn btn-primary w-100">
                    <i class="bi bi-upload me-1"></i> Upload & Import
                </button>
            </form>

        </div>
    </div>
</div>






   <!-- MAIN FORM -->
<div class="col-lg-9">
    <div class="card form-card border-0 p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="text-primary text-center fw-bold mb-1">
                    <i class="bi bi-capsule"></i> Add New Products
                </h4>
                <small class="text-muted text-center">Fill in all required fields for accurate stock & expiry tracking</small>
            </div>
            
        </div>

        <hr>

        <form method="post" class="row g-3">

            <!-- Product Info -->
            <div class="col-12">
                <div class="section-title"> Product Information</div>
            </div>

            <div class="col-md-6 form-floating">
                <input type="text" class="form-control" name="name" id="name" placeholder="Products Name" required>
                <label for="name">Products Name</label>
            </div>

            <div class="col-md-3 form-floating">
                <input type="text" class="form-control" name="category" id="category" placeholder="Category" required>
                <label for="category">Category</label>
            </div>

            <div class="col-md-3 form-floating">
                <input type="text" class="form-control" name="rack" id="rack" placeholder="Rack Location" required>
                <label for="rack">Rack / Location</label>
            </div>

            <!-- Pricing -->
            <div class="col-12 mt-3">
                <div class="section-title">💰 Pricing & Stock</div>
            </div>

            <div class="col-md-4 form-floating">
                <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Price" required>
                <label for="price">Price (PKR)</label>
            </div>

            <div class="col-md-4 form-floating">
                <input type="number" class="form-control" name="stock" id="stock" placeholder="Total Stock" required>
                <label for="stock">Total Stock</label>
            </div>

            <div class="col-md-4 form-floating">
                <input type="number" class="form-control" name="remaining_stock" id="remaining_stock" placeholder="Remaining Stock" required>
                <label for="remaining_stock">Remaining Stock</label>
            </div>

            <div class="col-md-6 form-floating">
                <input type="text" class="form-control" name="Vendor" id="Vendor" placeholder="Vendor" required>
                <label for="Vendor">Vendor / Supplier</label>
            </div>

            <!-- Dates -->
            <div class="col-12 mt-3">
                <div class="section-title">📅 Manufacturing, Expiry & Batch</div>
            </div>

            <div class="col-md-3 form-floating">
                <input type="date" class="form-control" name="MFG" id="MFG" required>
                <label for="MFG">Manufacturing Date</label>
            </div>

            <div class="col-md-3 form-floating">
                <input type="date" class="form-control" name="EXP" id="EXP" required>
                <label for="EXP">Expiry Date</label>
            </div>

            <div class="col-md-3 form-floating">
                <input type="text" class="form-control" name="Batch_No" id="Batch_No" placeholder="Batch Number" required>
                <label for="Batch_No">Batch Number</label>
            </div>

            <div class="col-md-3 form-floating">
                <input type="date" class="form-control" name="Purchase_Date" id="Purchase_Date" required>
                <label for="Purchase_Date">Purchase Date</label>
            </div>

            <!-- Submit -->
            <div class="col-12 mt-4">
                <button type="submit" name="add" class="btn btn-primary btn-lg w-100 shadow-sm">
                    <i class="bi bi-save me-2"></i> Save Product
                </button>
            </div>

        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
