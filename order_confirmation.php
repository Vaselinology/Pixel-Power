<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the order ID from the URL (passed after successful order placement)
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Retrieve order details
$order_sql = "SELECT o.*, u.username, u.email FROM orders o
              JOIN users u ON o.user_id = u.id
              WHERE o.id = $order_id";
$order_result = $conn->query($order_sql);

if ($order_result && $order_result->num_rows > 0) {
    $order = $order_result->fetch_assoc();

    // Retrieve the order items
    $items_sql = "SELECT oi.*, p.name AS product_name, p.price
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = $order_id";
    $items_result = $conn->query($items_sql);
} else {
    echo "Order not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation - Order #<?php echo $order_id; ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <div class="order-confirmation-container">
      <h1>Thank you for your order!</h1>
      <p>Your order has been placed successfully. Here are the details:</p>

      <!-- Order Summary -->
      <h2>Order Summary</h2>
      <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
      <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
      <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
      <p><strong>Shipping Address:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
      <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>

      <!-- Ordered Items -->
      <h3>Items in Your Order</h3>
      <table class="order-items-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $total = 0;
          while ($item = $items_result->fetch_assoc()):
            $item_total = $item['price'] * $item['quantity'];
            $total += $item_total;
          ?>
            <tr>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td>$<?php echo number_format($item['price'], 2); ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td>$<?php echo number_format($item_total, 2); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="order-total">
        <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
      </div>

      <!-- Next Steps -->
      <p>We will notify you by email once your order has been processed and shipped.</p>
      <p><a href="shopping.php" class="continue-shopping-btn">Continue Shopping</a></p>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

<style>
  .order-confirmation-container {
    padding: 40px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .order-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .order-items-table th, .order-items-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  .order-items-table th {
    background-color: #f1f1f1;
  }

  .order-total {
    margin-top: 20px;
    font-size: 1.2rem;
    font-weight: bold;
  }

  .continue-shopping-btn {
    padding: 12px 25px;
    background-color: #1F8EF1;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1rem;
  }

  .continue-shopping-btn:hover {
    background-color: #0D6EFD;
  }
</style>
