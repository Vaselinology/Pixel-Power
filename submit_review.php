<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_SESSION['customer_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($product_id && $rating && $comment) {
    $stmt = $conn->prepare("INSERT INTO Review (customer_id, product_id, rating, comment, date_posted) VALUES (?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("iiis", $customer_id, $product_id, $rating, $comment);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: product.php?id=$product_id");
exit;
