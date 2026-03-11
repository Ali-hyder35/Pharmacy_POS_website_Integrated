<?php

require_once "config/db.php";

$type = $_GET['type'];

if($type == "medicine"){
$q = $conn->query("SELECT id,name,stock FROM medicines ORDER BY name");
}else{
$q = $conn->query("SELECT id,name,stock FROM cosmetics ORDER BY name");
}

$data = [];

while($r = $q->fetch_assoc()){
$data[] = $r;
}

echo json_encode($data);