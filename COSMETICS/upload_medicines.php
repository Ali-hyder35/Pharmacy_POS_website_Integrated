<?php
require_once "../config/db.php"; // DB connection

// Function to safely convert DD-MM-YYYY or DD/MM/YYYY to YYYY-MM-DD
function convertDate($date) {
    if (!$date) return null; // empty date
    $d = DateTime::createFromFormat('d-m-Y', $date);
    if (!$d) $d = DateTime::createFromFormat('d/m/Y', $date);
    if ($d) return $d->format('Y-m-d');
    return null; // invalid date
}

if (isset($_POST['upload'])) {

    // Check if file was uploaded
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0) {
        die("No file uploaded or there was an upload error.");
    }

    $file = $_FILES['excel_file']['tmp_name'];
    $ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

    // Only allow CSV
    if ($ext != 'csv') {
        die("Please upload a CSV file.");
    }

    $handle = fopen($file, "r");
    if ($handle === false) die("Cannot open file.");

    // Skip header row
    $header = fgetcsv($handle, 1000, ",");

    echo "<h3>CSV Import Results:</h3>";

    // Loop through CSV rows
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {

        // Map columns safely
        $name              = isset($row[1]) ? $row[1] : '';
        
        $category          = isset($row[4]) ? $row[4] : '';
        $rack              = isset($row[5]) ? $row[5] : '';
        $price             = isset($row[7]) && is_numeric($row[7]) ? $row[7] : 0;
        $stock             = isset($row[9]) && is_numeric($row[9]) ? $row[9] : 0;
        $remaining_stock   = isset($row[10]) && is_numeric($row[10]) ? $row[10] : 0;

        // Convert dates to MySQL format or NULL if empty
        $mfg           = isset($row[11]) ? convertDate($row[11]) : null;
        $exp           = isset($row[12]) ? convertDate($row[12]) : null;
        $purchase_date = isset($row[16]) ? convertDate($row[16]) : null;

        $batch_no    = isset($row[13]) ? $row[13] : '';
        $vendor      = isset($row[14]) ? $row[14] : '';
        $strength    = isset($row[17]) ? $row[17] : '';

        // Prepare date values for SQL (NULL if empty)
        $mfg_sql           = $mfg ? "'".addslashes($mfg)."'" : "NULL";
        $exp_sql           = $exp ? "'".addslashes($exp)."'" : "NULL";
        $purchase_date_sql = $purchase_date ? "'".addslashes($purchase_date)."'" : "NULL";

        // Call procedure with OUT parameters
        $sql = "CALL PRC_COSMETICS_ADD(
            '".addslashes($name)."',
            '".addslashes($category)."',
            '".addslashes($rack)."',
            '".addslashes($price)."',
            '".addslashes($stock)."',
            '".addslashes($remaining_stock)."',
            $mfg_sql,
            $exp_sql,
            '".addslashes($batch_no)."',
            '".addslashes($vendor)."',
            $purchase_date_sql,
            '".addslashes($strength)."',
            @PCODE, @PDESC, @PMSG
        )";

        // Execute procedure
        if ($conn->query($sql)) {
            $out = $conn->query("SELECT @PCODE AS code, @PDESC AS description, @PMSG AS message")->fetch_assoc();
            echo "<strong>Cosmetic:</strong> {$name} &rarr; ";
            echo "<strong>Code:</strong> {$out['code']}, ";
            echo "<strong>Status:</strong> {$out['message']}, ";
            echo "<strong>Message:</strong> {$out['description']}<br>";
        } else {
            echo "<strong>Error inserting {$name}:</strong> " . $conn->error . "<br>";
        }
    }

    fclose($handle);
    echo "<br><strong>CSV import completed!</strong>";
}
?>
