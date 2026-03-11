<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
$page_title="Dashboard";
include "../includes/header.php";

// simple metrics
$tot_meds = $conn->query("SELECT COUNT(*) c FROM medicines")->fetch_assoc()['c'] ?? 0;
$tot_customers = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'] ?? 0;
$tot_sales = $conn->query("SELECT COUNT(*) c FROM sales")->fetch_assoc()['c'] ?? 0;
$sum_sales = $conn->query("SELECT COALESCE(SUM(total),0) s FROM sales")->fetch_assoc()['s'] ?? 0;
?>
<div class="row g-3">
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h6 class="text-muted">Medicines</h6><h3><?php echo $tot_meds; ?></h3></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h6 class="text-muted">Customers</h6><h3><?php echo $tot_customers; ?></h3></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h6 class="text-muted">Sales Count</h6><h3><?php echo $tot_sales; ?></h3></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><h6 class="text-muted">Total Sales</h6><h3>PKR <?php echo number_format($sum_sales,2); ?></h3></div></div></div>
</div>

<div class="row mt-4 g-3">
  <div class="col-md-6">
    <div class="card shadow-sm"><div class="card-body">
      <h5 class="mb-3">Quick Links</h5>
      <a class="btn btn-primary me-2 mb-2" href="/sales/pos.php"><i class="bi bi-bag"></i> New Sale (POS)</a>
      <a class="btn btn-outline-primary me-2 mb-2" href="/admin/medicines.php"><i class="bi bi-capsule"></i> Manage Medicines</a>
      <a class="btn btn-outline-primary me-2 mb-2" href="/admin/customers.php"><i class="bi bi-people"></i> Customers</a>
      <a class="btn btn-outline-primary me-2 mb-2" href="/reports/reports.php"><i class="bi bi-graph-up"></i> Sales Reports</a>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm"><div class="card-body">
      <h5 class="mb-3">Recent Sales</h5>
      <div class="table-responsive"><table class="table table-sm">
        <thead><tr><th>#</th><th>Date</th><th>Customer</th><th>Total</th><th></th></tr></thead>
        <tbody>
        <?php
        $q=$conn->query("SELECT s.id, s.created_at, s.total, c.name customer FROM sales s LEFT JOIN customers c ON s.customer_id=c.id ORDER BY s.id DESC LIMIT 8");
        while($r=$q->fetch_assoc()){
          echo "<tr><td>{$r['id']}</td><td>{$r['created_at']}</td><td>".htmlspecialchars($r['customer']??'Walk-in')."</td><td>PKR ".number_format($r['total'],2)."</td><td><a class='btn btn-sm btn-outline-secondary' href='/sales/invoice.php?id={$r['id']}' target='_blank'>Invoice</a></td></tr>";
        }
        ?>
        </tbody></table></div>
    </div></div>
  </div>
</div>
<?php include "../includes/footer.php"; ?>
