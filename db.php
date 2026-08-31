<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "portfolio";

$conn = new mysqli($host, $user, $pass, $db, 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
