<?php
require_once "../config/db.php";
if (isset($_POST['reset'])) {


 
    $email = $_GET['email'];
    $new   = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);

    $conn->query("
        UPDATE users 
        SET password='$new', is_password_reset=1 
        WHERE email='$email'
    ");

    header("Location: login.php");
    exit;
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | Pharmacy System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #198754);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
            padding: 2rem;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        input {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>





    <div class="card">
        <h3 class="mb-4">Reset Password</h3>

        <form method="post">
            <input type="password" name="new_pass" class="form-control" placeholder="New Password" required>
            <button type="submit" name="reset" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
