<?php
session_start();
include "../config/db.php";
include "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ================= CONFIG ================= */
define('GMAIL_USER', 'alihaiderg93@gmail.com');
define('GMAIL_APP_PASS', 'ryda zyoe zkyr kknk');

define('ALERT_EMAILS', [
    'alihaiderg93@gmail.com',
    'haiderg93@gmail.com'
]);

/* ================= FETCH EXPIRY ALERTS ================= */
$alerts = [];
$res = $conn->query("
    SELECT *
    FROM medicines
    WHERE EXP <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    ORDER BY EXP ASC
");
while ($row = $res->fetch_assoc()) {
    $alerts[] = $row;
}

/* ================= SEND EMAIL ================= */
if (isset($_POST['send_alerts'])) {

    if (empty($alerts)) {
        $_SESSION['message'] = [
            'type' => 'info',
            'text' => 'No expiring medicines found.'
        ];
        header("Location: expiry_alerts.php");
        exit;
    }

    $body = "
        <h2 style='color:#d9534f;'>⚠ Medicine Expiry Alert</h2>
        <p>The following medicines will expire within 30 days:</p>
        <table border='1' cellpadding='8' cellspacing='0' width='100%'>
        <tr style='background:#f2f2f2'>
            <th>Product Name</th><th>Generic</th><th>Dosage</th><th>Category</th>
            <th>Rack</th><th>Trade Price</th><th>Price</th><th>PCS</th>
            <th>Stock</th><th>Remaining</th><th>MFG</th><th>EXP</th>
            <th>Batch</th><th>Vendor</th><th>Company</th><th>Purchase</th><th>Strength</th>
        </tr>
    ";

    foreach ($alerts as $med) {
        $body .= "
        <tr>
            <td>{$med['name']}</td>
            <td>{$med['generic']}</td>
            <td>{$med['dosage_form']}</td>
            <td>{$med['category']}</td>
            <td>{$med['rack']}</td>
            <td>{$med['Trade_Price']}</td>
            <td>{$med['price']}</td>
            <td>{$med['PCS_IN_PACK']}</td>
            <td>{$med['stock']}</td>
            <td>{$med['remaining_stock']}</td>
            <td>{$med['MFG']}</td>
            <td>{$med['EXP']}</td>
            <td>{$med['Batch_No']}</td>
            <td>{$med['Vendor']}</td>
            <td>{$med['Company_Name']}</td>
            <td>{$med['Purchase_Date']}</td>
            <td>{$med['Strength']}</td>
        </tr>";
    }

    $body .= "</table><p><b>— Shahnaz Shabbir Noshahi Pharmacy POS</b></p>";

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = GMAIL_USER;
        $mail->Password = GMAIL_APP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(GMAIL_USER, 'Pharmacy POS');
        foreach (ALERT_EMAILS as $email) {
            $mail->addAddress($email);
        }

        $mail->isHTML(true);
        $mail->Subject = '⚠ Medicine Expiry Alert';
        $mail->Body = $body;
        $mail->send();

        $_SESSION['message'] = ['type'=>'success','text'=>'Email alert sent successfully.'];

    } catch (Exception $e) {
        $_SESSION['message'] = ['type'=>'danger','text'=>'Email failed'];
    }

    header("Location: expiry_alerts.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Expiry Alerts</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{background:#f4f6f9;}
.card{border-radius:14px;}
.card-blue{
    background:linear-gradient(135deg,#1f3c88,#2f5aa8);
    color:#fff;border-radius:18px;
}
</style>
</head>
<body>

<?php include "../includes/navbar.php"; ?>

<div class="container-fluid mt-4">
<div class="row">

<!-- SIDEBAR -->
<div class="col-lg-3 mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-body card-blue">
            <h5 class="text-center fw-bold mb-3">Product Actions</h5>
            <div class="d-grid gap-2">
                <a href="add_medicines.php" class="btn btn-outline-light">➕ Add</a>
                <a href="search_medicines.php" class="btn btn-outline-light">🔍 Search</a>
                <a href="low_stock_alert.php" class="btn btn-outline-light">⚠ Low Stock</a>
                <a href="expiry_medicines_yearly_quarter.php" class="btn btn-outline-light">📅 Report</a>
                <a href="near_expiry_alerts.php" class="btn btn-outline-light">📊 Soon</a>
                <a href="expiry_alerts.php" class="btn btn-outline-light">❌ Expired</a>
                <a href="../index.php" class="btn btn-light fw-semibold">🏠 Dashboard</a>
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

<!-- MAIN CONTENT -->
<div class="col-lg-9">

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-<?=$_SESSION['message']['type']?> alert-dismissible fade show">
<?= $_SESSION['message']['text'] ?>
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['message']); endif; ?>

<div class="card p-3 shadow">
<h4 class="text-center mb-3"> ⚠ Medicines Expiring in Next 30 Days</h4>

<form method="post">
<button name="send_alerts" class="btn w-100 btn-danger mb-3">Send Email Alert</button>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>Name</th><th>Generic</th><th>Dosage</th><th>Category</th><th>Rack</th>
<th>Trade</th><th>Price</th><th>PCS</th><th>Stock</th><th>Remaining</th>
<th>MFG</th><th>EXP</th><th>Batch</th><th>Vendor</th><th>Company</th><th>Purchase</th><th>Strength</th>
</tr>
</thead>
<tbody>
<?php foreach ($alerts as $med): ?>
<tr>
<td><?=htmlspecialchars($med['name'])?></td>
<td><?=htmlspecialchars($med['generic'])?></td>
<td><?=htmlspecialchars($med['dosage_form'])?></td>
<td><?=htmlspecialchars($med['category'])?></td>
<td><?=htmlspecialchars($med['rack'])?></td>
<td><?=htmlspecialchars($med['Trade_Price'])?></td>
<td><?=htmlspecialchars($med['price'])?></td>
<td><?=htmlspecialchars($med['PCS_IN_PACK'])?></td>
<td><?=htmlspecialchars($med['stock'])?></td>
<td><?=htmlspecialchars($med['remaining_stock'])?></td>
<td><?=htmlspecialchars($med['MFG'])?></td>
<td><?=htmlspecialchars($med['EXP'])?></td>
<td><?=htmlspecialchars($med['Batch_No'])?></td>
<td><?=htmlspecialchars($med['Vendor'])?></td>
<td><?=htmlspecialchars($med['Company_Name'])?></td>
<td><?=htmlspecialchars($med['Purchase_Date'])?></td>
<td><?=htmlspecialchars($med['Strength'])?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?php if (empty($alerts)): ?>
<p class="text-muted">No medicines expiring soon.</p>
<?php endif; ?>

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
