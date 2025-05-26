<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: wishlist.php');
    exit();
}

$userId = $_SESSION['user_id'];
$productId = (int)$_GET['id'];

// Remove from wishlist
$deleteStmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
$deleteStmt->bind_param("ii", $userId, $productId);
$deleteStmt->execute();

// Redirect back to wishlist
header('Location: wishlist.php');
exit();
?>