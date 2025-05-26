<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

require_once 'includes/db.php';


// Get user info from database
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name, email FROM user WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: signin.php');
    exit();
}

// FIRST get the customer_id associated with this user
$customerId = null;
$stmt = $conn->prepare("SELECT id FROM customer WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$customerResult = $stmt->get_result();

if ($customerResult->num_rows > 0) {
    $customerRow = $customerResult->fetch_assoc();
    $customerId = $customerRow['id'];
}

// Debug output
error_log("User ID: $userId | Customer ID: " . ($customerId ?? 'NULL'));

// Get recent orders - only if we have a customer_id
$recentOrders = [];
if ($customerId !== null) {
    try {
        $orderStmt = $conn->prepare("
            SELECT o.id, 
                   DATE_FORMAT(o.order_date, '%Y-%m-%d') as date, 
                   o.total_amount as total, 
                   o.status 
            FROM orders o
            WHERE o.customer_id = ?
            ORDER BY o.order_date DESC 
            LIMIT 5
        ");
        
        $orderStmt->bind_param("i", $customerId);
        $orderStmt->execute();
        $orderResult = $orderStmt->get_result();
        $recentOrders = $orderResult->fetch_all(MYSQLI_ASSOC);
        
        error_log("Found " . count($recentOrders) . " orders for customer $customerId");
        
    } catch (Exception $e) {
        error_log("Order query error: " . $e->getMessage());
    }
} else {
    error_log("No customer record found for user $userId - cannot fetch orders");
}

// Set default values
$user['avatar'] = 'default-avatar.jpg';
$user['join_date'] = 'Member since ' . date('Y');
$user['game_stats'] = [
    'hours_played' => 87,
    'achievements' => 15,
    'favorite_game' => 'Cyberpunk 2077'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Pixel Power</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1F8EF1;
            --primary-dark: #0D6EFD;
            --accent: #FC00FF;
            --danger: #FF4757;
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

        .profile-wrapper {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 3rem;
            text-align: center;
        }

        .avatar-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            box-shadow: 0 0 20px rgba(31, 142, 241, 0.3);
        }

        .edit-avatar {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--primary);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-avatar:hover {
            transform: scale(1.1);
            background: var(--primary-dark);
        }

        .username {
            font-size: 2rem;
            margin: 0.5rem 0;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 800;
        }

        .member-since {
            color: var(--text-lighter);
            font-size: 0.9rem;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .profile-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--card-border);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            margin: 0;
            color: var(--text-light);
        }

        .edit-btn {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            color: var(--text-lighter);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-lighter);
            font-size: 0.9rem;
        }

        .orders-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .orders-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            color: var(--text-lighter);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .orders-table td {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
        }

        .order-id {
            color: var(--primary);
            font-weight: 600;
        }

        .order-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-completed {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

        .action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .logout-btn {
            background: var(--danger);
            color: white;
            margin-top: 2rem;
        }

        .logout-btn:hover {
            background: #ff6b81;
            transform: translateY(-2px);
        }

        .view-all {
            display: block;
            text-align: right;
            margin-top: 1rem;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .orders-table {
                display: block;
                overflow-x: auto;
            }
        }
       
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="profile-wrapper">
      <div class="profile-header">
    <div class="avatar-container">
        <?php
        $avatarDir = 'images/';
        $avatarFiles = ['avatar1.jpg', 'avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg'];
        
        // Filter to only existing files
        $availableAvatars = array_filter($avatarFiles, function($file) use ($avatarDir) {
            return file_exists($avatarDir . $file);
        });
        
        // Fallback if none found
        if (empty($availableAvatars)) {
            $availableAvatars = ['default-avatar.jpg'];
        }
        
        $selectedAvatar = $avatarDir . $availableAvatars[array_rand($availableAvatars)];
        ?>
        
        <img src="<?= $selectedAvatar ?>" alt="Profile Avatar" class="avatar"
             onerror="this.src='images/default-avatar.jpg'">
        
        <div class="edit-avatar" title="Change Avatar">
            <i class="fas fa-camera"></i>
        </div>
    </div>
    
    <h1 class="username"><?= htmlspecialchars($user['name']) ?></h1>
    <div class="member-since">Member since <?= $user['join_date'] ?></div>
</div>
        
        <div class="profile-grid">
            <div class="left-column">
                <div class="profile-card">
                    <div class="card-header">
                        <h2 class="card-title">Profile Info</h2>
                        <button class="edit-btn">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </button>
                    </div>
                    
                    <div class="info-group">
                        <span class="info-label">Full Name</span>
                        <div class="info-value"><?= htmlspecialchars($user['name']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <span class="info-label">Email Address</span>
                        <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                    
                    <div class="info-group">
                        <span class="info-label">Password</span>
                        <div class="info-value">••••••••</div>
                    </div>
                    
                    <a href="logout.php" class="action-btn logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                
                <div class="profile-card" style="margin-top: 2rem;">
                    <div class="card-header">
                        <h2 class="card-title">Gaming Stats</h2>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value"><?= $user['game_stats']['hours_played'] ?></div>
                            <div class="stat-label">Hours Played</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?= $user['game_stats']['achievements'] ?></div>
                            <div class="stat-label">Achievements</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">1</div>
                            <div class="stat-label">Current Game</div>
                        </div>
                    </div>
                    
                    <div class="info-group" style="margin-top: 1.5rem;">
                        <span class="info-label">Favorite Game</span>
                        <div class="info-value"><?= $user['game_stats']['favorite_game'] ?></div>
                    </div>
                </div>
            </div>
            
              <div class="right-column">
            <div id="orders-section"></div>
            <div class="profile-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Orders</h2>
                    <a href="orders.php" class="view-all">View All</a>
                </div>
                
                <?php if (!empty($recentOrders)): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td class="order-id">#<?= htmlspecialchars($order['id']) ?></td>
                                <td><?= date('M d, Y', strtotime($order['date'])) ?></td>
                                <td>$<?= number_format($order['total'], 2) ?></td>
                                <td>
                                    <span class="order-status status-<?= strtolower($order['status']) ?>">
                                        <?= htmlspecialchars($order['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="order_details.php?id=<?= $order['id'] ?>" class="edit-btn">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-orders-message">
                        <i class="fas fa-shopping-bag"></i>
                        <p>No orders found</p>
                        <?php if ($conn->error): ?>
                            <p class="error-text">(Database error: <?= htmlspecialchars($conn->error) ?>)</p>
                        <?php endif; ?>
                        <a href="products.php" class="browse-btn">
                            <i class="fas fa-store"></i> Browse Products
                        </a>
                    </div>
                <?php endif; ?>
            </div>
                
                         <div class="profile-card" style="margin-top: 2rem;">
                    <div class="card-header">
                        <h2 class="card-title">Wishlist</h2>
                        <a href="wishlist.php" class="view-all">View All</a>
                    </div>
                    
                    <div style="text-align: center; padding: 2rem 0; color: var(--text-lighter);">
                        <i class="fas fa-heart" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>Your wishlist is empty</p>
                        <a href="products.php" class="edit-btn" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i> Add Items
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script>
        // Simple animation for stat cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>