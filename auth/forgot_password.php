<?php
session_start();
$page_title="Forgot Password";
include "../includes/header.php";
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="mb-3 text-center">Forgot Password</h4>
        <p class="text-muted small">Demo flow: contact admin to reset your password.</p>
        <a href="/auth/login.php" class="btn btn-outline-secondary w-100">Back to Login</a>
      </div>
    </div>
  </div>
</div>
<?php include "../includes/footer.php"; ?>
