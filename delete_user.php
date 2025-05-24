<?php
include 'includes/db.php';

$id = $_GET['id'];
$conn->query("DELETE FROM User WHERE id = $id");

header("Location: manage_users.php");
exit;
?>
