
<?php
require_once "../config/db.php";
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
if (isset($_POST['create'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role  = $_POST['role'];

    // Check if user already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? ");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // User exists → show message
        echo '
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Oops!</strong> User with this email or phone already exists.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    } else {
        // User does not exist → create new
        $tempPass = rand(100000,999999); // 6-digit password
        $hashTemp = password_hash($tempPass, PASSWORD_DEFAULT);
        $expiry   = date("Y-m-d H:i:s", strtotime("+1 minute"));

        $stmt = $conn->prepare("
            INSERT INTO users (name,email,phone,role,temp_password,temp_expiry)
            VALUES (?,?,?,?,?,?)
        ");
        $stmt->bind_param("ssssss", $name,$email,$phone,$role,$hashTemp,$expiry);
        $stmt->execute();

        // SEND EMAIL
        sendMail($email, $tempPass);

        header("Location: verify_password.php?email=".$email);
        exit;
    }

    $check->close();
}


function sendMail($to, $password) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alihaiderg93@gmail.com';
    $mail->Password = 'ryda zyoe zkyr kknk';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('alihaiderg93@gmail.com', 'Pharmacy System');
    $mail->addAddress($to);

    $mail->Subject = 'Account Password';
    $mail->Body = "Your account password is: $password\nValid for 1 minute.";

    $mail->send();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | Pharmacy System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
     <link href="../CSS/STYLES.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


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
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus-fill"></i> Create Account
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form method="post">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gmail Address</label>
                            <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="03XXXXXXXXX" required>
                        </div>

                        <div class="mb-3">

                            <label class="form-label"> Select Role </label>
                                <select name="role" class="form-select" required>
                                    <option value="" disabled selected>-- Select Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Pharmacists</option>
                                    <option value="salesman">Salesman</option>
                                </select>
                        </div>

                        <div class="d-grid">
                            <button name="create" class="btn btn-success">
                                <i class="bi bi-envelope-check"></i> Create Account
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-footer text-center">
                    <small>
                        Already have an account?
                        <a href="login.php" class="text-decoration-none">Login</a>
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>





<!-- Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="../JS/main.js"></script>

</body>
</html>
