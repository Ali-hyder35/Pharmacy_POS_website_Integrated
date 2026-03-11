<?php

session_start(); 
require_once "config/db.php";

$message = '';

if (isset($_POST['login'])) {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT id, name, password, status, is_password_reset 
        FROM users 
        WHERE email = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if ($user['status'] !== 'ACTIVE') {
            $message = "⚠️ Account not active";
        }
        elseif ($user['is_password_reset'] != 1) {
            $message = "⚠️ Please reset your password first";
        }
        elseif (!password_verify($password, $user['password'])) {
            $message = "❌ Invalid email or password";
        }
        else {
            // ✅ LOGIN SUCCESS
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: index.php");
            exit;
        }

    } else {
        $message = "❌ Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Pharmacy System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #198754, #0d6efd);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-lg">
                <div class="card-header text-center bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </h4>
                </div>

                <div class="card-body p-4">

                    <?php if ($message): ?>
                        <div class="alert alert-danger text-center">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button name="login" class="btn btn-primary">
                                <i class="bi bi-lock-fill"></i> Login
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-footer text-center">
                    <small>
                        Don’t have an account?
                        <a href="auth/create_account.php" class="text-decoration-none">Create Account</a>
                    </small>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
