<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$id = intval($_GET['id'] ?? 0);

// Fetch sale info with customer name
$sale = $conn->query("
    SELECT s.*, c.name AS customer 
    FROM sales s 
    LEFT JOIN customers c ON c.id = s.customer_id 
    WHERE s.id = $id
")->fetch_assoc();

if (!$sale) {
    die("Invoice not found");
}

// Fetch sale items (medicines + cosmetics)
$items = $conn->query("
    SELECT si.*, 
           COALESCE(m.name, c.name) AS product_name,
           COALESCE(si.price, m.price, c.price, 0) AS price,
           COALESCE(si.Trade_Price, m.Trade_Price, c.Trade_Price, 0) AS trade_price
    FROM sales_items si
    LEFT JOIN medicines m ON si.medicine_id = m.id
    LEFT JOIN cosmetics c ON si.cosmetic_id = c.id
    WHERE si.sale_id = $id
    ORDER BY si.id ASC
");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invoice #<?php echo $id; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
@media print { .no-print { display: none; } }
.invoice { max-width: 800px; margin: 20px auto; }
.invoice-header { margin-bottom: 20px; }
</style>
</head>
<body>
<div class="invoice border rounded p-4">

  <div class="d-flex justify-content-between align-items-center invoice-header">
    <div>
      <h4 class="mb-0">Shahnaz Shabbir Noshahi Pharmacy</h4>
      <small>Main Bazar, Lahore • +92 300 1234567</small>
    </div>
    <div class="text-end">
      <h5 class="mb-0">Invoice #<?php echo $id; ?></h5>
      <small>Date: <?php echo $sale['created_at']; ?></small>
    </div>
  </div>

  <hr>

  <div class="mb-3">
    <strong>Customer:</strong> <?php echo htmlspecialchars($sale['customer'] ?? 'Walk-in'); ?>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Trade Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1; 
        $grand_total = 0; 
        while ($r = $items->fetch_assoc()): 
            $subtotal = $r['qty'] * $r['price']; 
            $grand_total += $subtotal;
        ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo htmlspecialchars($r['product_name']); ?></td>
          <td><?php echo $r['qty']; ?></td>
          <td>PKR <?php echo number_format($r['price'], 2); ?></td>
          <td>PKR <?php echo number_format($r['trade_price'], 2); ?></td>
          <td>PKR <?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="5" class="text-end">Grand Total</th>
          <th>PKR <?php echo number_format($grand_total, 2); ?></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="text-end no-print mt-3">
    <button onclick="window.print()" class="btn btn-primary">Print</button>
    <a class="btn btn-secondary" href="/admin/dashboard.php">Back</a>
  </div>

</div>
</body>
</html>