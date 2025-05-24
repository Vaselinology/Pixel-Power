<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/db.php';
include 'includes/sidebar.php';

$query = "
    SELECT Review.id, Customer.name AS customer_name, Product.name AS product_name, 
           Review.rating, Review.comment, Review.date_posted
    FROM Review
    LEFT JOIN Customer ON Review.customer_id = Customer.id
    LEFT JOIN Product ON Review.product_id = Product.id
    ORDER BY Review.date_posted DESC
";
$result = $conn->query($query);
?>

<div class="main-content" id="main-content">
    <h2>Manage Reviews</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date Posted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($row['product_name'] ?? 'Unknown') ?></td>
                    <td><?= $row['rating'] ?></td>
                    <td><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                    <td><?= $row['date_posted'] ?></td>
                    <td>
                        <a href="delete_review.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Are you sure you want to delete this review?')"
                           class="delete-btn">Delete</a>
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
        background: #fff;
        min-height: 100vh;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fdfdfd;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    table th {
        background-color: #f5f5f5;
    }

    .delete-btn {
        padding: 6px 12px;
        background-color: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }
</style>
