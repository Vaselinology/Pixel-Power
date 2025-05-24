<?php
include 'includes/db.php'; // DB connection
include 'includes/sidebar.php';

// Fetch products
$sql = "SELECT * FROM Product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
</head>
<body>
    <div class="main-content" id="main-content">
        <h1>Manage Products</h1>
        <a href="add_product.php"><button class="add-product-btn">+ Add New Product</button></a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price ($)</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><img src="<?= $row['image_url'] ?>" width="60" height="60" alt="Product Image"></td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['id'] ?>"><button class="action-btn edit-btn">Edit</button></a>
                                <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')"><button class="action-btn delete-btn">Delete</button></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<style>
    .main-content {
        margin-left: 250px;
        padding: 20px;
    }
    h1 {
        margin-bottom: 20px;
    }

    .add-product-btn {
        margin-bottom: 20px;
        padding: 10px 20px;
        background-color: #1F8EF1;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .add-product-btn:hover {
        background-color: #0f6ad4;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ccc;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
    }

    .action-btn {
        padding: 6px 12px;
        margin-right: 5px;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .edit-btn {
        background-color: #28a745;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .edit-btn:hover {
        background-color: #218838;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }
    </style>