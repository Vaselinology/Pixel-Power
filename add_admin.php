<?php
include 'includes/db.php';
include 'includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Admin (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    header("Location: manage_admins.php");
    exit;
}
?>

<div class="main-content" id="main-content">
    <a href="manage_admins.php" class="btn btn-back">← Back</a>
    <h2>Add New Admin</h2>
    <form method="post" class="admin-form">
        <label>Name</label>
        <input type="text" name="name" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit" class="btn btn-add">Add Admin</button>
    </form>
</div>

<style>
    .main-content {
        padding: 20px;
    }
    .admin-form {
        max-width: 400px;
    }
    .admin-form label {
        display: block;
        margin: 10px 0 5px;
    }
    .admin-form input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
    }
    .btn {
        padding: 8px 16px;
        border: none;
        color: white;
        font-size: 14px;
        cursor: pointer;
        border-radius: 4px;
        text-decoration: none;
    }
    .btn-add {
        background-color: #28a745;
    }
    .btn-back {
        background-color: #6c757d;
        margin-bottom: 20px;
        display: inline-block;
    }
    .btn:hover {
        opacity: 0.9;
    }
</style>
