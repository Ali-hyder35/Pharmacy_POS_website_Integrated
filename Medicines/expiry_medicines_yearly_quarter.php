<?php
session_start();
include "../config/db.php";
include "../includes/header.php";

/* ================= HANDLE FILTER ================= */
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : '';
$selectedQuarter = isset($_GET['quarter']) ? intval($_GET['quarter']) : '';

$medicines = [];

if ($selectedYear && $selectedQuarter) {
    $monthStart = ($selectedQuarter - 1) * 3 + 1;
    $monthEnd = $monthStart + 2;

    $stmt = $conn->prepare("
        SELECT *
        FROM medicines
        WHERE YEAR(EXP) = ? AND MONTH(EXP) BETWEEN ? AND ?
        ORDER BY EXP ASC
    ");
    $stmt->bind_param("iii", $selectedYear, $monthStart, $monthEnd);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $medicines[] = $row;
    }
    $stmt->close();
}

$currentYear = date('Y');
$years = range($currentYear - 10, $currentYear + 5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pharmacy POS - Expiry Medicines</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<style>
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

<body>

<?php include "../includes/navbar.php"; ?>

<div class="container-fluid mt-4">
<div class="row">

    <!-- ================= LEFT SIDEBAR ================= -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body bg-dark text-white rounded card-blue">
                <h5 class="mb-3 text-center fw-bold">
                    <i class="bi bi-box-seam"></i> Product Actions
                </h5>

                <div class="d-grid gap-2">
                    <a href="add_medicines.php" class="btn btn-outline-light text-start">
                        <i class="bi bi-plus-circle me-2"></i> Add Products
                    </a>
                    <a href="search_medicines.php" class="btn btn-outline-light text-start">
                        <i class="bi bi-search me-2"></i> Search Products
                    </a>
                    <a href="low_stock_alert.php" class="btn btn-outline-light text-start">
                        <i class="bi bi-exclamation-triangle me-2"></i> Low Stock Alerts
                    </a>
                    <a href="expiry_medicines_yearly_quarter.php" class="btn btn-outline-light text-start">
                        <i class="bi bi-calendar3 me-2"></i> Expiry Yearly Report
                    </a>
                    <a href="near_expiry_alerts.php" class="btn btn-outline-light text-start">
                        <i class="bi bi-graph-up me-2"></i> Soon Expire Chart
                    </a>
                    <a href="expiry_alerts.php" class="btn btn-outline-light text-start">
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

    <!-- ================= RIGHT CONTENT ================= -->
    <div class="col-lg-9">

        <h3 class="mb-4 text-center">💊 Expire Medicines By Year & Quarter</h3>

        <form class="row g-2 mb-3" method="get">
            <div class="col-md-3">
                <select name="year" class="form-select" required onchange="this.form.submit()">
                    <option value="">Select Year</option>
                    <?php foreach($years as $y): ?>
                        <option value="<?=$y?>" <?=($y==$selectedYear)?'selected':''?>><?=$y?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($selectedYear): ?>
            <div class="col-md-3">
                <select name="quarter" class="form-select" required onchange="this.form.submit()">
                    <option value="">Select Quarter</option>
                    <?php for($q=1;$q<=4;$q++): ?>
                        <option value="<?=$q?>" <?=($q==$selectedQuarter)?'selected':''?>>Q<?=$q?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <?php endif; ?>
        </form>

        <div class="card shadow-sm">
            <div class="table-responsive p-3">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th><th>Generic</th><th>Category</th><th>Dosage</th>
                            <th>Rack</th><th>Batch</th><th>Price</th><th>Stock</th>
                            <th>Remaining</th><th>MFG</th><th>EXP</th><th>Vendor</th>
                            <th>Purchase Date</th><th>Strength</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($medicines)): ?>
                            <tr><td colspan="14" class="text-center text-muted">No data found</td></tr>
                        <?php else: foreach($medicines as $med): ?>
                        <tr>
                            <td><?=htmlspecialchars($med['name'])?></td>
                            <td><?=htmlspecialchars($med['generic'])?></td>
                            <td><?=htmlspecialchars($med['category'])?></td>
                            <td><?=htmlspecialchars($med['dosage_form'])?></td>
                            <td><?=htmlspecialchars($med['rack'])?></td>
                            <td><?=htmlspecialchars($med['Batch_No'])?></td>
                            <td><?=number_format($med['price'],2)?></td>
                            <td><?=$med['stock']?></td>
                            <td class="<?=($med['remaining_stock']<=10)?'text-danger fw-bold':''?>">
                                <?=$med['remaining_stock']?>
                            </td>
                            <td><?=$med['MFG']?></td>
                            <td><?=$med['EXP']?></td>
                            <td><?=$med['Vendor']?></td>
                            <td><?=$med['Purchase_Date']?></td>
                            <td><?=$med['Strength']?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>

</body>
</html>
