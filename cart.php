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
    $quantity = 1; // Default quantity

    // Check if product exists
    $sql = "SELECT * FROM Product WHERE id = $product_id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

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
        $_SESSION['cart_message'] = "Item added to cart!";
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['cart_message'] = "Item removed from cart!";
    }
}

// Update item quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = max(1, (int)$quantity);
        }
    }
    $_SESSION['cart_message'] = "Cart updated!";
}

// Clear cart message after display
$cart_message = $_SESSION['cart_message'] ?? '';
unset($_SESSION['cart_message']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart - Pixel Power</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #1F8EF1;
      --primary-dark: #0D6EFD;
      --accent: #FC00FF;
      --danger: #FF4757;
      --dark-bg: #0A1728;
      --card-bg: #1D1F2A;
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

    .cart-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 2rem;
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    h1 {
      font-size: 2.5rem;
      margin: 0;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-weight: 800;
    }

    .cart-message {
      padding: 1rem;
      background: rgba(31, 142, 241, 0.2);
      border-left: 4px solid var(--primary);
      margin-bottom: 2rem;
      border-radius: 4px;
      animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .empty-cart {
      text-align: center;
      padding: 3rem;
      color: var(--text-lighter);
    }

    .empty-cart i {
      font-size: 5rem;
      margin-bottom: 1rem;
      color: var(--text-lighter);
      opacity: 0.5;
    }

    .empty-cart h2 {
      font-size: 1.8rem;
      margin-bottom: 1rem;
    }

    .cart-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 15px;
    }

    .cart-table thead th {
      padding: 1rem;
      text-align: left;
      color: var(--text-lighter);
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.9rem;
      border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .cart-table tbody tr {
      background: rgba(255, 255, 255, 0.03);
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .cart-table tbody tr:hover {
      background: rgba(255, 255, 255, 0.07);
      transform: translateY(-2px);
    }

    .cart-table td {
      padding: 1.5rem 1rem;
      vertical-align: middle;
    }

    .product-cell {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .product-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }

    .product-image:hover {
      transform: scale(1.05);
    }

    .product-name {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .product-price {
      color: var(--primary);
      font-weight: 700;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .quantity-btn {
      width: 30px;
      height: 30px;
      background: rgba(255, 255, 255, 0.1);
      border: none;
      color: white;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .quantity-btn:hover {
      background: var(--primary);
      transform: scale(1.1);
    }

    .quantity-input {
      width: 50px;
      padding: 0.5rem;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      color: white;
      text-align: center;
      font-weight: 600;
    }

    .quantity-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(31, 142, 241, 0.2);
    }

    .item-total {
      font-weight: 700;
      color: var(--text-light);
    }

    .remove-btn {
      color: var(--danger);
      background: none;
      border: none;
      font-size: 1.2rem;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .remove-btn:hover {
      transform: scale(1.2);
    }

    .cart-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .cart-summary {
      text-align: right;
    }

    .cart-total {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    .total-amount {
      color: var(--primary);
      font-weight: 800;
    }

    .action-btn {
      padding: 1rem 2rem;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .continue-btn {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      text-decoration: none;
    }

    .continue-btn:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .update-cart-btn {
      background: rgba(255, 255, 255, 0.1);
      color: white;
    }

    .update-cart-btn:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .checkout-btn {
      background: linear-gradient(90deg, var(--primary), var(--primary-dark));
      color: white;
      text-decoration: none;
      box-shadow: 0 4px 15px rgba(31, 142, 241, 0.3);
    }

    .checkout-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(31, 142, 241, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .cart-container {
        padding: 1rem;
      }
      
      .cart-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .cart-table thead {
        display: none;
      }
      
      .cart-table tbody tr {
        display: block;
        margin-bottom: 1.5rem;
        padding: 1rem;
      }
      
      .cart-table td {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      }
      
      .cart-table td::before {
        content: attr(data-label);
        color: var(--text-lighter);
        font-weight: 500;
        margin-right: 1rem;
      }
      
      .product-cell {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .cart-actions {
        flex-direction: column;
        gap: 1rem;
      }
      
      .cart-summary {
        text-align: center;
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <div class="cart-container">
      <div class="cart-header">
        <h1>Your Gaming Cart</h1>
        <a href="shopping.php" class="continue-btn action-btn">
          <i class="fas fa-arrow-left"></i> Continue Shopping
        </a>
      </div>

      <?php if (!empty($cart_message)): ?>
        <div class="cart-message">
          <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($cart_message); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="cart.php">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
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
                  <td data-label="Product" class="product-cell">
                    <img src="images/products/<?php echo $item['image']; ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                         class="product-image">
                    <div>
                      <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                      <div class="product-price">$<?php echo number_format($item['price'], 2); ?></div>
                    </div>
                  </td>
                  <td data-label="Price">$<?php echo number_format($item['price'], 2); ?></td>
                  <td data-label="Quantity">
                    <div class="quantity-control">
                      <button type="button" class="quantity-btn minus" data-id="<?php echo $product_id; ?>">-</button>
                      <input type="number" 
                             name="quantity[<?php echo $product_id; ?>]" 
                             value="<?php echo $item['quantity']; ?>" 
                             min="1"
                             class="quantity-input">
                      <button type="button" class="quantity-btn plus" data-id="<?php echo $product_id; ?>">+</button>
                    </div>
                  </td>
                  <td data-label="Total" class="item-total">$<?php echo number_format($item_total, 2); ?></td>
                  <td data-label="Action">
                    <button type="button" 
                            onclick="window.location.href='cart.php?remove=<?php echo $product_id; ?>'" 
                            class="remove-btn"
                            title="Remove item">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          
          <div class="cart-actions">
            <button type="submit" name="update_cart" class="action-btn update-cart-btn">
              <i class="fas fa-sync-alt"></i> Update Cart
            </button>
            
            <div class="cart-summary">
              <div class="cart-total">
                Order Total: <span class="total-amount">$<?php echo number_format($total, 2); ?></span>
              </div>
              <a href="checkout.php" class="action-btn checkout-btn">
                <i class="fas fa-credit-card"></i> Proceed to Checkout
              </a>
            </div>
          </div>
        </form>

      <?php else: ?>
        <div class="empty-cart">
          <i class="fas fa-shopping-cart"></i>
          <h2>Your cart is empty</h2>
          <p>Start your gaming journey by adding some awesome products</p>
          <a href="shopping.php" class="action-btn checkout-btn" style="margin-top: 1rem;">
            <i class="fas fa-gamepad"></i> Browse Products
          </a>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>

  <script>
    // Quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.quantity-input');
        let value = parseInt(input.value);
        
        if (this.classList.contains('minus')) {
          if (value > 1) input.value = value - 1;
        } else {
          input.value = value + 1;
        }
        
        // Trigger change event to update any dependencies
        input.dispatchEvent(new Event('change'));
      });
    });

    // Quantity input validation
    document.querySelectorAll('.quantity-input').forEach(input => {
      input.addEventListener('change', function() {
        if (this.value < 1) this.value = 1;
        if (this.value > 99) this.value = 99;
      });
    });

    // Show confirmation before removing item
    document.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
          e.preventDefault();
        }
      });
    });
  </script>
 

<!-- Add this just before </body> in your cart.php -->
<div id="chatbot-container" style="position:fixed;bottom:20px;right:20px;width:320px;background:var(--card-bg);border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,0.3);z-index:1000;display:none;border:1px solid var(--primary);font-family:inherit;">
  <div id="chatbot-header" style="background:linear-gradient(90deg, var(--primary), var(--accent));color:white;padding:12px 15px;border-radius:10px 10px 0 0;cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
    <h3 style="margin:0;font-size:1.1rem;"><i class="fas fa-robot"></i> PixelBot Assistant</h3>
    <button id="chatbot-close" style="background:none;border:none;color:white;font-size:1.3rem;cursor:pointer;">×</button>
  </div>
  <div id="chatbot-messages" style="height:300px;overflow-y:auto;padding:15px;background:var(--dark-bg);">
    <div class="chatbot-message" style="background:rgba(255,255,255,0.1);padding:10px 15px;border-radius:15px;margin-bottom:12px;max-width:85%;">
      <p style="margin:0;font-size:0.9rem;">🎮 Hi there! I'm PixelBot, your gaming expert. Ask me about:</p>
      <ul style="margin:8px 0 0 15px;padding:0;font-size:0.85rem;">
        <li>Our products (mice, keyboards, headsets)</li>
        <li>Prices and availability</li>
        <li>Shipping information</li>
        <li>Payment options</li>
      </ul>
    </div>
  </div>
  <div style="padding:12px;background:var(--card-bg);border-top:1px solid rgba(255,255,255,0.1);border-radius:0 0 10px 10px;display:flex;">
    <input type="text" id="chatbot-input" placeholder="Ask about gaming gear..." style="flex:1;padding:10px;border-radius:20px;border:1px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.05);color:white;font-family:inherit;">
    <button id="chatbot-send" style="background:var(--primary);color:white;border:none;border-radius:50%;width:36px;height:36px;margin-left:8px;cursor:pointer;font-size:1rem;"><i class="fas fa-paper-plane"></i></button>
  </div>
</div>

<button id="chatbot-toggle" style="position:fixed;bottom:20px;right:20px;background:linear-gradient(90deg, var(--primary), var(--accent));color:white;border:none;border-radius:50%;width:60px;height:60px;font-size:1.5rem;cursor:pointer;box-shadow:0 5px 15px rgba(0,0,0,0.3);z-index:999;transition:transform 0.2s;">
  <i class="fas fa-robot"></i>
</button>

<script>
// Chatbot functionality - pure JavaScript (no JSX/React)
document.addEventListener('DOMContentLoaded', function() {
  const chatbotToggle = document.getElementById('chatbot-toggle');
  const chatbotContainer = document.getElementById('chatbot-container');
  const chatbotClose = document.getElementById('chatbot-close');
  const chatbotMessages = document.getElementById('chatbot-messages');
  const chatbotInput = document.getElementById('chatbot-input');
  const chatbotSend = document.getElementById('chatbot-send');

  // Product knowledge - you can expand this
  const products = {
    "mouse": [
      { 
        name: "Logitech G502 HERO", 
        price: "$49.99", 
        desc: "High-performance gaming mouse with 11 programmable buttons",
        stock: "In stock"
      },
      {
        name: "Razer Viper Mini",
        price: "$39.99",
        desc: "Lightweight gaming mouse with RGB lighting",
        stock: "In stock"
      }
    ],
    "keyboard": [
      {
        name: "Razer BlackWidow V3",
        price: "$119.99",
        desc: "Mechanical gaming keyboard with Razer Green switches",
        stock: "7 left"
      }
    ],
    "headset": [
      {
        name: "SteelSeries Arctis 7",
        price: "$149.99",
        desc: "Wireless gaming headset with 24-hour battery",
        stock: "7 left"
      }
    ]
  };

  // Chatbot responses
  const responses = {
    greeting: "🎮 Hello! I'm PixelBot, your gaming assistant. How can I help?",
    help: "I can tell you about our products, prices, and shipping info. Try asking about mice, keyboards, or headsets!",
    shipping: "🚚 <strong>Shipping Info:</strong> Free shipping on orders over $50. Delivery in 2-3 business days.",
    payment: "💳 <strong>Payment Options:</strong> We accept Visa, Mastercard, PayPal, and crypto.",
    default: "I'm not sure I understand. Try asking about specific products like 'gaming mice' or 'keyboards'."
  };

  // Add message to chat
  function addMessage(text, isUser = false) {
    const msgDiv = document.createElement('div');
    msgDiv.className = 'chatbot-message';
    msgDiv.style.background = isUser ? 'var(--primary)' : 'rgba(255,255,255,0.1)';
    msgDiv.style.marginLeft = isUser ? 'auto' : '0';
    msgDiv.style.marginRight = isUser ? '0' : 'auto';
    msgDiv.style.padding = '10px 15px';
    msgDiv.style.borderRadius = '15px';
    msgDiv.style.marginBottom = '12px';
    msgDiv.style.maxWidth = '85%';
    msgDiv.innerHTML = `<p style="margin:0;font-size:0.9rem;">${text}</p>`;
    chatbotMessages.appendChild(msgDiv);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }

  // Process user input
  function processInput() {
    const input = chatbotInput.value.trim().toLowerCase();
    if (!input) return;
    
    addMessage(input, true);
    chatbotInput.value = '';
    
    setTimeout(() => {
      let response = '';
      
      // Check for greetings
      if (/hello|hi|hey/.test(input)) {
        response = responses.greeting;
      } 
      // Check for help
      else if (/help|support/.test(input)) {
        response = responses.help;
      }
      // Check for shipping
      else if (/ship|deliver/.test(input)) {
        response = responses.shipping;
      }
      // Check for payment
      else if (/pay|purchase|buy/.test(input)) {
        response = responses.payment;
      }
      // Check for products
      else if (/mouse|mice/.test(input)) {
        response = "<strong>Gaming Mice:</strong><br>" + 
          products.mouse.map(p => 
            `- ${p.name}: ${p.price} (${p.stock})<br><small>${p.desc}</small>`
          ).join('<br><br>');
      }
      else if (/keyboard/.test(input)) {
        response = "<strong>Gaming Keyboards:</strong><br>" + 
          products.keyboard.map(p => 
            `- ${p.name}: ${p.price} (${p.stock})<br><small>${p.desc}</small>`
          ).join('<br><br>');
      }
      else if (/headset|headphone/.test(input)) {
        response = "<strong>Gaming Headsets:</strong><br>" + 
          products.headset.map(p => 
            `- ${p.name}: ${p.price} (${p.stock})<br><small>${p.desc}</small>`
          ).join('<br><br>');
      }
      else {
        response = responses.default;
      }
      
      addMessage(response);
    }, 600);
  }

  // Toggle chatbot
  chatbotToggle.addEventListener('click', function() {
    chatbotContainer.style.display = chatbotContainer.style.display === 'none' ? 'block' : 'none';
    this.style.transform = chatbotContainer.style.display === 'none' ? 'scale(1)' : 'scale(1.1)';
  });

  chatbotClose.addEventListener('click', function() {
    chatbotContainer.style.display = 'none';
    chatbotToggle.style.transform = 'scale(1)';
  });

  // Event listeners
  chatbotSend.addEventListener('click', processInput);
  chatbotInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') processInput();
  });

  // Add quick replies
  function addQuickReplies() {
    const quickReplies = ['Show mice', 'Keyboard options', 'Headsets', 'Shipping info'];
    const container = document.createElement('div');
    container.style.marginTop = '10px';
    container.style.display = 'flex';
    container.style.flexWrap = 'wrap';
    container.style.gap = '5px';
    
    quickReplies.forEach(text => {
      const btn = document.createElement('button');
      btn.textContent = text;
      btn.style.padding = '5px 10px';
      btn.style.background = 'rgba(255,255,255,0.1)';
      btn.style.border = '1px solid var(--primary)';
      btn.style.borderRadius = '15px';
      btn.style.color = 'white';
      btn.style.fontSize = '0.8rem';
      btn.style.cursor = 'pointer';
      btn.addEventListener('click', () => {
        chatbotInput.value = text;
        processInput();
      });
      container.appendChild(btn);
    });
    
    chatbotMessages.appendChild(container);
  }

  // Initial setup
  setTimeout(addQuickReplies, 1000);
});
</script>
</body>
</html>
