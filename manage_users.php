<?php
include 'includes/db.php';
include 'includes/sidebar.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM User WHERE id LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $query = "SELECT * FROM User";
}

$result = $conn->query($query);
?>

<div class="main-content" id="main-content">
    <h2>Manage Users</h2>

    <form method="get" class="search-form">
        <input type="text" name="search" placeholder="Search by ID, name or email" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <a href="manage_users.php" class="reset-btn">Reset</a>
    </form>

    <a href="add_user.php" class="add-btn">+ Add New User</a>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    <a href="delete_user.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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