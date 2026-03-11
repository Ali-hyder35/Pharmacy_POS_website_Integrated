<?php
require_once "includes/auth.php"; if(($_SESSION['role']??'')!=='admin'){ header("Location: index.php"); exit; }
require_once "config/db.php"; include "includes/header.php"; include "includes/navbar.php";
$msg='';
if(isset($_POST['add'])){
  $stmt=$conn->prepare("INSERT INTO users(name,username,password,role) VALUES(?,?,MD5(?),?)");
  $stmt->bind_param("ssss", $_POST['name'], $_POST['username'], $_POST['password'], $_POST['role']);
  if($stmt->execute()) $msg="User added."; else $msg="Username exists.";
}
if(isset($_GET['delete'])){ $id=intval($_GET['delete']); if($id!=($_SESSION['user_id'])) $conn->query("DELETE FROM users WHERE id=$id"); }
$rows=$conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<div class="container mt-4">
  <h3>Admin - Users</h3>
  <?php if($msg) echo "<div class='alert alert-info'>$msg</div>";?>
  <form method="post" class="row g-2 mb-3">
    <div class="col-md-3"><input class="form-control" name="name" placeholder="Full name" required></div>
    <div class="col-md-3"><input class="form-control" name="username" placeholder="Username" required></div>
    <div class="col-md-3"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
    <div class="col-md-2">
      <select name="role" class="form-select"><option value="cashier">Cashier</option><option value="admin">Admin</option></select>
    </div>
    <div class="col-md-1"><button class="btn btn-success w-100" name="add">Add</button></div>
  </form>
  <table class="table table-bordered">
    <thead class="table-dark"><tr><th>ID</th><th>Name</th><th>Username</th><th>Role</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($u=$rows->fetch_assoc()): ?>
      <tr>
        <td><?=$u['id']?></td><td><?=$u['name']?></td><td><?=$u['username']?></td><td><?=$u['role']?></td>
        <td><?php if($u['id']!=$_SESSION['user_id']): ?><a class="btn btn-sm btn-danger" onclick="return confirm('Delete?')" href="?delete=<?=$u['id']?>">Delete</a><?php endif; ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include "includes/footer.php"; ?>
