<?php
include 'includes/db.php';

$id = $_GET['id'];
$order = $conn->query("SELECT o.*, u.username FROM `Order` o 
                       LEFT JOIN user u ON o.customer_id = u.id 
                       WHERE o.id = $id")->fetch_assoc();

$items = $conn->query("SELECT oi.*, p.name FROM OrderItem oi 
                       LEFT JOIN Product p ON oi.product_id = p.id 
                       WHERE oi.order_id = $id");
?>

<h3>Order #<?= $order['id'] ?> — <?= ucfirst($order['status']) ?></h3>
<p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?></p>
<p><strong>Date:</strong> <?= $order['order_date'] ?></p>
<hr>
<h4>Items</h4>
<ul>
<?php while ($item = $items->fetch_assoc()): ?>
    <li><?= $item['name'] ?> × <?= $item['quantity'] ?> — $<?= $item['price'] ?></li>
<?php endwhile; ?>
</ul>
