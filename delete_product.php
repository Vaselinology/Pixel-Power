<?php
include 'includes/db.php';

$id = $_GET['id'];
$conn->query("DELETE FROM Product WHERE id = $id");

header("Location: manage_products.php");
exit;
