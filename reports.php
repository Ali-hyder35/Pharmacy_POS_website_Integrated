<?php
require_once "includes/auth.php";
require_once "config/db.php";
include "includes/header.php";
include "includes/navbar.php";

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
$expiry = $conn->query("SELECT name, expiry_date FROM medicines WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) ORDER BY expiry_date ASC");

// ================== PROFIT REPORT =================
$profit_sql = "SELECT SUM((m.price - m.Trade_Price) * si.quantity) AS profit
FROM sales_items si
JOIN medicines m ON si.medicine_id = m.id
JOIN sales s ON si.sale_id = s.id
WHERE DATE(s.sale_date) BETWEEN '$start_date' AND '$end_date'";
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

<div class="container mt-4">

<h3>Pharmacy Reports Dashboard</h3>

<!-- ================== FILTER FORM ================== -->
<form method="POST" class="row g-3 mb-4">
<div class="col-md-2">
<input type="date" class="form-control" name="start_date" value="<?=$start_date?>">
</div>
<div class="col-md-2">
<input type="date" class="form-control" name="end_date" value="<?=$end_date?>">
</div>
<div class="col-md-3">
<select class="form-control" name="customer_id">
<option value="All">All Customers</option>
<?php while($c = $customers->fetch_assoc()): ?>
<option value="<?=$c['id']?>" <?=($customer_id==$c['id']?'selected':'')?>><?=$c['name']?></option>
<?php endwhile; ?>
</select>
</div>
<div class="col-md-2">
<button class="btn btn-primary" type="submit">Filter</button>
</div>
</form>

<!-- ================== DAILY SALES ================== -->
<div class="card mb-4">
<div class="card-header bg-primary text-white">
Daily Sales
</div>
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead>
<tr><th>Date</th><th>Total Sales</th></tr>
</thead>
<tbody>
<?php while($r = $daily->fetch_assoc()): ?>
<tr>
<td><?=$r['day']?></td>
<td>PKR <?=number_format($r['total'],2)?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- ================== MONTHLY SALES ================== -->
<div class="card mb-4">
<div class="card-header bg-success text-white">
Monthly Sales
</div>
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead>
<tr><th>Month</th><th>Total</th></tr>
</thead>
<tbody>
<?php while($r = $monthly->fetch_assoc()): ?>
<tr>
<td><?=$r['month']?></td>
<td>PKR <?=number_format($r['total'],2)?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- ================== TOP SELLING ================== -->
<div class="card mb-4">
<div class="card-header bg-dark text-white">
Top Selling Medicines
</div>
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead>
<tr><th>Medicine</th><th>Sold Qty</th></tr>
</thead>
<tbody>
<?php while($r = $top->fetch_assoc()): ?>
<tr>
<td><?=$r['name']?></td>
<td><?=$r['qty']?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- ================== LOW STOCK ================== -->
<div class="card mb-4">
<div class="card-header bg-warning">
Low Stock Medicines
</div>
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead><tr><th>Medicine</th><th>Stock</th></tr></thead>
<tbody>
<?php while($r = $low->fetch_assoc()): ?>
<tr><td><?=$r['name']?></td><td><?=$r['stock']?></td></tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- ================== EXPIRY ================== -->
<div class="card mb-4">
<div class="card-header bg-danger text-white">
Expiring Medicines (30 Days)
</div>
<div class="table-responsive">
<table class="table table-striped mb-0">
<thead><tr><th>Medicine</th><th>Expiry Date</th></tr></thead>
<tbody>
<?php while($r = $expiry->fetch_assoc()): ?>
<tr><td><?=$r['name']?></td><td><?=$r['expiry_date']?></td></tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- ================== PROFIT ================== -->
<div class="alert alert-success">
<h4>Total Estimated Profit: PKR <?=number_format($profitRow['profit'],2)?></h4>
</div>

<!-- ================== CHARTS ================== -->
<h4 class="mt-5">Sales Charts</h4>
<canvas id="revenueChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = [
<?php 
$revenue->data_seek(0);
while($r = $revenue->fetch_assoc()){ echo "'".$r['month']."',"; }
?>
];
const data = [
<?php
$revenue->data_seek(0);
while($r = $revenue->fetch_assoc()){ echo $r['total'].","; }
?>
];

new Chart(document.getElementById("revenueChart"),{
type:'line',
data:{
labels:labels,
datasets:[{
label:'Monthly Revenue',
data:data,
borderWidth:2,
borderColor:'rgba(75, 192, 192, 1)',
backgroundColor:'rgba(75, 192, 192, 0.2)',
fill:true
}]
},
options:{responsive:true, scales:{y:{beginAtZero:true}}}
});
</script>

<?php include "includes/footer.php"; ?>