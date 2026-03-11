<?php
include "../config/db.php";

$lowStockThreshold = 5;

/* ================= SEARCH INPUTS ================= */
$name     = trim($_GET['name'] ?? '');
$Vendor   = trim($_GET['Vendor'] ?? '');
$Batch_No = trim($_GET['Batch_No'] ?? '');
$category = trim($_GET['category'] ?? '');

$lowStockItems = [];

/* ================= LOGIC ================= */
if ($name !== '' || $Vendor !== '' || $Batch_No !== '' || $category !== '') {

    // 🔍 SEARCH MODE (DB handles LIKE + low stock)
    $stmt = $conn->prepare("
        CALL PRC_SEARCH_MEDICINE(
            NULL, ?, ?, ?, ?, 
            @PCODE, @PDESC, @PMSG
        )
    ");

    $stmt->bind_param(
        "ssss",
        $name,
        $Vendor,
        $Batch_No,
        $category
    );

    $stmt->execute();

    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lowStockItems[] = $row;
    }

    $stmt->close();
    while ($conn->more_results() && $conn->next_result()) {;}

} else {

    // ⚠️ DEFAULT MODE (LOW STOCK ONLY)
    $stmt = $conn->prepare("
        SELECT name, Batch_No, Vendor, remaining_stock, MFG, EXP
        FROM medicines
        WHERE remaining_stock <= ?
        ORDER BY remaining_stock ASC
    ");

    $stmt->bind_param("i", $lowStockThreshold);
    $stmt->execute();

    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $lowStockItems[] = $row;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Panel - Low Stock</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f2f4f8;
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

/* Top bar */
.topbar {
    background: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,.05);
}

.circle-btn {
    background: #d63a2f;
    color: white;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    margin-left: 10px;
}

/* Filter panel */
.filter-box {
    background: #f9fafc;
    border-radius: 18px;
    padding: 15px;
    height: 100%;
}

.filter-header {
    background: #c0392b;
    color: white;
    padding: 10px;
    border-radius: 25px;
    text-align: center;
    font-weight: bold;
}

/* Records */
.records-box {
    background: white;
    border-radius: 15px;
    min-height: 500px;
    padding: 15px;
}

.footer-bar {
    padding: 10px;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
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


<!-- TOP BAR -->
<div class="topbar">
    <div class="d-flex align-items-center">
        <strong>LOW STOCK ALERT</strong>

        <!-- <button class="circle-btn"><i class="bi bi-plus"></i></button>
        <button class="circle-btn"><i class="bi bi-file-earmark"></i></button>
        <button class="circle-btn"><i class="bi bi-arrow-clockwise"></i></button> -->
    </div>

    <div>
        <strong>Filter</strong>
        <hr class="m-0">
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container-fluid mt-3">
    <div class="row g-3">

        <!-- LEFT FILTER PANEL -->
        <div class="col-md-3">
            <div class="filter-box shadow-sm">

                <div class="filter-header mb-3"style="background:#1f3c88;">
                    Search Criteria
                </div>

                <!-- <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control">
                </div> -->
<form method="GET"> 

                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Product Name"
                    value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <input type="text" name="category" class="form-control" placeholder="Category"
                    value="<?= htmlspecialchars($_GET['category'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <input type="text" name="Batch_No" class="form-control" placeholder="Batch_No"
                    value="<?= htmlspecialchars($_GET['Batch_No'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <input type="text" name="Vendor" class="form-control" placeholder="Vendor"
                    value="<?= htmlspecialchars($_GET['Vendor'] ?? '') ?>">                               
                </div>

                <!-- <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Email">
                </div> -->

                <!-- <div class="mb-3">
                    <select class="form-select">
                        <option>Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div> -->

                <button class="btn w-100 text-white" style="background:#1f3c88; border-radius:25px;">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>

        </form>
            <div class="d-flex justify-content-center mt-4">
                 <a href="../index.php" class="btn btn-primary btn-sm px-4 py-2 shadow-sm">
                    <i class="bi bi-house-door me-1"></i> Back to Home
                </a>
            </div>

        </div>

        <!-- RIGHT RECORDS PANEL -->
        <div class="col-md-9">
            <div class="records-box shadow-sm">

                <?php if(count($lowStockItems) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Batch</th>
                                <th>Vendor</th>
                                <th>Remaining</th>
                                <th>MFG</th>
                                <th>EXP</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($lowStockItems as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td><?= htmlspecialchars($m['Batch_No']) ?></td>
                                <td><?= htmlspecialchars($m['Vendor']) ?></td>
                                <td><span class="badge bg-danger"><?= $m['remaining_stock'] ?></span></td>
                                <td><?= $m['MFG'] ?></td>
                                <td><?= $m['EXP'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <h5 class="text-muted text-center mt-5">No record found!</h5>
                <?php endif; ?>
            </div>

            <!-- FOOTER -->
            <!-- <div class="footer-bar">
                <div>Items per page: 25</div>
                <div>0 of 0 &nbsp; ⏮ ⏪ ⏩ ⏭</div>
            </div> -->
        </div>

    </div>
</div>

</body>
</html>
