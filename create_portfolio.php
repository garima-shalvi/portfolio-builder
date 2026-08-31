<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['auth_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['auth_id'];   


$slug = $_SESSION['username'] . "_" . substr(md5(time()), 0, 6);


$stmt = $conn->prepare(
    "INSERT INTO portfolios (user_id, slug) VALUES (?, ?)"
);

$stmt->bind_param("is", $user_id, $slug);
$stmt->execute();

$portfolio_id = $stmt->insert_id;
$stmt->close();


header("Location: abc.php?portfolio_id=".$portfolio_id);
exit();
?>