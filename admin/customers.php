<?php
require_once "../includes/auth.php"; require_once "../config/db.php";
$page_title="Customers";
if(isset($_POST['save'])){
  $id=intval($_POST['id']??0); $name=trim($_POST['name']); $phone=trim($_POST['phone']);
  if($id){
    $stmt=$conn->prepare("UPDATE customers SET name=?, phone=? WHERE id=?");
    $stmt->bind_param("ssi",$name,$phone,$id); $stmt->execute();
  } else {
    $stmt=$conn->prepare("INSERT INTO customers(name,phone) VALUES(?,?)");
    $stmt->bind_param("ss",$name,$phone); $stmt->execute();
  }
  header("Location: customers.php"); exit;
}
if(isset($_GET['del'])){ $id=intval($_GET['del']); $conn->query("DELETE FROM customers WHERE id=$id"); header("Location: customers.php"); exit; }
include "../includes/header.php";
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Customers</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cModal"><i class="bi bi-plus"></i> Add</button>
</div>
<div class="table-responsive">
<table class="table table-striped">
  <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Actions</th></tr></thead>
  <tbody>
  <?php $q=$conn->query("SELECT * FROM customers ORDER BY id DESC");
  while($r=$q->fetch_assoc()){
    echo "<tr><td>{$r['id']}</td><td>".htmlspecialchars($r['name'])."</td><td>".htmlspecialchars($r['phone'])."</td>
    <td>
      <button class='btn btn-sm btn-outline-secondary' data-bs-toggle='modal' data-bs-target='#cModal' data-id='{$r['id']}' data-name='".htmlspecialchars($r['name'])."' data-phone='".htmlspecialchars($r['phone'])."'>Edit</button>
      <a class='btn btn-sm btn-outline-danger' onclick='return confirm("Delete?")' href='?del={$r['id']}'>Delete</a>
    </td></tr>";
  } ?>
  </tbody>
</table>
</div>

<div class="modal fade" id="cModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Customer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="id" id="c-id">
        <div class="mb-2"><label class="form-label">Name</label><input class="form-control" name="name" id="c-name" required></div>
        <div class="mb-2"><label class="form-label">Phone</label><input class="form-control" name="phone" id="c-phone"></div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary" name="save">Save</button></div>
    </form>
  </div>
</div>
<script>
const cModal=document.getElementById('cModal');
cModal.addEventListener('show.bs.modal',e=>{
  const b=e.relatedTarget; if(!b) return;
  document.getElementById('c-id').value=b.getAttribute('data-id')||'';
  document.getElementById('c-name').value=b.getAttribute('data-name')||'';
  document.getElementById('c-phone').value=b.getAttribute('data-phone')||'';
});
</script>
<?php include "../includes/footer.php"; ?>
