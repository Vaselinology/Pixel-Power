<?php
require_once 'includes/db.php';

// Map product IDs to new image filenames
$imageUpdates = [
    1 => 'images\products\Logitech G502 HERO Mouse.jpeg',
    2 => 'images\products\Razer BlackWidow V3 Keyboard.jpg',
    3 => 'images\products\SteelSeries Arctis 7 Headset.jpg',
    4 => 'images\products\Elgato Stream Deck Mini.jpg',
    5 => 'images\products\Xbox Wireless Controller.jpg',
    6 => 'images\products\Razer BlackWidow V3 Keyboard.jpg',
    7 => 'images\products\Logitech G502 HERO Mouse.jpeg',
    8 => 'images\products\SteelSeries Arctis 7 Headset.jpg',
    9 => 'images\products\asus tuf gaming monitor.jpg',
    10 => 'images\products\Corsair K70 RGB TKL.jpg',
    11 => 'images\products\HyperX Cloud II Headset.jpg',
    12 => 'images\products\Xbox Wireless Controller.jpg',
      13 => 'images\products\Elgato Stream Deck Mini.jpg',
    14 => 'images\products\Logitech G920 Racing Wheel.png',
    15 => 'images\products\Samsung 980 Pro 1TB NVMe SSD.jpg.png',
    16 => 'images\products\Master Chief Figurine.png',
    17 => 'images\products\Link ÔÇô Breath of the Wild Figurine.jpg',
    18 => 'images\products\Geralt of Rivia Figurine.jpg',
    19 => 'images\products\Kratos and Atreus Diorama.jpeg',
    20 => 'images\products\Pikachu PVC Statue.jpg',
    21 => 'images\products\Aloy Horizon Zero Dawn Figurine.png',
    22 => 'images\products\Lara Croft Tomb Raider Statue.jpg',
    23 => 'images\products\Doom Slayer Collectible Figure.jpg',
    24 => 'images\products\Ezio Auditore Figure.jpg',
    25 => 'images\products\Vault Boy Bobblehead.jpg',
    // Add all products here
];

try {
    $conn->begin_transaction();
    
    $stmt = $conn->prepare("UPDATE product SET image_url = ? WHERE id = ?");
    
    foreach ($imageUpdates as $productId => $filename) {
        $newPath = 'images/products/new/' . $filename;
        
        // Verify image exists first
        if (!file_exists($newPath)) {
            throw new Exception("Image not found: $newPath");
        }
        
        $stmt->bind_param("si", $newPath, $productId);
        $stmt->execute();
        
        // Move file to permanent location
        rename($newPath, 'images/products/' . $filename);
    }
    
    $conn->commit();
    echo "Successfully updated " . count($imageUpdates) . " product images";
    
} catch (Exception $e) {
    $conn->rollback();
    die("Error updating images: " . $e->getMessage());
}
?>