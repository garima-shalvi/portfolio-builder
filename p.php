<?php
require_once "db.php";

$slug = $_GET['slug'] ?? null;
if(!$slug){
    die("Portfolio not found");
}

$stmt = $conn->prepare(
    "SELECT id, template_name FROM portfolios WHERE slug=?"
);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if(!$result){
    die("Portfolio not found");
}

$pid = $result['id'];
$template = $result['template_name'];

/* 🔥 IMPORTANT LINE */
$_GET['pid'] = $pid;

include $template . ".php";
?>