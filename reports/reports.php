<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// ================== FILTER ==================
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
$end_date   = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : 'All';

// ================== DAILY SALES =================
$daily_sql = "SELECT DATE(sale_date) AS day, SUM(total) AS total
FROM sales WHERE DATE(sale_date) BETWEEN '$start_date' AND '$end_date'";
if($customer_id != 'All'){
    $daily_sql .= " AND customer_id = $customer_id";
}
$daily_sql .= " GROUP BY DATE(sale_date) ORDER BY day DESC";
$daily = $conn->query($daily_sql);

// ================== MONTHLY SALES =================
$monthly_sql = "SELECT DATE_FORMAT(sale_date,'%Y-%m') AS month, SUM(total) AS total
FROM sales WHERE DATE(sale_date) BETWEEN '$start_date' AND '$end_date'";
if($customer_id != 'All'){
    $monthly_sql .= " AND customer_id = $customer_id";
}
$monthly_sql .= " GROUP BY month ORDER BY month DESC";
$monthly = $conn->query($monthly_sql);

// ================== TOP SELLING MEDICINES =================
$top_sql = "SELECT m.name, SUM(si.quantity) AS qty
FROM sales_items si
JOIN medicines m ON si.medicine_id = m.id
JOIN sales sa ON si.sale_id = sa.id
WHERE DATE(sa.sale_date) BETWEEN '$start_date' AND '$end_date'";
if($customer_id != 'All'){
    $top_sql .= " AND sa.customer_id = $customer_id";
}
$top_sql .= " GROUP BY si.medicine_id ORDER BY qty DESC LIMIT 10";
$top = $conn->query($top_sql);

// ================== LOW STOCK =================
$low = $conn->query("SELECT name, stock FROM medicines WHERE stock <= 10 ORDER BY stock ASC");

// ================== EXPIRY MEDICINES =================
$expiry = $conn->query("SELECT name, EXP FROM medicines WHERE EXP <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) ORDER BY EXP ASC");

// ================== PROFIT REPORT (Medicines + Cosmetics) =================
$profit_sql = "
SELECT 
    COALESCE(SUM((IFNULL(m.price,0) - IFNULL(m.Trade_Price,0)) * si.quantity),0) +
    COALESCE(SUM((IFNULL(c.price,0) - IFNULL(c.Trade_Price,0)) * si.quantity),0) AS profit
FROM sales_items si
LEFT JOIN medicines m ON si.medicine_id = m.id
LEFT JOIN cosmetics c ON si.cosmetic_id = c.id
LEFT JOIN sales s ON si.sale_id = s.id
WHERE DATE(s.sale_date) BETWEEN '$start_date' AND '$end_date'
";

if($customer_id != 'All'){
    $profit_sql .= " AND s.customer_id = $customer_id";
}

$profit = $conn->query($profit_sql);
$profitRow = $profit->fetch_assoc();

// ================== MONTHLY REVENUE =================
$revenue_sql = "SELECT DATE_FORMAT(sale_date,'%Y-%m') AS month, SUM(total) AS total
FROM sales WHERE DATE(sale_date) BETWEEN '$start_date' AND '$end_date'";
if($customer_id != 'All'){
    $revenue_sql .= " AND customer_id = $customer_id";
}
$revenue_sql .= " GROUP BY month ORDER BY month ASC";
$revenue = $conn->query($revenue_sql);

// ================== GET CUSTOMERS =================
$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Pharmacy System</title>
<link rel="icon" href="../images/logo.png" type="image/png">
<link href="../CSS/STYLES.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.navbar { font-size: 15px; }
.navbar-nav .nav-link { padding: 6px 14px; border-radius: 20px; transition: 0.2s ease; }
.navbar-nav .nav-link:hover { background: rgba(255,255,255,0.1); }
.navbar-brand img { box-shadow: 0 2px 6px rgba(0,0,0,0.4); }
.btn-outline-light:hover { background: #fff; color: #000; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand d-flex align-items-center" href="../index.php">
        <img src="../images/logo.png" alt="Logo" height="34" class="me-2 bg-white p-1 rounded">
        <span class="fw-bold fs-5">SSN Pharmacy POS</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shahnaz-Shabbir-Noshahi-Pharmacy/Medicines/add_medicines.php">Medicines</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shahnaz-Shabbir-Noshahi-Pharmacy/Cosmetics/Cosmetics.php">Cosmetics</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shahnaz-Shabbir-Noshahi-Pharmacy/Customer/customers.php">Customers</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shahnaz-Shabbir-Noshahi-Pharmacy/pos.php">POS</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shahnaz-Shabbir-Noshahi-Pharmacy/Sales/sales.php">Sales</a></li>
        <li class="nav-item"><a class="nav-link" href="/Shahnaz-Shabbir-Noshahi-Pharmacy/Reports/reports.php">Reports</a></li>
        <?php if(($_SESSION['role'] ?? '')==='admin'): ?>
          <li class="nav-item"><a class="nav-link" href="../Admin/admin_users.php">Admin</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3 text-light small">WELCOME, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong></li>
        <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
<h3 class="text-center">Pharmacy Reports Dashboard</h3>

<!-- FILTER FORM -->
<form method="POST" class="row g-3 mb-4">
  <div class="col-md-2"><input type="date" class="form-control" name="start_date" value="<?=$start_date?>"></div>
  <div class="col-md-2"><input type="date" class="form-control" name="end_date" value="<?=$end_date?>"></div>
  <div class="col-md-3">
    <select class="form-control" name="customer_id">
      <option value="All">All Customers</option>
      <?php while($c = $customers->fetch_assoc()): ?>
      <option value="<?=$c['id']?>" <?=($customer_id==$c['id']?'selected':'')?>><?=$c['name']?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-2"><button class="btn btn-primary" type="submit">Filter</button></div>
</form>

<!-- DAILY SALES -->
<div class="card mb-4">
  <div class="card-header bg-primary text-white">Daily Sales</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead><tr><th>Date</th><th>Total Sales</th></tr></thead>
      <tbody><?php while($r = $daily->fetch_assoc()): ?>
        <tr><td><?=$r['day']?></td><td>PKR <?=number_format($r['total'],2)?></td></tr>
      <?php endwhile; ?></tbody>
    </table>
  </div>
</div>

<!-- MONTHLY SALES -->
<div class="card mb-4">
  <div class="card-header bg-success text-white">Monthly Sales</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead><tr><th>Month</th><th>Total</th></tr></thead>
      <tbody><?php while($r = $monthly->fetch_assoc()): ?>
        <tr><td><?=$r['month']?></td><td>PKR <?=number_format($r['total'],2)?></td></tr>
      <?php endwhile; ?></tbody>
    </table>
  </div>
</div>

<!-- TOP SELLING -->
<div class="card mb-4">
  <div class="card-header bg-dark text-white">Top Selling Medicines</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead><tr><th>Medicine</th><th>Sold Qty</th></tr></thead>
      <tbody><?php while($r = $top->fetch_assoc()): ?>
        <tr><td><?=$r['name']?></td><td><?=$r['qty']?></td></tr>
      <?php endwhile; ?></tbody>
    </table>
  </div>
</div>

<!-- LOW STOCK -->
<div class="card mb-4">
  <div class="card-header bg-warning">Low Stock Medicines</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead><tr><th>Medicine</th><th>Stock</th></tr></thead>
      <tbody><?php while($r = $low->fetch_assoc()): ?>
        <tr><td><?=$r['name']?></td><td><?=$r['stock']?></td></tr>
      <?php endwhile; ?></tbody>
    </table>
  </div>
</div>

<!-- EXPIRY -->
<div class="card mb-4">
  <div class="card-header bg-danger text-white">Expiring Medicines (30 Days)</div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead><tr><th>Medicine</th><th>Expiry Date</th></tr></thead>
      <tbody><?php while($r = $expiry->fetch_assoc()): ?>
        <tr><td><?=$r['name']?></td><td><?=$r['EXP']?></td></tr>
      <?php endwhile; ?></tbody>
    </table>
  </div>
</div>

<!-- PROFIT -->
<div class="alert alert-success">
<h4>Total Estimated Profit: PKR <?=number_format($profitRow['profit'],2)?></h4>
</div>

<!-- SALES CHART -->
<h4 class="mt-5">Monthly Revenue Chart</h4>
<canvas id="revenueChart" height="100"></canvas>

<script>
const labels = [
<?php $revenue->data_seek(0); while($r=$revenue->fetch_assoc()){ echo "'".$r['month']."',"; } ?>
];
const data = [
<?php $revenue->data_seek(0); while($r=$revenue->fetch_assoc()){ echo $r['total'].","; } ?>
];
new Chart(document.getElementById("revenueChart"),{
    type:'line',
    data:{ labels:labels, datasets:[{ label:'Monthly Revenue', data:data, borderWidth:2, borderColor:'rgba(75, 192, 192, 1)', backgroundColor:'rgba(75, 192, 192, 0.2)', fill:true }] },
    options:{ responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>

</div>
</body>
</html>

<?php include "../includes/footer.php"; ?>