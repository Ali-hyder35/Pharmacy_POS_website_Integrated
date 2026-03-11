
<?php
//require_once "includes/auth.php";

include "../includes/auth.php";
include '../config/db.php';

include "../includes/header.php";
include "../includes/navbar.php";

/* ----------------------------------------------
   ADD MEDICINE (Insert or Increase Stock)
---------------------------------------------- */

// --- Handle POST/GET actions with PRG pattern ---
// if (isset($_POST['add'])) {

//     // --- 1) Sanitize and cast numeric values ---
//     $stock = isset($_POST['stock']) && is_numeric($_POST['stock']) ? (int)$_POST['stock'] : 0;
//     $remaining_stock = isset($_POST['remaining_stock']) && is_numeric($_POST['remaining_stock']) ? (int)$_POST['remaining_stock'] : 0;
//     $trade_price = isset($_POST['Trade_Price']) && is_numeric($_POST['Trade_Price']) ? (int)$_POST['Trade_Price'] : 0;
//     $pcs_in_pack = isset($_POST['PCS_IN_PACK']) && is_numeric($_POST['PCS_IN_PACK']) ? (int)$_POST['PCS_IN_PACK'] : 0;
//     $price = isset($_POST['price']) && is_numeric($_POST['price']) ? (float)$_POST['price'] : 0;

//     // --- 2) Sanitize string inputs ---
//     $name = strtoupper(trim($_POST['name']));           // convert to uppercase for consistent comparison
//     $generic = trim($_POST['generic']);
//     $dosage_form = strtoupper(trim($_POST['dosage_form'])); // convert to uppercase
//     $category = trim($_POST['category']);
//     $rack = trim($_POST['rack']);
//     $batch_no = trim($_POST['Batch_No']);
//     $vendor = trim($_POST['Vendor']);
//     $company_name = trim($_POST['Company_Name']);

//     // --- 3) Format date inputs ---
//     $purchase_date = !empty($_POST['Purchase_Date']) ? date('Y-m-d', strtotime($_POST['Purchase_Date'])) : null;
//     $mfg = !empty($_POST['MFG']) ? date('Y-m-d', strtotime($_POST['MFG'])) : null;
//     $exp = !empty($_POST['EXP']) ? date('Y-m-d', strtotime($_POST['EXP'])) : null;

//     // --- 4) Check if medicine exists (case-insensitive + trimmed) ---
//     $check = $conn->prepare("
//         SELECT id, stock, remaining_stock 
//         FROM medicines 
//         WHERE UPPER(TRIM(name)) = ? AND UPPER(TRIM(dosage_form)) = ? 
//         LIMIT 1
//     ");
//     if (!$check) die("Prepare failed: " . $conn->error);

//     $check->bind_param("ss", $name, $dosage_form);
//     $check->execute();
//     $result = $check->get_result();

//     if ($result && $result->num_rows > 0) {
//         // --- Medicine exists → update stock ---
//         $row = $result->fetch_assoc();
//         $existing_id = (int)$row['id'];
//         $new_stock = $row['stock'] + $stock;
//         $new_remaining = $row['remaining_stock'] + $remaining_stock;

//         $update = $conn->prepare("
//             UPDATE medicines SET
//                 stock = ?, 
//                 remaining_stock = ?, 
//                 category = ?, 
//                 Trade_Price = ?, 
//                 price = ?, 
//                 PCS_IN_PACK = ?, 
//                 rack = ?, 
//                 MFG = ?, 
//                 EXP = ?, 
//                 Batch_No = ?, 
//                 Vendor = ?, 
//                 Company_Name = ?, 
//                 Purchase_Date = ?
//             WHERE id = ?
//         ");
//         if (!$update) die("Prepare failed: " . $conn->error);

//         $update->bind_param(
//             "iisisssssssssi",
//             $new_stock,        // stock int
//             $new_remaining,    // remaining_stock int
//             $category,         // varchar
//             $trade_price,      // int
//             $price,            // decimal/double
//             $pcs_in_pack,      // int
//             $rack,             // varchar
//             $mfg,              // date string
//             $exp,              // date string
//             $batch_no,         // varchar
//             $vendor,           // varchar
//             $company_name,     // varchar
//             $purchase_date,    // date string
//             $existing_id       // id int
//         );

//         if (!$update->execute()) die("Update failed: " . $update->error);

//         header("Location: medicines.php?msg=stock_added");
//         exit;

//     } else {
//         // --- Insert new medicine ---
//         $stmt = $conn->prepare("
//             INSERT INTO medicines(
//                 name, generic, dosage_form, category, Trade_Price, price, PCS_IN_PACK, rack, stock, remaining_stock,
//                 MFG, EXP, Batch_No, Vendor, Company_Name, Purchase_Date
//             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
//         ");
//         if (!$stmt) die("Prepare failed: " . $conn->error);

//         $stmt->bind_param(
//             "ssssiiississssss",
//             $name,            // varchar
//             $generic,         // varchar
//             $dosage_form,     // varchar
//             $category,        // varchar
//             $trade_price,     // int
//             $price,           // decimal/double
//             $pcs_in_pack,     // int
//             $rack,            // varchar
//             $stock,           // int
//             $remaining_stock, // int
//             $mfg,             // date string
//             $exp,             // date string
//             $batch_no,        // varchar
//             $vendor,          // varchar
//             $company_name,    // varchar
//             $purchase_date    // date string
//         );

//         if (!$stmt->execute()) die("Insert failed: " . $stmt->error);

//         header("Location: medicines.php?msg=added");
//         exit;
//     }
// }



// */

/* ----------------------------------------------
   UPDATE MEDICINE
---------------------------------------------- */
if (isset($_POST['update'])) {

    $id = (int) $_POST['id'];

    $stmt = $conn->prepare("
        UPDATE medicines 
        SET
            name = ?, 
            generic = ?, 
            dosage_form = ?, 
            category = ?, 
            Trade_Price = ?,
            price = ?, 
            PCS_IN_PACK = ?,
            rack = ?, 
            stock = ?,
            remaining_stock = ?, 
            MFG = ?, 
            EXP = ?, 
            Batch_No = ?, 
            Vendor = ?, 
            Company_Name = ?, 
            Purchase_Date = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssssssssssssssss",
        $_POST['name'],
        $_POST['generic'],
        $_POST['dosage_form'],
        $_POST['category'],
        $_POST['Trade_Price'],
        $_POST['price'],  
        $_POST['PCS_IN_PACK'],
        $_POST['rack'],
        $_POST['stock'],
        $_POST['remaining_stock'],
        $_POST['MFG'],
        $_POST['EXP'],
        $_POST['Batch_No'],
        $_POST['Vendor'],
        $_POST['Company_Name'],
        $_POST['Purchase_Date'],
        $id
    );

    $stmt->execute();
    header("Location: medicines.php?msg=updated");
    exit;
}

/* ----------------------------------------------
   DELETE MEDICINE
---------------------------------------------- */
/*
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM medicines WHERE id = $id");

    header("Location: medicines.php?msg=deleted");
    exit;
}
*/
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete']; // get the ID safely

    $stmt = $conn->prepare("UPDATE medicines SET is_deleted = 1 WHERE id = ?");
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("i", $id); // use the correct variable
    if (!$stmt->execute()) die("Execute failed: " . $stmt->error);

    header("Location: medicines.php?msg=deleted");
    exit;
}



/* ----------------------------------------------
   SWEETALERT MESSAGE HANDLING
---------------------------------------------- */
$msg       = "";
$msg_title = "";
$msg_icon  = "info";

if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case "added":
            $msg = "Medicine added successfully!";
            $msg_title = "✔ Added!";
            $msg_icon = "success";
            break;

        case "updated":
            $msg = "Medicine updated!";
            $msg_title = "Updated";
            $msg_icon = "info";
            break;

        case "deleted":
            $msg = "Medicine deleted!";
            $msg_title = "Deleted!";
            $msg_icon = "error";
            break;
    }
}

/* ----------------------------------------------
   SEARCH LOGIC
---------------------------------------------- */


$search     = "";
$search_exp = "";
$where      = [];

if (isset($_POST['search'])) {

    $search     = trim($_POST['search_text']);
    $search_exp = trim($_POST['search_exp']);

    if ($search !== "") {
        $escaped = $conn->real_escape_string($search);
        $where[] = "(name LIKE '%{$escaped}%' OR generic LIKE '%{$escaped}%')";
    }

    if ($search_exp !== "") {
        $year    = intval($search_exp);
        $where[] = "YEAR(EXP) = {$year}";
    }
}

// $where[] = "is_deleted = 0";

$sql  = "SELECT * FROM medicines";
$sql .= !empty($where) ? " WHERE " . implode(" AND ", $where) : "";
$sql .= " ORDER BY id DESC";

$rows = $conn->query($sql);

?>

<div class="container mt-4">

    <!-- ldsjflisdflsdijflsdifjsldjfldisj -->

    <!-- Quarter-based EXP Tables -->
    <?php
        $currentYear = date("Y");

        $quarters = [
            'Q1 (Jan–Mar)' => ["{$currentYear}-01-01", "{$currentYear}-03-31"],
            'Q2 (Apr–Jun)' => ["{$currentYear}-04-01", "{$currentYear}-06-30"],
            'Q3 (Jul–Sep)' => ["{$currentYear}-07-01", "{$currentYear}-09-30"],
            'Q4 (Oct–Dec)' => ["{$currentYear}-10-01", "{$currentYear}-12-31"],
        ];

        foreach ($quarters as $quarterName => $dates):

            list($startDate, $endDate) = $dates;

            echo "<div class='card mb-4 border-danger'>
                   <h5 class='mb-0 text-center'> Expiring Medicines</h5>
                    <div class='card-header bg-danger text-white'>
                        <h5 class='mb-1 mt-1 text-center'>{$quarterName} ({$startDate} to {$endDate})</h5>
                    </div>
                    <div class='card-body'>";

            $stmt = $conn->prepare("
                SELECT * FROM medicines
                WHERE  EXP BETWEEN ? AND ?
                ORDER BY EXP ASC
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                echo "<div class='table-responsive'>
                        <table class='table table-bordered table-striped'>
                            <thead class='table-dark'>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Generic</th>
                                    <th>Dosage</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Remaining</th>
                                    <th>EXP</th>
                                </tr>
                            </thead>
                            <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['generic']}</td>
                            <td>{$row['dosage_form']}</td>
                            <td>{$row['category']}</td>
                            <td>{$row['stock']}</td>
                            <td>{$row['remaining_stock']}</td>
                            <td>{$row['EXP']}</td>
                        </tr>";
                }

                echo "</tbody></table></div>";

            } else {
                echo "<p>No medicines expiring in this quarter.</p>";
            }

            echo "</div></div>";

        endforeach;
    ?>

    <!-- Medicines Table -->

<div class="card border-primary mb-4 shadow-sm">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0 text-center">Medicines List</h5>
  </div>
  <div class="card-body p-0">

    <!--  RESPONSIVE TABLE WRAPPER -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">

 <table class="table table-bordered table-striped table-hover align-middle">
    <thead class="table-dark sticky-top text-center">
      <tr>
        <th class="col-id">ID</th>
<th class="col-name">Brand Name</th>
<th class="col-generic">Generic</th>
<th class="col-dosage">Dosage Form</th>
<th class="col-category">Category</th>
<th class="col-rack">Rack</th>
<th class="col-trade">Trade_Price</th>
<th class="col-price">Price</th>
<th class="col-pcs">PCS_IN_PACK</th>
<th class="col-stock">Stock</th>
<th class="col-rem">Remaining</th>
<th class="col-mfg">MFG</th>
<th class="col-exp">EXP</th>
<th class="col-batch">Batch No</th>
<th class="col-vendor">Vendor</th>
<th class="col-company">Company</th>
<th class="col-purchase">Purchase Date</th>
<th class="col-actions">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($r = $rows->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($r['id'])?></td>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=htmlspecialchars($r['generic'])?></td>
        <td><?=htmlspecialchars($r['dosage_form'])?></td>
        <td><?=htmlspecialchars($r['category'])?></td>
        <td><?=htmlspecialchars($r['rack'])?></td>
        <td><?=htmlspecialchars($r['Trade_Price'])?></td>
        <td><?=htmlspecialchars($r['price'])?></td>
        <td><?=htmlspecialchars($r['PCS_IN_PACK'])?></td>
        <td><?=htmlspecialchars($r['stock'])?></td>
        <td><?=htmlspecialchars($r['remaining_stock'])?></td>
        <td><?=htmlspecialchars($r['MFG'])?></td>
        <td><?=htmlspecialchars($r['EXP'])?></td>
        <td><?=htmlspecialchars($r['Batch_No'])?></td>
        <td><?=htmlspecialchars($r['Vendor'])?></td>
        <td><?=htmlspecialchars($r['Company_Name'])?></td>
        <td><?=htmlspecialchars($r['Purchase_Date'])?></td>
        <td>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=htmlspecialchars($r['id'])?>">Edit</button>
          <a class="btn btn-sm btn-danger" onclick="return confirm('Delete?')" href="?delete=<?=htmlspecialchars($r['id'])?>">Delete</a>
        </td>
      </tr>
     
      <!-- Edit Modal -->
      <div class="modal fade" id="edit<?=htmlspecialchars($r['id'])?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post">
              <div class="modal-header">
                <h5 class="modal-title">Edit Medicine</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id" value="<?=htmlspecialchars($r['id'])?>">
                <div class="mb-2"><label>Name</label><input class="form-control" name="name" value="<?=htmlspecialchars($r['name'])?>" required></div>
                <div class="mb-2"><label>Generic</label><input class="form-control" name="generic" value="<?=htmlspecialchars($r['generic'])?>" required></div>
                <div class="mb-2"><label>Dosage Form</label><input class="form-control" name="dosage_form" value="<?=htmlspecialchars($r['dosage_form'])?>" required></div>
                <div class="mb-2"><label>Category</label><input class="form-control" name="category" value="<?=htmlspecialchars($r['category'])?>" required></div>
                <div class="mb-2"><label>Trade_Price</label><input type="number" step="0.01" class="form-control" name="Trade_Price" value="<?=htmlspecialchars($r['Trade_Price'])?>" required></div>
                <div class="mb-2"><label>Price</label><input type="number" step="0.01" class="form-control" name="price" value="<?=htmlspecialchars($r['price'])?>" required></div>
                <div class="mb-2"><label>PCS_IN_PACK</label><input type="number" step="0.01" class="form-control" name="PCS_IN_PACK" value="<?=htmlspecialchars($r['PCS_IN_PACK'])?>" required></div>
                <div class="mb-2"><label>Rack</label><input class="form-control" name="rack" value="<?=htmlspecialchars($r['rack'])?>" required></div>
                <div class="mb-2"><label>Stock</label><input type="number" class="form-control" name="stock" value="<?=htmlspecialchars($r['stock'])?>" required></div>
                <div class="mb-2"><label>Remaining Stock</label><input type="number" class="form-control" name="remaining_stock" value="<?=htmlspecialchars($r['remaining_stock'])?>" required></div>
                <div class="mb-2"><label>MFG</label><input type="date" class="form-control" name="MFG" value="<?=htmlspecialchars($r['MFG'])?>" required></div>
                <div class="mb-2"><label>EXP</label><input type="date" class="form-control" name="EXP" value="<?=htmlspecialchars($r['EXP'])?>" required></div>
                <div class="mb-2"><label>Batch No</label><input class="form-control" name="Batch_No" value="<?=htmlspecialchars($r['Batch_No'])?>" required></div>
                <div class="mb-2"><label>Vendor</label><input class="form-control" name="Vendor" value="<?=htmlspecialchars($r['Vendor'])?>" required></div>
                <div class="mb-2"><label>Company</label><input class="form-control" name="Company_Name" value="<?=htmlspecialchars($r['Company_Name'])?>" required></div>
                <div class="mb-2"><label>Purchase Date</label><input type="date" class="form-control" name="Purchase_Date" value="<?=htmlspecialchars($r['Purchase_Date'])?>" required></div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" name="update">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <?php endwhile; ?>
    </tbody>
  </table>
</div>

  </div>
</div>

<?php include "../includes/footer.php"; ?>
