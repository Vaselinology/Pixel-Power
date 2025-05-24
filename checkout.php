<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect to cart page if empty
    exit();
}

// Initialize variables
$total = 0;
foreach ($_SESSION['cart'] as $product_id => $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle the form submission (place order)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get customer details from form submission
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in session
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method']; // This can be used for payment system integration
    
    // Insert order into orders table
    $order_sql = "INSERT INTO orders (user_id, total_amount, shipping_address, phone_number, payment_method) 
                  VALUES ($user_id, $total, '$address', '$phone', '$payment_method')";
    if ($conn->query($order_sql) === TRUE) {
        $order_id = $conn->insert_id; // Get the order ID

        // Insert each product into the order_items table
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];
            $product_name = $item['name'];
            $order_item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                               VALUES ($order_id, $product_id, '$product_name', $quantity, $price)";
            $conn->query($order_item_sql);
        }

        // Clear the cart after placing the order
        unset($_SESSION['cart']);

        // Redirect to a thank you or confirmation page
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <div class="checkout-container">
      <h1>Checkout</h1>

      <!-- Cart Summary -->
      <h2>Your Cart</h2>
      <table class="cart-table">
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
          foreach ($_SESSION['cart'] as $product_id => $item): 
            $item_total = $item['price'] * $item['quantity'];
            $total += $item_total;
          ?>
            <tr>
              <td><?php echo htmlspecialchars($item['name']); ?></td>
              <td>$<?php echo number_format($item['price'], 2); ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td>$<?php echo number_format($item_total, 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="cart-summary">
        <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
      </div>

      <!-- Checkout Form -->
      <form method="POST" action="checkout.php">
        <div class="form-group">
          <label for="address">Shipping Address</label>
          <textarea name="address" id="address" required></textarea>
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" name="phone" id="phone" required>
        </div>
        <div class="form-group">
          <label for="payment_method">Payment Method</label>
          <select name="payment_method" id="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <!-- Add more payment options as necessary -->
          </select>
        </div>
        <button type="submit" class="checkout-btn">Place Order</button>
      </form>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

<style>
  .checkout-container {
    padding: 40px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .cart-table th, .cart-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  .cart-table th {
    background-color: #f1f1f1;
  }

  .cart-summary {
    margin-top: 20px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    font-weight: bold;
  }

  .form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
  }

  .checkout-btn {
    padding: 12px 25px;
    background-color: #1F8EF1;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .checkout-btn:hover {
    background-color: #0D6EFD;
  }
</style>
