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

// Add item to cart
if (isset($_GET['add'])) {
    $product_id = (int)$_GET['add'];
    $quantity = 1; // You can add functionality for choosing quantity

    // Check if product exists in the database
    $sql = "SELECT * FROM Product WHERE id = $product_id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // If the product is already in the cart, just update the quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image_url']
            ];
        }
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Update item quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <div class="cart-container">
      <h1>Your Shopping Cart</h1>

      <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="cart.php">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
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
                  <td>
                    <img src="images/products/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50">
                    <?php echo htmlspecialchars($item['name']); ?>
                  </td>
                  <td>$<?php echo number_format($item['price'], 2); ?></td>
                  <td>
                    <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                  </td>
                  <td>$<?php echo number_format($item_total, 2); ?></td>
                  <td><a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-btn">Remove</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="cart-summary">
            <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
            <button type="submit" name="update_cart" class="update-cart-btn">Update Cart</button>
          </div>
        </form>

        <div class="checkout">
          <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>

      <?php else: ?>
        <p>Your cart is empty.</p>
      <?php endif; ?>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

<style>
  .cart-container {
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

  .cart-table img {
    max-width: 50px;
    margin-right: 10px;
  }

  .cart-summary {
    margin-top: 20px;
  }

  .update-cart-btn, .checkout-btn {
    padding: 12px 25px;
    background-color: #1F8EF1;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .update-cart-btn:hover, .checkout-btn:hover {
    background-color: #0D6EFD;
  }

  .remove-btn {
    color: red;
    text-decoration: none;
  }

  .remove-btn:hover {
    text-decoration: underline;
  }

  .checkout {
    margin-top: 20px;
  }
</style>
