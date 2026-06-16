<?php
$host     = 'localhost';
$db_name  = 'snfmstcg_swc_dues';
$username = 'snfmstcg_swc_dues';
$password = '44444444';

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
//SAMPLE CODE ON MY SERVER , FOR TUTORIALS SAKE !!
