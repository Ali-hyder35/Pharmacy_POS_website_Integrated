<?php
include "../includes/auth.php";
include "../config/db.php";
// include "../includes/header.php";
// include "../includes/navbar.php"; 

/* GET SALES DATA */
$res = $conn->query("
SELECT 
s.id,
s.total,
s.payment_method,
s.sale_date,
c.name AS customer,
u.username AS cashier

FROM sales s

LEFT JOIN customers c 
ON s.customer_id = c.id 

LEFT JOIN users u 
ON s.user_id = u.id 

ORDER BY s.id DESC
");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pharmacy POS</title>
     <style>
    /* body {
      background: #12ebe0;
    } */
    /* NAV BAR*/

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
  <link rel="icon" type="image/png" href="../images/logo.png">

  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="../CSS/STYLES.css" rel="stylesheet">

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

<h3 class="text-center">Sales History</h3>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Customer</th>
<th>Salesman</th>
<th>Payment</th>
<th>Total</th>
<th>Date</th>
<th>Invoice</th>

</tr>

</thead>

<tbody>

<?php while($r=$res->fetch_assoc()): ?>

<tr>

<td><?=$r['id']?></td>

<td>
<?=$r['customer'] ? $r['customer'] : 'Walk-in'?>
</td>

<td><?=$r['cashier']?></td>

<td><?=ucfirst($r['payment_method'])?></td>

<td>PKR <?=number_format($r['total'],2)?></td>

<td><?=$r['sale_date']?></td>

<td>
<a class="btn btn-sm btn-primary"
href="invoice.php?sale_id=<?=$r['id']?>">
View Invoice
</a>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>
</body>
</html>
<?php include "includes/footer.php"; ?>