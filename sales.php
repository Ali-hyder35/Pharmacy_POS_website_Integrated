<?php
require_once "includes/auth.php";
require_once "config/db.php";
include "includes/header.php";
include "includes/navbar.php"; 

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

<div class="container mt-4">

<h3>Sales History</h3>

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

<?php include "includes/footer.php"; ?>