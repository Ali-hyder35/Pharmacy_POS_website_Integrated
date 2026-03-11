<?php
session_start();
require_once "../config/db.php";
$msg='';
if(isset($_POST['register'])){
  $u=trim($_POST['username']??''); $p=md5($_POST['password']??''); $r='user';
  if(!$u || !$p){ $msg="All fields required"; }
  else{
    $stmt=$conn->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
    $stmt->bind_param("sss",$u,$p,$r);
    if($stmt->execute()){ header("Location: /auth/login.php"); exit; } else { $msg="Username may already exist."; }
  }
}
$page_title="Sign Up - Pharmacy POS";
include "../includes/header.php";
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow">
      <div class="card-body">
        <h3 class="text-center mb-3">Create Account</h3>
        <?php if($msg): ?><div class="alert alert-danger py-2 text-center"><?php echo $msg; ?></div><?php endif; ?>
        <form method="post">
          <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
          <div class="d-grid"><button name="register" class="btn btn-success">Register</button></div>
        </form>
        <div class="mt-3 text-center"><a href="/auth/login.php">Already have an account? Login</a></div>
      </div>
    </div>
  </div>
</div>
<?php include "../includes/footer.php"; ?>
