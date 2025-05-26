<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$userId = $_SESSION['user_id'];
$productId = (int)$_GET['id'];

// Check if product exists
$productStmt = $conn->prepare("SELECT id FROM Product WHERE id = ?");
$productStmt->bind_param("i", $productId);
$productStmt->execute();
$productResult = $productStmt->get_result();

if ($productResult->num_rows === 0) {
    header('Location: products.php');
    exit();
}

// Check if already in wishlist
$checkStmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$checkStmt->bind_param("ii", $userId, $productId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    // Add to wishlist
    $insertStmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insertStmt->bind_param("ii", $userId, $productId);
    $insertStmt->execute();
}

// Redirect back to previous page
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'products.php'));
exit();
?>