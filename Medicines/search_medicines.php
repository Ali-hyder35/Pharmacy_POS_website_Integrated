<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

/* ================= HANDLE DELETE ================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("CALL PRC_MEDICINE_DEL(?, @PCODE, @PDESC, @PMSG)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    while ($conn->more_results() && $conn->next_result()) { $conn->use_result(); }

    $out = $conn->query("SELECT @PCODE AS PCODE, @PDESC AS PDESC")->fetch_assoc();

    $_SESSION['message'] = [
        'type' => ($out['PCODE'] === '00') ? 'success' : 'danger',
        'text' => $out['PDESC']
    ];

    header("Location: search_medicines.php?page=$page&search=" . urlencode($search));
    exit;
}

/* ================= HANDLE UPDATE ================= */
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("
        CALL PRC_MEDICINE_UPD(
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            @PCODE, @PDESC, @PMSG
        )
    ");

    $stmt->bind_param(
        "issssssssssssss",
        $id,
        $_POST['name'],
        $_POST['generic'],
        $_POST['dosage_form'],
        $_POST['category'],
        $_POST['rack'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['remaining_stock'],
        $_POST['MFG'],
        $_POST['EXP'],
        $_POST['Batch_No'],
        $_POST['Vendor'],
        $_POST['Purchase_Date'],
        $_POST['Strength']
    );

    $stmt->execute();
    $stmt->close();

    while ($conn->more_results() && $conn->next_result()) { $conn->use_result(); }

    $out = $conn->query("SELECT @PCODE AS PCODE, @PDESC AS PDESC")->fetch_assoc();

    $_SESSION['message'] = [
        'type' => ($out['PCODE'] === '00') ? 'success' : 'danger',
        'text' => $out['PDESC']
    ];

    header("Location: search_medicines.php?page=$page&search=" . urlencode($search));
    exit;
}

/* ================= PAGINATION ================= */
$limit  = 10;
$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

/* ================= SEARCH ================= */

// $search    = isset($_GET['search']) ? trim($_GET['search']) : '';
// $medicines = [];

/* ================= SEARCH INPUTS ================= */
$name     = trim($_GET['name'] ?? '');
$generic  = trim($_GET['generic'] ?? '');
$category = trim($_GET['category'] ?? '');
$batch    = trim($_GET['batch'] ?? '');

$medicines = [];
    

/* ================= PROCEDURE CALL ================= */
if ($name !== '' || $generic !== '' || $category !== '' || $batch !== '') 
    {
    $stmt = $conn->prepare("
        CALL PRC_SEARCH_MEDICINE(
            NULL, ?, ?, ?, ?, 
            @PCODE, @PDESC, @PMSG
        )
    ");
        
    $stmt->bind_param("ssss", $name, $generic, $category, $batch);

    }

else

    {
        $stmt = $conn->prepare("CALL PRC_SEARCH_ALL_MEDICINE(@PCODE, @PDESC, @PMSG)");
    }

$stmt->execute();

/* ================= FETCH RESULT ================= */
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
}

$stmt->close();
while ($conn->more_results() && $conn->next_result()) { $conn->use_result(); }

$out = $conn->query("SELECT @PCODE AS PCODE, @PDESC AS PDESC")->fetch_assoc();


/* ================= UI PAGINATION ================= */

$total_records = count($medicines);
$total_pages   = ceil($total_records / $limit);
$medicines     = array_slice($medicines, $offset, $limit);

/* ================= LOW STOCK ================= */

$lowStockItems = array_filter($medicines, fn($m) => $m['remaining_stock'] <= 10);

?>



                                                    <!-- HTML CODE -->




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Medicines | Pharmacy POS</title>
<link href="../CSS/STYLES.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
 body { background:#f2f4f8; }
 .card, .records-box { border-radius:15px; }
 .filter-box { background:#f9fafc; border-radius:18px; padding:15px; }
 .filter-header { background:#c0392b; color:#fff; padding:10px; border-radius:25px; text-align:center; font-weight:bold; }
 .filter-box .form-control { border-radius:10px; padding:10px 14px; }
 .filter-box .form-control:focus { box-shadow:none; border-color:#c0392b; }
 .records-box { background:#fff; padding:15px; border-radius:15px; }
 .table-hover tbody tr:hover { background:#fff3f3; transition:0.2s; }
 .low-stock { color:#c0392b; font-weight:bold; }
 .row.g-1 { display: flex; align-items: stretch; }
 .col-md-3 .filter-box, .col-md-9 .records-box { height: 100%; }
 .modal-header { border-bottom:none; }
 .modal-footer { border-top:none; }
</style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
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
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3 text-light small">WELCOME, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong></li>
        <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ================= TOPBAR ================= -->
<div class="topbar bg-white p-3 d-flex justify-content-between shadow-sm">
    <strong>SEARCH MEDICINES</strong>
    <strong>Filter</strong>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="container-fluid mt-3">
<div class="row g-1">

<!-- =============== FILTER BOX =============== -->
<div class="col-md-3">
<div class="filter-box shadow-sm">
<div class="filter-header mb-3" style="background:#1f3c88;">Search Criteria</div>

<form method="get">

    <div class="mb-3">
        <input type="text" name="name" class="form-control"
           placeholder="By Name"
           value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <input type="text" name="generic" class="form-control"
           placeholder="By Generic"
           value="<?= htmlspecialchars($_GET['generic'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <input type="text" name="category" class="form-control"
           placeholder="By Category"
           value="<?= htmlspecialchars($_GET['category'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <input type="text" name="batch" class="form-control"
           placeholder="By Batch No"
           value="<?= htmlspecialchars($_GET['batch'] ?? '') ?>">
    </div>

    <button class="btn btn-primary w-100 text-white" style="background:#1f3c88;border-radius:25px;">
        🔍 Search
    </button>
</form>


<div class="text-center mt-4">
    <a href="../index.php" class="btn w-100 btn-primary btn-sm px-4">🏠 Back to Home</a>
</div>
</div>
</div>

<!-- =============== RECORDS BOX =============== -->
<div class="col-md-9">
<div class="records-box shadow-sm">
<h4 class="mb-3 text-center">💊 All Medicines</h4>
<table class="table table-bordered table-striped table-hover align-middle">
<thead class="table-dark">
<tr>
    <th>Name</th>
    <th>Generic</th>
    <th>Category</th>
    <th>Rack</th>
    <th>Batch</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Remaining</th>
    <th>MFG</th>
    <th>EXP</th>
    <th>Vendor</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php if(empty($medicines)): ?>
<tr><td colspan="12" class="text-center text-muted">No medicines found</td></tr>
<?php else: foreach($medicines as $m): ?>
<tr>
    <td><?= htmlspecialchars($m['name']) ?></td>
    <td><?= htmlspecialchars($m['generic']) ?></td>
    <td><?= htmlspecialchars($m['category']) ?></td>
    <td><?= htmlspecialchars($m['rack']) ?></td>
    <td><?= htmlspecialchars($m['Batch_No']) ?></td>
    <td><?= number_format($m['price'],2) ?></td>
    <td><?= htmlspecialchars($m['stock']) ?></td>
    <td class="<?= ($m['remaining_stock']<=10)?'low-stock':'' ?>"><?= htmlspecialchars($m['remaining_stock']) ?></td>
    <td><?= htmlspecialchars($m['MFG']) ?></td>
    <td><?= htmlspecialchars($m['EXP']) ?></td>
    <td><?= htmlspecialchars($m['Vendor']) ?></td>
    <td class="text-center">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#editModal<?= $m['id'] ?>">
                <i class="bi bi-pencil-fill"></i> Edit
            </button>
            <a href="?delete=<?= $m['id'] ?>&page=<?= $page ?>&search=<?= urlencode($search) ?>"
               class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
               onclick="return confirm('Delete this medicine?')">
               <i class="bi bi-trash-fill"></i> Delete
            </a>
        </div>

        <!-- ============== EDIT MODAL ============== -->
        <div class="modal fade" id="editModal<?= $m['id'] ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Medicine: <?= htmlspecialchars($m['name']) ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($m['name']) ?>" required></div>
                        <div class="col-md-6"><label class="form-label">Generic</label>
                            <input type="text" name="generic" class="form-control" value="<?= htmlspecialchars($m['generic']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Dosage Form</label>
                            <input type="text" name="dosage_form" class="form-control" value="<?= htmlspecialchars($m['dosage_form']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($m['category']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Rack</label>
                            <input type="text" name="rack" class="form-control" value="<?= htmlspecialchars($m['rack']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($m['price']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($m['stock']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Remaining Stock</label>
                            <input type="number" name="remaining_stock" class="form-control" value="<?= htmlspecialchars($m['remaining_stock']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">MFG</label>
                            <input type="date" name="MFG" class="form-control" value="<?= htmlspecialchars($m['MFG']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">EXP</label>
                            <input type="date" name="EXP" class="form-control" value="<?= htmlspecialchars($m['EXP']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Batch No</label>
                            <input type="text" name="Batch_No" class="form-control" value="<?= htmlspecialchars($m['Batch_No']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Vendor</label>
                            <input type="text" name="Vendor" class="form-control" value="<?= htmlspecialchars($m['Vendor']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Purchase Date</label>
                            <input type="date" name="Purchase_Date" class="form-control" value="<?= htmlspecialchars($m['Purchase_Date']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Strength</label>
                            <input type="text" name="Strength" class="form-control" value="<?= htmlspecialchars($m['Strength']) ?>"></div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" name="update" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- ============ END MODAL ============ -->
    </td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>

<!-- =============== PAGINATION =============== -->
<nav>
<ul class="pagination justify-content-center">
<?php for($i=1;$i<=$total_pages;$i++): ?>
<li class="page-item <?= $i==$page?'active':'' ?>">
  <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
