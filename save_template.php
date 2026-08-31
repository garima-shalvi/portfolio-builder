<?php
include "db.php";

$pid = $_GET['pid'] ?? null;
$template = $_GET['template'] ?? null;

if(!$pid || !$template){
    die("Invalid request");
}

$stmt = $conn->prepare(
    "UPDATE portfolios SET template_name=? WHERE id=?"
);

$stmt->bind_param("si", $template, $pid);
$stmt->execute();
$stmt->close();


$stmt2 = $conn->prepare("SELECT slug FROM portfolios WHERE id=?");
$stmt2->bind_param("i", $pid);
$stmt2->execute();
$result = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

$slug = $result['slug'];

header("Location: p.php?slug=".$slug);
exit;
?>