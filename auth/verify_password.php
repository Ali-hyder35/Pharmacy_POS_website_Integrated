<?php
session_start();
require_once "../config/db.php";

/* ================= MAIL FUNCTION ================= */
function sendMail($to, $otp) {
    $subject = "Your OTP Code - Pharmacy System";
    $message = "Your OTP is: $otp\n\nThis OTP will expire in 1 minute.";
    $headers = "From: alihaiderg93@gmail.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8";

    return mail($to, $subject, $message, $headers);
}

/* ================= INIT ================= */
$email = $_GET['email'] ?? '';
$error = '';
$showResend = false;

/* ================= VERIFY OTP ================= */
if (isset($_POST['verify'])) {

    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT temp_password, temp_expiry FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();

    if (!$u) {
        $error = "❌ User not found";
        $showResend = true;
    }
    elseif (strtotime($u['temp_expiry']) < time()) {
        $error = "⏰ OTP expired";
        $showResend = true;
    }
    elseif (!password_verify($pass, $u['temp_password'])) {
        $error = "❌ Invalid OTP";
        $showResend = true;
    }
    else {
        $stmt = $conn->prepare("UPDATE users SET status='ACTIVE' WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $_SESSION['toast'] = "🎉 Account verified successfully";
        header("Location: reset_password.php?email=$email");
        exit;
    }
}

/* ================= RESEND OTP ================= */
if (isset($_POST['resend'])) {

    $newOtp  = rand(100000,999999);
    $hashOtp = password_hash($newOtp, PASSWORD_DEFAULT);
    $expiry  = date("Y-m-d H:i:s", strtotime("+1 minute"));
      

    $stmt = $conn->prepare("
        UPDATE users 
        SET temp_password=?, temp_expiry=? 
        WHERE email=?
    ");
    $stmt->bind_param("sss", $hashOtp, $expiry, $email);
    $stmt->execute();

    sendMail($email, $newOtp);
   


    $_SESSION['msg'] = "🔄 New OTP sent to your email ,$newOtp";
    echo "OTP: $newOtp";
    header("Location: verify_password.php?email=$email");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify OTP | Pharmacy System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
    max-width: 400px;
    width: 100%;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,.2);
}
#timer {
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 1rem;
}
</style>
</head>
<body>

<div class="card">

    <div id="timer">01:00</div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="password" name="password" class="form-control mb-3" placeholder="Enter OTP" required>
        <button type="submit" name="verify" id="verifyBtn" class="btn btn-primary w-100">
            Verify OTP
        </button>
    </form>

    <?php if ($showResend): ?>
        <form method="post" class="mt-3">
            <button type="submit" name="resend" class="btn btn-outline-success w-100">
                Resend OTP
            </button>
        </form>
    <?php endif; ?>

    <form method="post" id="autoResendForm" class="mt-3" style="display:none;">
        <button type="submit" name="resend" class="btn btn-outline-success w-100">
            Resend OTP
        </button>
    </form>

</div>

<script>
let time = 60;
const timerEl = document.getElementById("timer");
const verifyBtn = document.getElementById("verifyBtn");
const autoResendForm = document.getElementById("autoResendForm");

const countdown = setInterval(() => {
    time--;
    timerEl.innerText = `00:${time.toString().padStart(2,'0')}`;

    if (time <= 0) {
        clearInterval(countdown);
        timerEl.innerText = "OTP Expired";
        verifyBtn.disabled = true;
        autoResendForm.style.display = "block";
    }
}, 1000);
</script>

</body>
</html>
