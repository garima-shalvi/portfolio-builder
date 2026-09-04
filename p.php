<?php
require_once "db.php";

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    http_response_code(404);
    die("Portfolio not found");
}

$stmt = $conn->prepare(
    "SELECT id, template_name FROM portfolios WHERE slug=?"
);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$result) {
    http_response_code(404);
    die("Portfolio not found");
}

$pid = $result['id'];
$template = $result['template_name'];

$allowed_templates = [
    "portfolio",
    "my_template",
    "temp-3",
    "temp-4"
];

if (!in_array($template, $allowed_templates, true)) {
    http_response_code(500);
    die("Invalid template configuration");
}

$_GET['pid'] = $pid;

include __DIR__ . "/" . $template . ".php";
?>