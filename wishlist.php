<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Get wishlist items
$wishlistStmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image, p.description 
    FROM wishlist w
    JOIN Product p ON w.product_id = p.id
    WHERE w.user_id = ?
    ORDER BY w.added_at DESC
");
$wishlistStmt->bind_param("i", $userId);
$wishlistStmt->execute();
$wishlistResult = $wishlistStmt->get_result();
$wishlistItems = $wishlistResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Pixel Power</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Include all the CSS from userprofile.php */
        .wishlist-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .wishlist-header {
            margin-bottom: 2rem;
        }
        
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .wishlist-product {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
        }
        
        .wishlist-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .wishlist-product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .wishlist-product-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .wishlist-product-price {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .wishlist-product-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .wishlist-empty {
            text-align: center;
            padding: 4rem 0;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1>My Wishlist</h1>
        </div>
        
        <?php if (!empty($wishlistItems)): ?>
            <div class="wishlist-grid">
                <?php foreach ($wishlistItems as $item): ?>
                <div class="wishlist-product">
                    <img src="images/products/<?= htmlspecialchars($item['image']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>" 
                         class="wishlist-product-image">
                    <div class="wishlist-product-name"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="wishlist-product-price">$<?= number_format($item['price'], 2) ?></div>
                    <div class="wishlist-product-actions">
                        <a href="details.php?id=<?= $item['id'] ?>" class="edit-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="add_to_cart.php?id=<?= $item['id'] ?>" class="edit-btn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </a>
                        <a href="remove_from_wishlist.php?id=<?= $item['id'] ?>" class="edit-btn">
                            <i class="fas fa-trash"></i> Remove
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="wishlist-empty">
                <i class="fas fa-heart" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h2>Your wishlist is empty</h2>
                <p>Browse our products and add items to your wishlist</p>
                <a href="products.php" class="action-btn" style="margin-top: 1rem;">
                    <i class="fas fa-store"></i> Browse Products
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>