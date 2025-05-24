<?php
include 'includes/db.php';

$id = $_GET['id'];
$conn->query("DELETE FROM Admin WHERE id = $id");
header("Location: manage_admins.php");
exit;
