<?php
require 'vendor/autoload.php';
require 'config/db.php';

use PHPMailer\PHPMailer\PHPMailer;

$q = $conn->query("
    SELECT 
        YEAR(EXP) y,
        QUARTER(EXP) q,
        name, Batch_No, Vendor, EXP
    FROM medicines
    WHERE EXP >= CURDATE()
      AND EXP <= DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
    ORDER BY y, q, EXP
");

$data = [];
while ($r = $q->fetch_assoc()) {
    $key = "Q{$r['q']}-{$r['y']}";
    $data[$key][] = $r;
}

foreach ($data as $quarter => $rows) {

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'alihaiderg93@gmail.com';
    $mail->Password   = 'ryda zyoe zkyr kknk';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('alihaiderg93@gmail.com', 'Shahnaz Shabbir Noshahi Pharmacy');
    $mail->addAddress('haiderg93@gmail.com', 'alihaiderg93@gmail.com');

    /* 🔹 Dynamic Subject */
    $mail->Subject = "$quarter Medicines Expiry Alert";

    $body = "<h3>$quarter Expiring Medicines</h3><table border='1' cellpadding='5'>
             <tr><th>Name</th><th>Batch</th><th>Vendor</th><th>Expiry</th></tr>";

    foreach ($rows as $m) {
        $body .= "<tr>
                    <td>{$m['name']}</td>
                    <td>{$m['Batch_No']}</td>
                    <td>{$m['Vendor']}</td>
                    <td>{$m['EXP']}</td>
                  </tr>";
    }

    $body .= "</table>";

    $mail->isHTML(true);
    $mail->Body = $body;
    $mail->send();
}
