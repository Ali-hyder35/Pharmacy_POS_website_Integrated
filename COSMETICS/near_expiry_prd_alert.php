<?php
include '../config/db.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ================= SEND EMAIL ON BUTTON CLICK ================= */
$alertMsg = '';

if (isset($_POST['send_email'])) {

    $q = $conn->query("
        SELECT 
            YEAR(EXP) y,
            QUARTER(EXP) q,
            name,generic,dosage_form,category,rack,price,stock,remaining_stock,
            MFG,EXP,Batch_No,Vendor,Purchase_Date,Strength
        FROM medicines
        WHERE EXP >= CURDATE()
          AND EXP <= DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
        ORDER BY y,q,EXP
    ");

    $data = [];
    while ($r = $q->fetch_assoc()) {
        $key = "Q{$r['q']}-{$r['y']}";
        $data[$key][] = $r;
    }

    try {
        foreach ($data as $quarter => $rows) {

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alihaiderg93@gmail.com';
            $mail->Password   = 'ryda zyoe zkyr kknk';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('alihaiderg93@gmail.com', 'Shahnaz Shabbir Noshahi Pharmacy');

            foreach (['haiderg93@gmail.com','alihaiderg93@gmail.com','misbahhaider17@gmail.com'] as $email) {
                $mail->addAddress($email);
            }

            $mail->Subject = "$quarter Expiry Alert";

            $body = "<h3>$quarter Expiring Products</h3><table border='1' cellpadding='6'>";
            $body .= "<tr><th>Name</th><th>Category</th>
                      <th>Rack</th><th>Price</th><th>Stock</th><th>Remaining</th>
                      <th>MFG</th><th>EXP</th><th>Batch</th><th>Vendor</th></tr>";

            foreach ($rows as $m) {
                $body .= "<tr>
                    <td>{$m['name']}</td>
                    <td>{$m['category']}</td>
                    <td>{$m['rack']}</td>
                    <td>{$m['price']}</td>
                    <td>{$m['stock']}</td>
                    <td>{$m['remaining_stock']}</td>
                    <td>{$m['MFG']}</td>
                    <td>{$m['EXP']}</td>
                    <td>{$m['Batch_No']}</td>
                    <td>{$m['Vendor']}</td>
                </tr>";
            }

            $body .= "</table>";
            $mail->isHTML(true);
            $mail->Body = $body;
            $mail->send();
        }

        $alertMsg = "<div class='alert alert-success text-center mt-3'>✔ Expiry alert email sent successfully</div>";

    } catch (Exception $e) {
        $alertMsg = "<div class='alert alert-danger text-center mt-3'>❌ Email failed</div>";
    }
}

/* ================= CHART DATA ================= */
$labels = [];
$values = [];

$q = $conn->query("
    SELECT YEAR(EXP) y, QUARTER(EXP) q, COUNT(*) total
    FROM cosmetics
    WHERE EXP >= CURDATE()
      AND EXP <= DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
    GROUP BY y,q
    ORDER BY y,q
");

while ($r = $q->fetch_assoc()) {
    $labels[] = "Q{$r['q']}-{$r['y']}";
    $values[] = $r['total'];
}

/* ================= MEDICINE LIST ================= */
$list = $conn->query("
    SELECT *, YEAR(EXP) y, QUARTER(EXP) q
    FROM cosmetics
    WHERE EXP >= CURDATE()
      AND EXP <= DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
    ORDER BY EXP
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Soon To Expire Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body{background:#f4f6f9;}
.card{border-radius:14px;}

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
</head>
<body>

<?php include "../includes/navbar.php"; ?>

<div class="container-fluid mt-4">
<div class="row">

<!-- ================= LEFT SIDEBAR ================= -->
<div class="col-lg-3 mb-4">

    <div class="card shadow-sm border-0">
        <div class="card-body bg-dark text-white rounded card-blue">
            <h5 class="text-center fw-bold mb-3">
                <i class="bi bi-box-seam"></i> Product Actions
            </h5>
                <div class="d-grid gap-2">
                    <a href="Cosmetics.php" class="btn btn-outline-light text-start">
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
                    <a href="expiry_prd_alerts.php" class="btn btn-outline-light text-start">
                        <i class="bi bi-x-circle me-2"></i> Expired Products
                    </a>
                    <a href="../index.php" class="btn btn-light text-dark fw-semibold text-start">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </div>
        </div>
    </div>
        <div class="card mt-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="text-primary fw-bold mb-2">
                    <i class="bi bi-cloud-upload me-1"></i> Bulk Upload Products
                </h5>

                <form action="upload_medicines.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="excel_file" class="form-control mb-3" required>
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-upload me-1"></i> Upload & Import
                    </button>
                </form>
            </div>
        </div>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="col-lg-9">
<div class="card p-4 shadow-sm">

<h3 class="text-center mb-3">Soon To Expire Products (Next 12 Months)</h3>

<form method="post" class="text-center mb-3">
    <button class="btn btn-success" name="send_email">📧 Send Expiry Alert Email</button>
</form>

<?= $alertMsg ?>

<canvas id="expiryChart" height="120"></canvas>

<script>
new Chart(document.getElementById('expiryChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Products Expiring',
            data: <?= json_encode($values) ?>,
            borderWidth: 1
        }]
    }
});
</script>

<hr>

<h4 class="text-center">ProductS Details</h4>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
<thead class="table-dark">
<tr>
<th>Name</th><th>Category</th>
<th>Rack</th><th>Price</th><th>Stock</th><th>Remaining</th>
<th>MFG</th><th>EXP</th><th>Batch</th><th>Vendor</th><th>Quarter</th>
</tr>
</thead>
<tbody>
<?php while ($m = $list->fetch_assoc()): ?>
<tr>
<td><?=htmlspecialchars($m['name'])?></td>
<td><?=htmlspecialchars($m['category'])?></td>
<td><?=htmlspecialchars($m['rack'])?></td>
<td><?=htmlspecialchars($m['price'])?></td>
<td><?=htmlspecialchars($m['stock'])?></td>
<td><?=htmlspecialchars($m['remaining_stock'])?></td>
<td><?=htmlspecialchars($m['MFG'])?></td>
<td><?=htmlspecialchars($m['EXP'])?></td>
<td><?=htmlspecialchars($m['Batch_No'])?></td>
<td><?=htmlspecialchars($m['Vendor'])?></td>
<td><?= "Q{$m['q']}-{$m['y']}" ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>
</div>
</div>
</div>

</body>
</html>
