<?php
include 'includes/db.php';
include 'includes/sidebar.php';

$admins = $conn->query("SELECT * FROM Admin");
?>

<div class="main-content" id="main-content">
    <a href="add_admin.php" class="btn btn-add">+ Add New Admin</a>
    <h2>Manage Admins</h2>
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($admin = $admins->fetch_assoc()): ?>
                <tr>
                    <td><?= $admin['id'] ?></td>
                    <td><?= htmlspecialchars($admin['name']) ?></td>
                    <td><?= htmlspecialchars($admin['email']) ?></td>
                    <td>
                        <a href="edit_admin.php?id=<?= $admin['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_admin.php?id=<?= $admin['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<style>
    .main-content {
        padding: 20px;
    }
    .styled-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .styled-table th, .styled-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .styled-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        margin-right: 5px;
        display: inline-block;
    }
    .btn-add {
        background-color: #28a745;
    }
    .btn-edit {
        background-color: #007bff;
    }
    .btn-delete {
        background-color: #dc3545;
    }
    .btn:hover {
        opacity: 0.9;
    }
</style>
