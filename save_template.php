<?php
session_start();
include "db.php";

if (!isset($_SESSION['auth_id'])) {
    http_response_code(401);
    die("Not logged in");
}

$pid = $_GET['pid'] ?? null;
$template = $_GET['template'] ?? null;

if (!$pid || !$template) {
    http_response_code(400);
    die("Invalid request");
}

$allowed_templates = [
    "portfolio",
    "my_template",
    "temp-3",
    "temp-4"
];

if (!in_array($template, $allowed_templates, true)) {
    http_response_code(400);
    die("Invalid template");
}

$user_id = $_SESSION['auth_id'];

$stmt = $conn->prepare(
    "SELECT id FROM portfolios WHERE id=? AND user_id=?"
);
$stmt->bind_param("ii", $pid, $user_id);
$stmt->execute();

if (!$stmt->get_result()->fetch_assoc()) {
    http_response_code(403);
    die("Unauthorized");
}

$stmt->close();

$stmt = $conn->prepare(
    "UPDATE portfolios SET template_name=? WHERE id=?"
);
$stmt->bind_param("si", $template, $pid);
$stmt->execute();
$stmt->close();

$stmt2 = $conn->prepare(
    "SELECT slug FROM portfolios WHERE id=?"
);
$stmt2->bind_param("i", $pid);
$stmt2->execute();
$result = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

if (!$result) {
    http_response_code(404);
    die("Portfolio not found");
}

$slug = $result['slug'];

header("Location: p.php?slug=" . urlencode($slug));
exit;
?>