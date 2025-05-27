<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int)$_POST['order_id'];
$user_id = (int)$_SESSION['user_id'];

// Verify order belongs to user and is cancellable
$verify_sql = "SELECT o.* FROM orders o
               JOIN customer c ON o.customer_id = c.id
               WHERE o.id = ? AND c.user_id = ? 
               AND o.status IN ('placed', 'confirmed')";
               
$stmt = $conn->prepare($verify_sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    $_SESSION['error'] = "Order cannot be cancelled or doesn't exist";
    header("Location: order_confirmation.php?order_id=".$order_id);
    exit();
}

// Begin transaction
$conn->begin_transaction();

try {
    // 1. Update order status
    $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    
    // 2. Restore product stock
    $items_sql = "SELECT product_id, quantity FROM orderitem WHERE order_id = ?";
    $stmt = $conn->prepare($items_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items = $stmt->get_result();
    
    while ($item = $items->fetch_assoc()) {
        $restore_sql = "UPDATE product SET stock = stock + ? WHERE id = ?";
        $stmt = $conn->prepare($restore_sql);
        $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt->execute();
    }
    
    // 3. Commit transaction
    $conn->commit();
    
    $_SESSION['success'] = "Order #".$order_id." has been cancelled successfully";
    
    // Send cancellation email (you would implement this)
    // send_order_cancellation_email($user_id, $order_id);
    
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to cancel order: ".$e->getMessage();
}

header("Location: order_confirmation.php?order_id=".$order_id);
exit();