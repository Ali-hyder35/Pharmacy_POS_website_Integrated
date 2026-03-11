<?php
require_once "includes/auth.php";
require_once "config/db.php";
include "includes/header.php";
include "includes/navbar.php";

if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

/* ================= LOW STOCK ALERT ================= */
$low_stock = $conn->query("
    SELECT name, stock FROM medicines 
    WHERE stock <= 10
    ORDER BY stock ASC
");

/* ================= ADD PRODUCT ================= */
if(isset($_POST['add_to_cart'])){

    $type = $_POST['type'];
    $id = intval($_POST['product_id']);
    $qty = intval($_POST['qty']);

    if($type == "medicine"){
        $p = $conn->query("SELECT * FROM medicines WHERE id=$id")->fetch_assoc();
    }else{
        $p = $conn->query("SELECT * FROM cosmetics WHERE id=$id")->fetch_assoc();
    }

    if($p && $qty>0 && $qty <= $p['stock']){
        $_SESSION['cart'][] = [
            'type' => $type,
            'id' => $p['id'],
            'name' => $p['name'],
            'price' => $p['price'],
            'Trade_Price' => isset($p['Trade_Price']) ? $p['Trade_Price'] : 0, // safe default
            'qty' => $qty,
            'total' => $p['price'] * $qty
        ];
    }
}

/* ================= REMOVE ================= */
if(isset($_GET['remove'])){
    $i = intval($_GET['remove']);
    if(isset($_SESSION['cart'][$i])){
        array_splice($_SESSION['cart'],$i,1);
    }
}

/* ================= TOTAL ================= */
$grand = 0;
foreach($_SESSION['cart'] as $it){
    $grand += $it['total'];
}

/* ================= CHECKOUT ================= */
if(isset($_POST['checkout']) && !empty($_SESSION['cart'])){

    $customer_type = $_POST['customer_type'];
    $customer_id = ($customer_type=="walkin" || empty($_POST['customer_id'])) ? "NULL" : intval($_POST['customer_id']);
    $payment_method = $_POST['payment_method'];

    $uid = $_SESSION['user_id'];

    $conn->query("
        INSERT INTO sales(customer_id, user_id, total, payment_method)
        VALUES($customer_id, $uid, $grand, '$payment_method')
    ");

    $sale_id = $conn->insert_id;

    foreach($_SESSION['cart'] as $it){
        $tradePrice = isset($it['Trade_Price']) ? $it['Trade_Price'] : 0;

        if($it['type']=="medicine"){
            $conn->query("
                INSERT INTO sales_items(sale_id, medicine_id, cosmetic_id, quantity, price, Trade_Price)
                VALUES($sale_id, {$it['id']}, NULL, {$it['qty']}, {$it['price']}, $tradePrice)
            ");

            $conn->query("
                UPDATE medicines
                SET stock = stock - {$it['qty']}
                WHERE id={$it['id']}
            ");
        }else{
            $conn->query("
                INSERT INTO sales_items(sale_id, medicine_id, cosmetic_id, quantity, price, Trade_Price)
                VALUES($sale_id, NULL, {$it['id']}, {$it['qty']}, {$it['price']}, $tradePrice)
            ");

            $conn->query("
                UPDATE cosmetics
                SET stock = stock - {$it['qty']}
                WHERE id={$it['id']}
            ");
        }
    }

    $_SESSION['cart'] = [];
    header("Location: invoice.php?sale_id=".$sale_id);
    exit;
}
?>

<div class="container mt-4">

<h3 class="text-center">Pharmacy POS</h3>

<!-- LOW STOCK ALERT -->
<?php if($low_stock->num_rows > 0): ?>
<div class="alert alert-danger">
<strong>Low Stock Warning</strong><br>
<?php while($l=$low_stock->fetch_assoc()): ?>
<?=$l['name']?> (Stock: <?=$l['stock']?>)<br>
<?php endwhile; ?>
</div>
<?php endif; ?>

<!-- ADD PRODUCT FORM -->
<form method="post">
<div class="row mb-3">
    <div class="col-md-3">
        <label>Product Type</label>
        <select name="type" id="type" class="form-control" onchange="loadProducts()">
            <option value="medicine">Medicine</option>
            <option value="cosmetic">Cosmetic</option>
        </select>
    </div>

    <div class="col-md-4">
        <label>Select Product</label>
        <select name="product_id" id="product_list" class="form-control">
            <option value="">Select Product</option>
        </select>
    </div>

    <div class="col-md-2">
        <label>Qty</label>
        <input type="number" name="qty" class="form-control" value="1">
    </div>

    <div class="col-md-2">
        <label>&nbsp;</label>
        <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add</button>
    </div>
</div>
</form>

<!-- PRESCRIPTION UPLOAD -->
<form method="post" enctype="multipart/form-data" class="mb-4">
<div class="row">
    <div class="col-md-6">
        <label>Upload Prescription</label>
        <input type="file" name="prescription" class="form-control">
    </div>
</div>
</form>

<!-- CART -->
<h5>Cart</h5>
<table class="table table-bordered">
<thead>
<tr>
<th>#</th>
<th>Product</th>
<th>Type</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
<th></th>
</tr>
</thead>
<tbody>
<?php $i=0; foreach($_SESSION['cart'] as $it): ?>
<tr>
<td><?=++$i?></td>
<td><?=$it['name']?></td>
<td><?=$it['type']?></td>
<td><?=$it['price']?></td>
<td><?=$it['qty']?></td>
<td><?=$it['total']?></td>
<td>
<a class="btn btn-danger btn-sm" href="?remove=<?=$i-1?>">Remove</a>
</td>
</tr>
<?php endforeach; ?>
<tr>
<th colspan="5" class="text-end">Grand Total</th>
<th colspan="2">PKR <?=number_format($grand,2)?></th>
</tr>
</tbody>
</table>

<!-- CUSTOMER + PAYMENT -->
<form method="post">
<div class="row mb-3">
    <div class="col-md-3">
        <select name="customer_type" class="form-select">
            <option value="walkin">Walk-in</option>
            <option value="prescribed">Prescribed</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="customer_id" class="form-select">
            <option value="">Select Customer</option>
            <?php
            $customers = $conn->query("SELECT * FROM customers");
            while($c = $customers->fetch_assoc()):
            ?>
            <option value="<?=$c['id']?>"><?=$c['name']?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-3">
        <select name="payment_method" class="form-select">
            <option value="cash">Cash</option>
            <option value="card">Card</option>
        </select>
    </div>
</div>

<button class="btn btn-success" name="checkout" <?= empty($_SESSION['cart'])?'disabled':'' ?>>
Checkout & Print Invoice
</button>
</form>

</div>

<script>
function loadProducts(){
    let type = document.getElementById("type").value;
    fetch("get_products.php?type="+type)
    .then(res => res.json())
    .then(data => {
        let list = document.getElementById("product_list");
        list.innerHTML = "";
        data.forEach(p => {
            let opt = document.createElement("option");
            opt.value = p.id;
            opt.text = p.name + " (Stock:" + p.stock + ")";
            list.appendChild(opt);
        });
    });
}
loadProducts();
</script>

<?php include "includes/footer.php"; ?>