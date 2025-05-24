<?php
include 'includes/db.php';
include 'includes/sidebar.php';

$orders = $conn->query("SELECT o.*, u.name FROM `Order` o 
                        LEFT JOIN User u ON o.customer_id = u.id 
                        ORDER BY o.order_date DESC");
?>

<div class="main-content" id="main-content">
    <h2>Manage Orders</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $orders->fetch_assoc()): ?>
            <tr onclick="viewOrder(<?= $row['id'] ?>)">
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['order_date'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="orderModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <div id="orderDetails"></div>
  </div>
</div>

<script>
function viewOrder(orderId) {
    fetch(`fetch_order_details.php?id=${orderId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('orderDetails').innerHTML = html;
            document.getElementById('orderModal').style.display = 'block';
        });
}
function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1001;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border-radius: 8px;
        width: 60%;
        max-width: 600px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        cursor: pointer;
    }

    .close:hover {
        color: black;
    }
</style>
