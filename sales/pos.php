<?php
require_once "../includes/auth.php"; 
require_once "../config/db.php";

$page_title="POS";
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['checkout'])){
  $customer_id = intval($_POST['customer_id']??0);
  $items = $_POST['item']??[]; // item[med_id] = qty
  $total = 0;
  foreach($items as $mid=>$qty){
    $mid=intval($mid); $qty=intval($qty);
    if($qty<=0) continue;
    $r=$conn->query("SELECT price,stock FROM medicines WHERE id=$mid")->fetch_assoc();
    if(!$r) continue;
    $qty = min($qty, intval($r['stock']));
    $total += $r['price'] * $qty;
  }
  if($total>0){
    $stmt=$conn->prepare("INSERT INTO sales(customer_id,user_id,total) VALUES(?,?,?)");
    $uid=$_SESSION['user_id']; $stmt->bind_param("iid",$customer_id,$uid,$total); $stmt->execute();
    $sale_id=$stmt->insert_id;
    foreach($items as $mid=>$qty){
      $mid=intval($mid); $qty=intval($qty); if($qty<=0) continue;
      $r=$conn->query("SELECT price,stock FROM medicines WHERE id=$mid")->fetch_assoc(); if(!$r) continue;
      $qty=min($qty,intval($r['stock'])); if($qty<=0) continue;
      $price=$r['price'];
      $conn->query("INSERT INTO sale_items(sale_id,medicine_id,qty,price) VALUES($sale_id,$mid,$qty,$price)");
      $conn->query("UPDATE medicines SET stock=stock-$qty WHERE id=$mid");
    }
    header("Location: /sales/invoice.php?id=".$sale_id); exit;
  }
}
include "../includes/header.php";
?>
<h3>Point of Sale</h3>
<form method="post">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm"><div class="card-body">
        <h6 class="text-muted mb-2">Customer</h6>
        <select class="form-select" name="customer_id">
          <option value="0">Walk-in</option>
          <?php $q=$conn->query("SELECT id,name FROM customers ORDER BY name");
          while($r=$q->fetch_assoc()){ echo "<option value='{$r['id']}'>".htmlspecialchars($r['name'])."</option>"; } ?>
        </select>
      </div></div>
    </div>
    <div class="col-md-8">
      <div class="card shadow-sm"><div class="card-body">
        <h6 class="text-muted mb-2">Items</h6>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead><tr><th>Medicine</th><th>Price</th><th>Stock</th><th style="width:120px">Qty</th></tr></thead>
            <tbody>
            <?php $q=$conn->query("SELECT id,name,price,stock FROM medicines ORDER BY name");
            while($r=$q->fetch_assoc()){
              echo "<tr>
                <td>".htmlspecialchars($r['name'])."</td>
                <td>PKR ".number_format($r['price'],2)."</td>
                <td>{$r['stock']}</td>
                <td><input type='number' min='0' max='{$r['stock']}' class='form-control form-control-sm' name='item[{$r['id']}]' value='0'></td>
              </tr>";
            } ?>
            </tbody>
          </table>
        </div>
      </div></div>
    </div>
  </div>
  <div class="text-end mt-3"><button name="checkout" class="btn btn-success btn-lg"><i class="bi bi-receipt"></i> Checkout</button></div>
</form>
<?php include "../includes/footer.php"; ?>
