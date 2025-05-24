<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Retrieve product details from the database
$sql = "SELECT * FROM Product WHERE id = $product_id";
$result = $conn->query($sql);

$product = null;
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}

// Get related products from the same category
$category = $product['category'];
$related_sql = "SELECT * FROM Product WHERE category = '$category' AND id != $product_id LIMIT 6";
$related_result = $conn->query($related_sql);

// Get reviews for this product
$review_sql = "SELECT r.rating, r.comment, r.date_posted, u.name AS customer_name
               FROM Review r
               LEFT JOIN Customer c ON r.customer_id = c.id  
               LEFT JOIN User u ON c.user_id = u.id  
               WHERE r.product_id = $product_id
               ORDER BY r.date_posted DESC";
$review_result = $conn->query($review_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Detail - <?php echo htmlspecialchars($product['name']); ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main>
    <!-- Product Details -->
    <div class="product-detail">
      <div class="product-image">
        <img src="images/products/<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
      </div>
      <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
        <p class="product-stock">Stock: <?php echo $product['stock']; ?> available</p>
        <a href="cart.php?add=<?php echo $product['id']; ?>" class="add-to-cart-btn">Add to Cart</a>
      </div>
    </div>

    <!-- Related Products -->
    <div class="related-products">
      <h2>Related Products</h2>
      <div class="product-grid">
        <?php if ($related_result && $related_result->num_rows > 0): ?>
          <?php while ($related = $related_result->fetch_assoc()): ?>
            <div class="product-card">
              <img src="images/products/<?php echo $related['image_url']; ?>" alt="<?php echo htmlspecialchars($related['name']); ?>">
              <h3><?php echo htmlspecialchars($related['name']); ?></h3>
              <p>$<?php echo number_format($related['price'], 2); ?></p>
              <a href="product.php?id=<?php echo $related['id']; ?>" class="view-button">View</a>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No related products found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="product-reviews">
      <h2>Customer Reviews</h2>
      <?php if ($review_result && $review_result->num_rows > 0): ?>
        <?php while ($review = $review_result->fetch_assoc()): ?>
          <div class="review">
            <p><strong><?php echo htmlspecialchars($review['customer_name'] ?? 'Anonymous'); ?></strong> (<?php echo $review['date_posted']; ?>)</p>
            <p>Rating: <?php echo $review['rating']; ?>/5</p>
            <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No reviews yet. Be the first to review this product!</p>
      <?php endif; ?>
    </div>
      
    <!-- Review Form (for logged-in users) -->
    <?php if (isset($_SESSION['customer_id'])): ?>
      <form action="submit_review.php" method="POST" class="review-form">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <textarea name="comment" placeholder="Write your review..." required></textarea>
        <label for="rating">Rating:</label>
        <select name="rating" required>
          <option value="">--Choose--</option>
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
        <button type="submit">Submit Review</button>
      </form>
    <?php else: ?>
      <p><a href="signin.php">Log in</a> to leave a review.</p>
    <?php endif; ?>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

<style>
  .product-detail {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px;
    gap: 40px;
  }

  .product-image img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .product-info {
    max-width: 500px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .product-title {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #333;
  }

  .product-description {
    font-size: 1rem;
    margin-bottom: 20px;
    color: #555;
  }

  .product-price {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 10px;
    color: #1F8EF1;
  }

  .product-stock {
    margin-bottom: 20px;
    color: #666;
  }

  .add-to-cart-btn {
    display: inline-block;
    padding: 12px 25px;
    background-color: #1F8EF1;
    color: white;
    text-align: center;
    font-size: 1rem;
    border-radius: 5px;
    text-decoration: none;
  }

  .add-to-cart-btn:hover {
    background-color: #0D6EFD;
  }

  .related-products {
    padding: 40px;
    background: #f0f0f0;
    text-align: center;
  }

  .related-products h2 {
    font-size: 2rem;
    margin-bottom: 20px;
  }

  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }

  .product-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .product-card img {
    max-width: 100%;
    height: auto;
  }

  .view-button {
    display: inline-block;
    background-color: #F06292;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none;
    margin-top: 10px;
  }

  .view-button:hover {
    background-color: #000000;
  }

  .product-reviews {
    padding: 40px;
    background: #fafafa;
  }

  .review {
    margin-bottom: 20px;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

