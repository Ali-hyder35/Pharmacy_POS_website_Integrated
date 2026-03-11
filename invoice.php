<?php
require_once "includes/auth.php"; 
require_once "config/db.php";
if(!isset($_GET['sale_id'])) die("Invalid invoice");
$id=intval($_GET['sale_id']);
$sale=$conn->query("SELECT s.*, c.name AS customer FROM sales s 
                   LEFT JOIN customers c ON s.customer_id=c.id 
                   WHERE s.id=$id")->fetch_assoc();

$items=$conn->query("SELECT si.*, m.name FROM sales_items si 
                    JOIN medicines m ON si.medicine_id=m.id 
                    WHERE si.sale_id=$id");
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #<?=$id?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print {.no-print{display:none}}</style>
  </head>
  <body onload="window.print()">
    <div class="container mt-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3>Shahnaz Shabbir Noshahi Pharmacy</h3>
          <small>Near Astana Alia Pir Shabbir Hussain Shah, Main Road,
            Ranmal Sharif, Phalia, Mandi Bahaudhin • +92-3005126574 || +92-3006053214</small>
    </div>
    <div class="text-end"><h4>Invoice #<?=$id?></h4>
        <!-- <div>Date: <?=$sale['Date']?></div> -->
        <div>Customer: <?=$sale['customer']?:'Walk-in'?></div>
    </div>
  </div>
  <hr>
  <table class="table table-bordered">
    <thead class="table-light"><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
    <tbody>
      <?php $grand=0; while($r=$items->fetch_assoc()): $t=$r['quantity']*$r['price']; $grand+=$t; ?>
      <tr><td><?=$r['name']?></td><td><?=$r['quantity']?></td><td><?=$r['price']?></td><td><?=$t?></td></tr>
      <?php endwhile; ?>
    </tbody>
    <tfoot><tr><th colspan="3" class="text-end">Grand Total</th><th><?=number_format($grand,2)?></th></tr></tfoot>
  </table>
  <div class="text-center no-print"><a class="btn btn-secondary" href="sales.php">Back to Sales</a></div>
</div>
</body></html>
