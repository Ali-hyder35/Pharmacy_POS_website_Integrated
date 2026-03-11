<?php
$host="127.0.0.1";
 $user="root";
 $pass="Oracle@123";
 $db="pharmacy_pos";
$port=3306;
$conn = new mysqli($host,$user,$pass,$db,$port);
         if($conn->connect_errno)
            { 
                die("Database connection failed: ".$conn->connect_error);
             }
        else
        {
            $conn->set_charset("utf8mb4");
        }
?>