<?php
include 'includes/db.php';
include 'includes/sidebar.php';

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM User WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $updateQuery = "UPDATE User SET name='$name', email='$email'";
    
    if (!empty($_POST['password'])) {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updateQuery .= ", password='$hashedPassword'";
    }

    $updateQuery .= " WHERE id=$id";

    $conn->query($updateQuery);
    header("Location: manage_users.php");
    exit;
}
?>

<div class="main-content" id="main-content">
    <a href="manage_users.php" class="go-back-btn"><i class="fas fa-arrow-left"></i> Go Back</a>
    <h2>Edit User</h2>
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>New Password (leave blank to keep existing)</label>
        <input type="password" name="password">

        <button type="submit">Update User</button>
    </form>
</div>
<style>
    .main-content {
        margin-left: 260px;
        padding: 20px;
        background-color: #fff;
        min-height: 100vh;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #f9f9f9;
    }

    .admin-table th, .admin-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .admin-table th {
        background-color: #343a40;
        color: white;
    }

    .admin-table tr:hover {
        background-color: #f1f1f1;
    }

    .add-btn {
        display: inline-block;
        margin-bottom: 15px;
        padding: 10px 16px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .add-btn:hover {
        background-color: #0069d9;
    }

    .edit-btn,
    .delete-btn {
        padding: 6px 10px;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        margin-right: 6px;
    }

    .edit-btn {
        background-color: #28a745;
    }

    .edit-btn:hover {
        background-color: #218838;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    form {
        background-color: #fefefe;
        padding: 20px;
        border-radius: 6px;
        max-width: 600px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    form label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    form button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #0069d9;
    }

    .go-back-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 8px 14px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .go-back-btn i {
        margin-right: 6px;
    }

    .go-back-btn:hover {
        background-color: #5a6268;
    }
    .search-form {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-form input[type="text"] {
        padding: 8px;
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .search-form button {
        padding: 8px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .search-form button:hover {
        background-color: #0069d9;
    }

    .reset-btn {
        padding: 8px 12px;
        background-color: #6c757d;
        color: white;
        border-radius: 4px;
        text-decoration: none;
    }

    .reset-btn:hover {
        background-color: #5a6268;
    }

</style>