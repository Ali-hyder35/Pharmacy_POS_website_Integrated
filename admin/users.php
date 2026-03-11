<?php
require_once "../includes/auth.php"; require_once "../includes/admin_only.php"; require_once "../config/db.php";
$page_title="Users";
if(isset($_POST['save'])){
  $id=intval($_POST['id']??0); $username=trim($_POST['username']); $role=$_POST['role'];
  if($id){
    $stmt=$conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
    $stmt->bind_param("ssi",$username,$role,$id); $stmt->execute();
  } else {
    $pass = md5('password123');
    $stmt=$conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
    $stmt->bind_param("sss",$username,$pass,$role); $stmt->execute();
  }
  header("Location: users.php"); exit;
}
if(isset($_GET['del'])){ $id=intval($_GET['del']); if($id!=1) $conn->query("DELETE FROM users WHERE id=$id"); header("Location: users.php"); exit; }
include "../includes/header.php";
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>User Management</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uModal"><i class="bi bi-person-plus"></i> Add User</button>
</div>
<table class="table table-striped">
  <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Actions</th></tr></thead>
  <tbody>
  <?php $q=$conn->query("SELECT * FROM users ORDER BY id DESC");
  while($r=$q->fetch_assoc()){
    $disabled = $r['id']==1 ? "disabled" : "";
    echo "<tr><td>{$r['id']}</td><td>".htmlspecialchars($r['username'])."</td><td>{$r['role']}</td>
      <td>
        <button class='btn btn-sm btn-outline-secondary' data-bs-toggle='modal' data-bs-target='#uModal' data-id='{$r['id']}' data-username='".htmlspecialchars($r['username'])."' data-role='{$r['role']}'>Edit</button>
        <a class='btn btn-sm btn-outline-danger $disabled' onclick='return confirm("Delete user?")' href='?del={$r['id']}'>Delete</a>
      </td></tr>";
  } ?>
  </tbody>
</table>

<div class="modal fade" id="uModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="id" id="u-id">
        <div class="mb-2"><label class="form-label">Username</label><input class="form-control" name="username" id="u-username" required></div>
        <div class="mb-2"><label class="form-label">Role</label>
          <select class="form-select" name="role" id="u-role">
            <option value="admin">admin</option>
            <option value="cashier">cashier</option>
            <option value="user">user</option>
          </select>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary" name="save">Save</button></div>
    </form>
  </div>
</div>
<script>
const uModal=document.getElementById('uModal');
uModal.addEventListener('show.bs.modal',e=>{
  const b=e.relatedTarget; if(!b) return;
  document.getElementById('u-id').value=b.getAttribute('data-id')||'';
  document.getElementById('u-username').value=b.getAttribute('data-username')||'';
  document.getElementById('u-role').value=b.getAttribute('data-role')||'user';
});
</script>
<?php include "../includes/footer.php"; ?>
