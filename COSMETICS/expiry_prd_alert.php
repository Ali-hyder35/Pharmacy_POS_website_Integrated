<?php
session_start();
// require_once __DIR__ . '/config/db.php';
include "../config/db.php";
include "../vendor/autoload.php";     
// require_once __DIR__ . '/vendor/autoload.php';



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ================= CONFIG ================= */
define('GMAIL_USER', 'alihaiderg93@gmail.com');
define('GMAIL_APP_PASS', 'ryda zyoe zkyr kknk'); // Gmail App Password

define('ALERT_EMAILS', [
    'alihaiderg93@gmail.com',
    'haiderg93@gmail.com'
]);

/* ================= FETCH EXPIRY ALERTS ================= */
$alerts = [];
$res = $conn->query("
    SELECT *
    FROM cosmetics
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
            'text' => 'No expiring Cosmetics  found.'
        ];
        header("Location: expiry_prd_alert.php");
        exit;
    }

    /* ===== Build Email Body ===== */
    $body = "
        <h2 style='color:#d9534f;'>⚠ Cosmetics Expiry Alert</h2>
        <p>The following Cosmetics will expire within 30 days:</p>
        <table border='1' cellpadding='8' cellspacing='0' width='100%'>
        <tr style='background:#f2f2f2'>
            <th>Product Name</th>
    
            <th>Category</th>
            <th>Rack</th>
            <th>Trade Price</th>
            <th>Price</th>
            <th>PCS_IN_PACK</th>
            <th>Stock</th>
            <th>Remaining Stock</th>
            <th>Manufacturing Date</th>
            <th>Expiry Date</th>
            <th>Batch</th>
            <th>Vendor</th>
            <th>Company Name</th>
            <th>Purchase Date</th>
       
        </tr>
    ";

    foreach ($alerts as $med) {
        $body .= "
        <tr>
            <td>{$med['name']}</td>
     
            <td>{$med['category']}</td>
            <td>{$med['rack']}</td>
            <td>{$med['trade_price']}</td>
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
        
            
        </tr>
        ";
    }

    $body .= "</table><p> <b>— Shahnaz Shabbir Noshahi Pharmacy POS System <b></p>";

    try {
        $mail = new PHPMailer(true);

        /* ===== Gmail SMTP ===== */
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = GMAIL_USER;
        $mail->Password   = GMAIL_APP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        /* ===== Email ===== */
        $mail->setFrom(GMAIL_USER, 'Pharmacy POS');

        foreach (ALERT_EMAILS as $email) {
            $mail->addAddress($email);
        }

        $mail->isHTML(true);
        $mail->Subject = '⚠ Medicine Expiry Alert';
        $mail->Body    = $body;

        $mail->send();

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Email alert sent successfully.'
        ];

    } catch (Exception $e) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Email failed: ' . $mail->ErrorInfo
        ];
    }

    header("Location: expiry_alerts.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Expiry Alerts | Pharmacy POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f9;}
.card{border-radius:14px;}
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

<div class="container mt-4">

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-<?=$_SESSION['message']['type']?> alert-dismissible fade show">
<?= $_SESSION['message']['text'] ?>
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['message']); endif; ?>

<div class="card p-3 shadow">
<h4>⚠ Medicines Expiring in Next 30 Days</h4>

<form method="post">
<button type="submit" name="send_alerts" class="btn btn-danger mb-3">
Send Email Alert
</button>
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
            <th>Product Name</th>
   
            <th>Category</th>
            <th>Rack</th>
            <th>Trade Price</th>
            <th>Price</th>
            <th>PCS_IN_PACK</th>
            <th>Stock</th>
            <th>Remaining Stock</th>
            <th>Manufacturing Date</th>
            <th>Expiry Date</th>
            <th>Batch</th>
            <th>Vendor</th>
            <th>Company Name</th>
            <th>Purchase Date</th>
       
</tr>
</thead>
<tbody>
<?php foreach ($alerts as $med): ?>
<tr>
    <td><?= htmlspecialchars($med['name']) ?></td>

    <td><?= htmlspecialchars($med['category']) ?></td>
    <td><?= htmlspecialchars($med['rack']) ?></td>
    <td><?= htmlspecialchars($med['Trade_Price']) ?></td>
    <td><?= htmlspecialchars($med['price']) ?></td>
    <td><?= htmlspecialchars($med['PCS_IN_PACK']) ?></td>
    <td><?= htmlspecialchars($med['stock']) ?></td>
    <td><?= htmlspecialchars($med['remaining_stock']) ?></td>
    <td><?= htmlspecialchars($med['MFG']) ?></td>
    <td><?= htmlspecialchars($med['EXP']) ?></td>
    <td><?= htmlspecialchars($med['Batch_No']) ?></td>
    <td><?= htmlspecialchars($med['Vendor']) ?></td>
    <td><?= htmlspecialchars($med['Company_Name']) ?></td>
    <td><?= htmlspecialchars($med['Purchase_Date']) ?></td>
 
</tr>

<?php endforeach; ?>
</tbody>
</table>

<?php if (empty($alerts)): ?>
<p class="text-muted">No Cosmetics expiring soon.</p>
<?php endif; ?>

</div>

</div>


</div>
<div class="d-flex justify-content-center mt-4">
    <a href="../index.php" class="btn btn-primary btn-sm px-4 py-2 shadow-sm">
        <i class="bi bi-house-door me-1"></i> Back to Home
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
