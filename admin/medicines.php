<?php
require_once "../includes/auth.php"; require_once "../config/db.php";
$page_title="Medicines";
if(isset($_POST['save'])){
  $id=intval($_POST['id']??0); $name=trim($_POST['name']); $code=trim($_POST['code']); $price=floatval($_POST['price']); $stock=intval($_POST['stock']); $cat=trim($_POST['category']);
  if($id){
    $stmt=$conn->prepare("UPDATE medicines SET name=?, code=?, price=?, stock=?, category=? WHERE id=?");
    $stmt->bind_param("ssdisi",$name,$code,$price,$stock,$cat,$id); $stmt->execute();
  }else{
    $stmt=$conn->prepare("INSERT INTO medicines(name,code,price,stock,category) VALUES(?,?,?,?,?)");
    $stmt->bind_param("ssdis",$name,$code,$price,$stock,$cat); $stmt->execute();
  }
  header("Location: medicines.php"); exit;
}
if(isset($_GET['del'])){
  $id=intval($_GET['del']); $conn->query("DELETE FROM medicines WHERE id=$id"); header("Location: medicines.php"); exit;
}
include "../includes/header.php";
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Medicines</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medModal"><i class="bi bi-plus"></i> Add</button>
</div>
<div class="table-responsive">
<table class="table table-striped align-middle">
  <thead><tr><th>#</th><th>Name</th><th>Code</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
  <tbody>
  <?php
  $q=$conn->query("SELECT * FROM medicines ORDER BY id DESC");
  while($r=$q->fetch_assoc()){
    echo "<tr>
      <td>{$r['id']}</td>
      <td>".htmlspecialchars($r['name'])."</td>
      <td>".htmlspecialchars($r['code'])."</td>
      <td>".htmlspecialchars($r['category'])."</td>
      <td>PKR ".number_format($r['price'],2)."</td>
      <td>{$r['stock']}</td>
      <td>
        <button class='btn btn-sm btn-outline-secondary' data-bs-toggle='modal' data-bs-target='#medModal' 
          data-id='{$r['id']}' data-name='".htmlspecialchars($r['name'])."' data-code='".htmlspecialchars($r['code'])."' data-category='".htmlspecialchars($r['category'])."' data-price='{$r['price']}' data-stock='{$r['stock']}'>Edit</button>
        <a class='btn btn-sm btn-outline-danger' onclick='return confirm("Delete?")' href='?del={$r['id']}'>Delete</a>
      </td>
    </tr>";
  }
  ?>
  </tbody>
</table>
</div>

<!-- Modal -->
<div class="modal fade" id="medModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Medicine</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="id" id="m-id">
        <div class="mb-2"><label class="form-label">Name</label><input class="form-control" name="name" id="m-name" required></div>
        <div class="mb-2"><label class="form-label">Code</label><input class="form-control" name="code" id="m-code" required></div>
        <div class="mb-2"><label class="form-label">Category</label><input class="form-control" name="category" id="m-category"></div>
        <div class="mb-2"><label class="form-label">Price</label><input type="number" step="0.01" class="form-control" name="price" id="m-price" required></div>
        <div class="mb-2"><label class="form-label">Stock</label><input type="number" class="form-control" name="stock" id="m-stock" required></div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary" name="save">Save</button></div>
    </form>
  </div>
</div>
<script>
const medModal=document.getElementById('medModal');
medModal.addEventListener('show.bs.modal',e=>{
  const btn=e.relatedTarget; if(!btn) return;
  document.getElementById('m-id').value=btn.getAttribute('data-id')||'';
  document.getElementById('m-name').value=btn.getAttribute('data-name')||'';
  document.getElementById('m-code').value=btn.getAttribute('data-code')||'';
  document.getElementById('m-category').value=btn.getAttribute('data-category')||'';
  document.getElementById('m-price').value=btn.getAttribute('data-price')||'';
  document.getElementById('m-stock').value=btn.getAttribute('data-stock')||'';
});
</script>
<?php include "../includes/footer.php"; ?>
