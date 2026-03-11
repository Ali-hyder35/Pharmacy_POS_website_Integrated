<?php

// require_once  "includes/auth.php"; 
// require_once  "config/db.php"; 
include       "../includes/auth.php"; 
include       "../config/db.php";


$msg='';
if(isset($_POST['add']))
  {
      $stmt = $conn->prepare("INSERT INTO customers(name,phone,address) VALUES(?,?,?)");
      $stmt->bind_param("sss",
      $_POST['name'], 
      $_POST['phone'], 
      $_POST['address']); 
      $stmt->execute();
      $msg="Customer added.";
}
if(isset($_GET['delete']))
  { 
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM customers WHERE id=$id");
    $msg  = "Customer deleted."; 
  }
$rows=$conn->query("SELECT * FROM customers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pharmacy POS</title>
  <link rel="icon" type="image/png" href="../images/logo.png">

  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="../CSS/STYLES.css" rel="stylesheet">
  <style>
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
  <h3>Customers</h3>
  <?php if($msg) echo "<div class='alert alert-info'>$msg</div>";?>
  <form method="post" class="row g-2 mb-3">
    <div class="col-md-3"><input class="form-control" name="name" placeholder="Name" required></div>
    <div class="col-md-3"><input class="form-control" name="phone" placeholder="Phone"></div>
    <div class="col-md-4"><input class="form-control" name="address" placeholder="Address"></div>
    <div class="col-md-2"><button class="btn btn-primary w-100" name="add">Add</button></div>
  </form>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($r=$rows->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id']?></td>
        <td><?=$r['name']?></td>
        <td><?=$r['phone']?></td>
        <td><?=$r['address']?></td>
        <td><a class="btn btn-sm btn-danger" 
             onclick="return confirm('Delete?')" 
             href="?delete=<?=$r['id']?>">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>

<?php include "../includes/footer.php"; ?>
