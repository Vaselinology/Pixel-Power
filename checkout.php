<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// First check stock availability before proceeding
$stock_errors = [];
foreach ($_SESSION['cart'] as $product_id => $item) {
    // Get current stock from database
    $stock_query = $conn->prepare("SELECT stock FROM Product WHERE id = ?");
    $stock_query->bind_param("i", $product_id);
    $stock_query->execute();
    $stock_result = $stock_query->get_result();
    
    if ($stock_result->num_rows > 0) {
        $product = $stock_result->fetch_assoc();
        if ($item['quantity'] > $product['stock']) {
            $stock_errors[$product_id] = "Only " . $product['stock'] . " items available for " . $item['name'];
        }
    } else {
        $stock_errors[$product_id] = "Product no longer available";
    }
}

// Calculate total only if no stock errors
$total = 0;
if (empty($stock_errors)) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total += $item['price'] * $item['quantity'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($stock_errors)) {
    // Validate user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to checkout");
    }

    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // First get the customer_id associated with this user
        $customer_query = "SELECT id FROM customer WHERE user_id = ?";
        $stmt = $conn->prepare($customer_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $customer_result = $stmt->get_result();
        
        if ($customer_result->num_rows === 0) {
            throw new Exception("No customer profile found for this user");
        }
        
        $customer = $customer_result->fetch_assoc();
        $customer_id = $customer['id'];

        // Insert order - using the correct customer_id
        $order_sql = "INSERT INTO orders (customer_id, order_date, status, total_amount) 
                     VALUES (?, NOW(), 'placed', ?)";
        $stmt = $conn->prepare($order_sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("id", $customer_id, $total);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $order_id = $conn->insert_id;
        
        // Insert order items
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $insert_item = $conn->prepare("INSERT INTO orderitem (order_id, product_id, quantity, price)
                                          VALUES (?, ?, ?, ?)");
            if (!$insert_item) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $insert_item->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
            
            if (!$insert_item->execute()) {
                throw new Exception("Execute failed: " . $insert_item->error);
            }
            
            // Update product stock
            $update_stmt = $conn->prepare("UPDATE Product SET stock = stock - ? WHERE id = ?");
            $update_stmt->bind_param("ii", $item['quantity'], $product_id);
            $update_stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        // Clear cart and redirect
        unset($_SESSION['cart']);
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error = "Error placing order: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - Pixel Power</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #1F8EF1;
      --primary-dark: #0D6EFD;
      --accent: #FC00FF;
      --danger: #FF4757;
      --success: #2ecc71;
      --dark-bg: #0A1728;
      --card-bg: #1D1F2A;
      --card-border: #2A2D3D;
      --text-light: #E0E0E0;
      --text-lighter: #A1A1C2;
    }

    body {
      background-color: var(--dark-bg);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }

    .checkout-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 2rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }

    @media (max-width: 768px) {
      .checkout-container {
        grid-template-columns: 1fr;
      }
    }

    .section-title {
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-weight: 800;
    }

    /* Order Summary Styles */
    .order-summary {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      border: 1px solid var(--card-border);
      height: fit-content;
    }

    .cart-items {
      max-height: 400px;
      overflow-y: auto;
      margin-bottom: 1.5rem;
    }

    .cart-item {
      display: flex;
      gap: 1.5rem;
      padding: 1rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .item-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .item-details {
      flex: 1;
    }

    .item-name {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .item-price {
      color: var(--primary);
      font-weight: 700;
    }

    .item-quantity {
      color: var(--text-lighter);
      font-size: 0.9rem;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .summary-row:last-child {
      border-bottom: none;
      font-weight: 700;
      font-size: 1.2rem;
    }

    .total-amount {
      color: var(--primary);
      font-weight: 800;
    }

    /* Checkout Form Styles */
    .checkout-form {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      border: 1px solid var(--card-border);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      color: var(--text-light);
      font-weight: 500;
    }

    .form-input {
      width: 100%;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      color: white;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(31, 142, 241, 0.2);
    }

    textarea.form-input {
      min-height: 100px;
      resize: vertical;
    }

    .payment-methods {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }

    .payment-method {
      position: relative;
    }

    .payment-method input {
      position: absolute;
      opacity: 0;
    }

    .payment-method label {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .payment-method label:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .payment-method input:checked + label {
      border-color: var(--primary);
      background: rgba(31, 142, 241, 0.1);
    }

    .payment-icon {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    .payment-name {
      font-size: 0.9rem;
    }

    .checkout-btn {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(90deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .checkout-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(31, 142, 241, 0.4);
    }

    .error-message {
      color: var(--danger);
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    .secure-checkout {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1rem;
      color: var(--text-lighter);
      font-size: 0.9rem;
    }

    .secure-checkout i {
      color: var(--success);
    }
  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="checkout-container">
    <!-- Order Summary Section -->
    <section class="order-summary">
      <h2 class="section-title">Order Summary</h2>
      
      <div class="cart-items">
        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
          <div class="cart-item">
            <img src="images/products/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-image">
            <div class="item-details">
              <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
              <div class="item-price">$<?= number_format($item['price'], 2) ?></div>
              <div class="item-quantity">Quantity: <?= $item['quantity'] ?></div>
              <?php if (isset($stock_errors[$product_id])): ?>
                <div class="error-message" style="color: var(--danger); margin-top: 0.5rem;">
                  <i class="fas fa-exclamation-circle"></i> <?= $stock_errors[$product_id] ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if (empty($stock_errors)): ?>
        <div class="summary-total">
          <div class="summary-row">
            <span>Subtotal</span>
            <span>$<?= number_format($total, 2) ?></span>
          </div>
          <div class="summary-row">
            <span>Shipping</span>
            <span>FREE</span>
          </div>
          <div class="summary-row">
            <span>Tax</span>
            <span>$<?= number_format($total * 0.1, 2) ?></span>
          </div>
          <div class="summary-row">
            <span>Total</span>
            <span class="total-amount">$<?= number_format($total * 1.1, 2) ?></span>
          </div>
        </div>
      <?php else: ?>
        <div class="error-message" style="margin-top: 1rem; padding: 1rem; background: rgba(255, 71, 87, 0.1); border-radius: 8px;">
          <i class="fas fa-exclamation-triangle"></i> Please adjust your cart quantities to match available stock before proceeding.
        </div>
      <?php endif; ?>
    </section>

    <!-- Checkout Form Section -->
    <section class="checkout-form">
      <h2 class="section-title">Shipping & Payment</h2>
      
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="checkout.php">
        <div class="form-group">
          <label for="address" class="form-label">Shipping Address</label>
          <textarea id="address" name="address" class="form-input" required <?= !empty($stock_errors) ? 'disabled' : '' ?>></textarea>
        </div>

        <div class="form-group">
          <label for="phone" class="form-label">Phone Number</label>
          <input type="tel" id="phone" name="phone" class="form-input" required <?= !empty($stock_errors) ? 'disabled' : '' ?>>
        </div>

        <div class="form-group">
          <label class="form-label">Payment Method</label>
          <div class="payment-methods">
            <div class="payment-method">
              <input type="radio" id="credit-card" name="payment_method" value="credit_card" checked <?= !empty($stock_errors) ? 'disabled' : '' ?>>
              <label for="credit-card">
                <i class="fas fa-credit-card payment-icon"></i>
                <span class="payment-name">Credit Card</span>
              </label>
            </div>
            <div class="payment-method">
              <input type="radio" id="paypal" name="payment_method" value="paypal" <?= !empty($stock_errors) ? 'disabled' : '' ?>>
              <label for="paypal">
                <i class="fab fa-paypal payment-icon"></i>
                <span class="payment-name">PayPal</span>
              </label>
            </div>
            <div class="payment-method">
              <input type="radio" id="crypto" name="payment_method" value="crypto" <?= !empty($stock_errors) ? 'disabled' : '' ?>>
              <label for="crypto">
                <i class="fas fa-coins payment-icon"></i>
                <span class="payment-name">Crypto</span>
              </label>
            </div>
          </div>
        </div>

        <button type="submit" class="checkout-btn" <?= !empty($stock_errors) ? 'disabled' : '' ?>>
          <i class="fas fa-lock"></i> Complete Order
        </button>

        <?php if (!empty($stock_errors)): ?>
          <div class="error-message" style="margin-top: 1rem;">
            <i class="fas fa-exclamation-circle"></i> You cannot proceed with checkout until all items are within available stock limits.
          </div>
        <?php endif; ?>

        <div class="secure-checkout">
          <i class="fas fa-shield-alt"></i>
          <span>Secure Checkout</span>
        </div>
      </form>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>

  <script>
    // Simple animation for payment methods
    document.querySelectorAll('.payment-method').forEach(method => {
      method.addEventListener('click', function() {
        document.querySelectorAll('.payment-method label').forEach(label => {
          label.style.transform = 'scale(1)';
        });
        this.querySelector('label').style.transform = 'scale(1.05)';
      });
    });
  </script>
</body>
</html>