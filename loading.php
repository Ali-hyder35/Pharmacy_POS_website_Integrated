<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pharmacy POS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="CSS/STYLES.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #0d6efd, #198754);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
}

.loader-box {
    text-align: center;
    width: 550px;
}

.logo {
    font-size: 2.3rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 25px;
}

.progress {
    height: 12px;
    background-color: rgba(255,255,255,0.3);
    border-radius: 20px;
    overflow: hidden;
}

.progress-bar {
    background-color: #fff;
    width: 0%;
    transition: width 0.3s ease;
}
</style>
</head>

<body>

<div class="loader-box">
    <div class="logo">
         Shahnaz Shabbir Noshahi Pharmacy POS
    </div>
    <div class="subtitle">
        Smart Pharmacy Management System
    </div>

    <div class="progress">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <div class="mt-3" id="loadingText">Loading...</div>
</div>

<script>
let progress = 0;
const bar = document.getElementById("progressBar");
const text = document.getElementById("loadingText");

const interval = setInterval(() => {
    progress += 5;
    bar.style.width = progress + "%";

    if (progress >= 100) {
        clearInterval(interval);
        text.innerText = "Starting POS...";
        setTimeout(() => {
            window.location.href = "main.php"; // landing page
        }, 10000);
    }
}, 120);
</script>

</body>
</html>
    <!-- <a href="auth/login.php" class="btn btn-light btn-lg mt-3">
        Get Started
    </a> -->
  </div>