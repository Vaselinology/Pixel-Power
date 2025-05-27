<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Database connection
    $host = 'localhost';
    $user = 'root'; 
    $pass = '';     
    $dbname = 'store';
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4");

    // Get the order ID from the URL
    $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
    if ($order_id <= 0) {
        throw new Exception("Invalid order ID");
    }

    // Verify the order belongs to the logged-in user
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    $user_id = (int)$_SESSION['user_id'];
    
    // Modified query to include order_date
    $order_sql = "SELECT o.id, o.customer_id, o.order_date, o.status, o.total_amount,
                         u.name AS customer_name, u.email 
                  FROM orders o
                  JOIN customer c ON o.customer_id = c.id
                  JOIN user u ON c.user_id = u.id
                  WHERE o.id = ? AND c.user_id = ?";
    
    $stmt = $conn->prepare($order_sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    if ($order_result->num_rows === 0) {
        throw new Exception("Order not found or you don't have permission to view this order.");
    }

    $order = $order_result->fetch_assoc();

    // Retrieve the order items
    $items_sql = "SELECT oi.*, p.name AS product_name, p.price, p.image_url
                  FROM orderitem oi
                  JOIN product p ON oi.product_id = p.id
                  WHERE oi.order_id = ?";
    
    $stmt = $conn->prepare($items_sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items_result = $stmt->get_result();
    $items = $items_result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $error_message = $e->getMessage();
    if (strpos($error_message, "Database error") !== false) {
        error_log($error_message);
        $error_message = "We're experiencing technical difficulties. Please try again later.";
    }
    die($error_message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?= htmlspecialchars($order['id']) ?> - Pixel Power</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f0f1a;
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .order-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .order-items {
            display: grid;
            gap: 15px;
        }
        .order-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-price {
            font-weight: bold;
            color: #7b2cbf;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(90deg, #7b2cbf 0%, #3a0ca3 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(123, 44, 191, 0.3);
        }
           .order-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        gap: 1rem;
    }
    
    .cancel-form {
        flex: 1;
    }
    
    .cancel-btn {
        width: 100%;
        padding: 12px;
        background: hsla(355, 100.00%, 63.90%, 0.10);
        color: var(--danger);
        border: 1px solid var(--danger);
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .cancel-btn:hover {
        background: rgba(255, 71, 87, 0.2);
    }
    
    .status-badge {
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
    }
    
    .status-badge.cancelled {
        background: rgba(255, 71, 87, 0.1);
        color: var(--danger);
        border: 1px solid var(--danger);
    }
    .cancellation-policy {
        background: rgba(31, 142, 241, 0.1);
        padding: 1rem;
        border-radius: 8px;
        margin: 1.5rem 0;
        border-left: 4px solid var(--primary);
    }
    
    .cancellation-policy h3 {
        margin-top: 0;
        color: var(--primary);
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-header">
            <h1>Order #<?= htmlspecialchars($order['id']) ?></h1>
            <p>Date: <?= date('F j, Y', strtotime($order['order_date'])) ?></p>
            <p>Status: <span style="color: #2ecc71;"><?= htmlspecialchars($order['status']) ?></span></p>
            <p>Total: $<?= number_format($order['total_amount'], 2) ?></p>
        </div>

        <h2>Order Items</h2>
        <div class="order-items">
            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="item-image">
                    <div class="item-details">
                        <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                        <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                    </div>
                    <div class="item-price">
                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Add this before the order actions -->
<div class="cancellation-policy">
    <h3><i class="fas fa-info-circle"></i> Cancellation Policy</h3>
    <p>Orders can be cancelled within 24 hours of placement or before they are shipped. 
       Refunds for cancelled orders will be processed within 3-5 business days.</p>
</div>
           <!-- Add this right after displaying the order items -->
<div class="order-actions">
    <?php if ($order['status'] === 'placed' || $order['status'] === 'confirmed'): ?>
        <form method="POST" action="cancel_order.php" class="cancel-form">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this order?')">
                <i class="fas fa-times-circle"></i> Cancel Order
            </button>
        </form>
    <?php elseif ($order['status'] === 'cancelled'): ?>
        <div class="status-badge cancelled">
            <i class="fas fa-ban"></i> Order Cancelled
        </div>
    <?php endif; ?>
    
    <a href="<?php echo dirname($_SERVER['PHP_SELF']) === '/' ? '' : dirname($_SERVER['PHP_SELF']); ?>/userprofile.php#orders" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to My Orders
    </a>
</div>

 
   
</body>
</html>