<?php
require_once "includes/auth.php"; 
require_once "config/db.php";

/* ================= SUMMARY ================= */
$total_meds = $conn->query("SELECT COUNT(*) AS c FROM medicines")->fetch_assoc()['c'] ?? 0;
$total_sales = $conn->query("SELECT IFNULL(SUM(total),0) AS t FROM sales")->fetch_assoc()['t'] ?? 0;
$total_customers = $conn->query("SELECT COUNT(*) AS c FROM customers")->fetch_assoc()['c'] ?? 0;
$total_invoices = $conn->query("SELECT COUNT(*) AS c FROM sales")->fetch_assoc()['c'] ?? 0;
$total_cosmetics = $conn->query("SELECT COUNT(*) AS c FROM cosmetics")->fetch_assoc()['c'] ?? 0;

/* ================= SALES LAST 7 DAYS ================= */
$chart_sales_labels = [];
$chart_sales_values = [];

$res_sales = $conn->query("
    SELECT DATE(s.sale_date) AS day, IFNULL(SUM(si.total),0) AS total
    FROM sales s
    JOIN sales_items si ON s.id = si.sale_id
    WHERE s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(s.sale_date)
    ORDER BY DATE(s.sale_date)
");
while($row = $res_sales->fetch_assoc()){
    $chart_sales_labels[] = $row['day'];
    $chart_sales_values[] = $row['total'];
}

/* ================= MEDICINES EXPIRING NEXT 30 DAYS ================= */
$chart_exp_labels = [];
$chart_exp_values = [];

$res_exp = $conn->query("
    SELECT DATE(EXP) AS day, COUNT(*) AS total
    FROM medicines
    WHERE EXP <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(EXP)
    ORDER BY DATE(EXP)
");
while($row = $res_exp->fetch_assoc()){
    $chart_exp_labels[] = $row['day'];
    $chart_exp_values[] = $row['total'];
}

/* ================= TOP SELLING PRODUCTS (MEDICINES + COSMETICS LAST 7 DAYS) ================= */
$chart_top_labels = [];
$chart_top_values = [];
$res_top = $conn->query("
    SELECT 
        COALESCE(m.name, c.name) AS product_name,
        SUM(si.quantity) AS total_sold
    FROM sales s
    JOIN sales_items si ON s.id = si.sale_id
    LEFT JOIN medicines m ON si.medicine_id = m.id
    LEFT JOIN cosmetics c ON si.cosmetic_id = c.id
    WHERE s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY product_name
    ORDER BY total_sold DESC
    LIMIT 5
");
while($row = $res_top->fetch_assoc()){
    $chart_top_labels[] = $row['product_name'];
    $chart_top_values[] = $row['total_sold'];
}

/* ================= CUSTOMER GROWTH LAST 7 DAYS ================= */
$chart_customer_labels = [];
$chart_customer_values = [];
$res_cust = $conn->query("
    SELECT DATE(created_at) AS day, COUNT(*) AS total
    FROM customers
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at)
");
while($row = $res_cust->fetch_assoc()){
    $chart_customer_labels[] = $row['day'];
    $chart_customer_values[] = $row['total'];
}

/* ================= PROFIT LAST 7 DAYS ================= */
// $chart_profit_labels = [];
// $chart_profit_values = [];
// $res_profit = $conn->query("
//     SELECT DATE(s.sale_date) AS day, SUM((si.price - si.Trade_Price) * si.quantity) AS profit
//     FROM sales s
//     JOIN sales_items si ON s.id = si.sale_id
//     WHERE s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
//     GROUP BY DATE(s.sale_date)
//     ORDER BY DATE(s.sale_date)
// ");
// while($row = $res_profit->fetch_assoc()){
//     $chart_profit_labels[] = $row['day'];
//     $chart_profit_values[] = $row['profit'];
// }
$chart_profit_labels = [];
$chart_profit_values = [];
$res_profit = $conn->query("
 SELECT DATE(s.sale_date) AS day,
    COALESCE(SUM((IFNULL(m.price,0) - IFNULL(m.Trade_Price,0)) * si.quantity),0) +
    COALESCE(SUM((IFNULL(c.price,0) - IFNULL(c.Trade_Price,0)) * si.quantity),0) AS profit
FROM sales_items si
LEFT JOIN medicines m ON si.medicine_id = m.id
LEFT JOIN cosmetics c ON si.cosmetic_id = c.id
LEFT JOIN sales s ON si.sale_id = s.id
WHERE s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
GROUP BY DATE(s.sale_date)
ORDER BY DATE(s.sale_date)
");

while($row = $res_profit->fetch_assoc()){
    $chart_profit_labels[] = $row['day'];
    $chart_profit_values[] = $row['profit'];
}

include "includes/header.php"; 
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
</head>
<body>

<?php include "includes/navbar.php"; ?>

<div class="container mt-4">
<h3 class="mb-4 text-center">Dashboard</h3>

<!-- SUMMARY CARDS -->
<div class="row g-4 mb-4">
<?php
$cards = [
    ['Total Medicines',$total_meds,'primary','bi bi-capsule'],
    ['Total Sales (PKR)',number_format($total_sales,2),'success','bi bi-currency-dollar'],
    ['Customers',$total_customers,'warning','bi bi-people'],
    ['Invoices',$total_invoices,'danger','bi bi-receipt'],
    ['Cosmetics',$total_cosmetics,'info','bi bi-bag'],
    ['Profit (PKR)',number_format(array_sum($chart_profit_values),2),'secondary','bi bi-graph-up']
];
foreach($cards as $c):
?>
<div class="col-md-3 col-sm-6">
  <div class="card shadow-sm border-0 text-center py-4 h-100 bg-<?= $c[2] ?> text-white">
    <div class="fs-1 mb-2"><i class="<?= $c[3] ?>"></i></div>
    <h6><?= $c[0] ?></h6>
    <div class="fs-3 fw-bold"><?= $c[1] ?></div>
  </div>
</div>
<?php endforeach; ?>
</div>

<!-- CHARTS -->
<div class="row g-4">
<div class="col-lg-6"><canvas id="salesChart" height="120"></canvas></div>
<div class="col-lg-6"><canvas id="expChart" height="120"></canvas></div>
<div class="col-lg-6"><canvas id="topProductsChart" height="120"></canvas></div>
<div class="col-lg-6"><canvas id="customerChart" height="120"></canvas></div>
<div class="col-lg-6"><canvas id="profitChart" height="120"></canvas></div>
</div>

<script>
const charts = [
  ['salesChart','line',<?= json_encode($chart_sales_labels) ?>,<?= json_encode($chart_sales_values) ?>,'rgba(54,162,235,0.2)','rgba(54,162,235,1)'],
  ['expChart','bar',<?= json_encode($chart_exp_labels) ?>,<?= json_encode($chart_exp_values) ?>,'rgba(220,53,69,0.7)','#dc3545'],
  ['topProductsChart','bar',<?= json_encode($chart_top_labels) ?>,<?= json_encode($chart_top_values) ?>,'rgba(40,167,69,0.7)','#28a745'],
  ['customerChart','line',<?= json_encode($chart_customer_labels) ?>,<?= json_encode($chart_customer_values) ?>,'rgba(255,193,7,0.2)','rgba(255,193,7,1)'],
  ['profitChart','line',<?= json_encode($chart_profit_labels) ?>,<?= json_encode($chart_profit_values) ?>,'rgba(108,117,125,0.2)','rgba(108,117,125,1)']
];

charts.forEach(c=>{
  new Chart(document.getElementById(c[0]),{
    type:c[1],
    data:{
      labels:c[2],
      datasets:[{data:c[3],fill:true,backgroundColor:c[4],borderColor:c[5],tension:0.4,pointRadius:5}]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });
});
</script>

</div>
<?php include "includes/footer.php"; ?>
</body>
</html>
