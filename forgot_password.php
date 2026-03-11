<?php
require_once "config/db.php"; session_start();
$msg='';
if(isset($_POST['reset'])){
  $u=$_POST['username']; $new=md5($_POST['new_password']);
  $stmt=$conn->prepare("UPDATE users SET password=? WHERE username=?");
  $stmt->bind_param("ss",$new,$u);
  if($stmt->execute() && $stmt->affected_rows>0){ $msg="Password updated. Login now."; } else { $msg="User not found."; }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Forgot Password</title></head><body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
  <div class="card shadow" style="max-width:480px; width:100%;">
    <div class="card-body">
      <h3 class="mb-3 text-center">Reset Password</h3>
      <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="new_password" class="form-control" required></div>
        <button class="btn btn-primary" name="reset">Update Password</button>
        <a class="btn btn-secondary" href="login.php">Back</a>
      </form>
      <p class="mt-2 small text-muted">Demo reset (no email). In production, implement secure email/token reset.</p>
    </div>
  </div>
</div>
</body></html>
