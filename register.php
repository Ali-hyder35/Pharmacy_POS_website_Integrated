<?php
require_once "config/db.php"; session_start();
$msg='';
if(isset($_POST['register'])){
  $name=$_POST['name']; $u=$_POST['username']; $p=md5($_POST['password']);
  $role='cashier';
  $stmt=$conn->prepare("INSERT INTO users(name,username,password,role) VALUES(?,?,?,?)");
  $stmt->bind_param("ssss",$name,$u,$p,$role);
  if($stmt->execute()){ $msg="Account created. You can login now."; } else { $msg="Username already exists."; }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Sign up</title></head><body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
  <div class="card shadow" style="max-width:480px; width:100%;">
    <div class="card-body">
      <h3 class="mb-3 text-center">Sign up</h3>
      <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Full name</label><input name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <button class="btn btn-success" name="register">Create account</button>
        <a class="btn btn-secondary" href="login.php">Back to login</a>
      </form>
    </div>
  </div>
</div>
</body></html>
