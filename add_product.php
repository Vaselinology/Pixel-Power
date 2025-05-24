<?php
include 'includes/db.php';
include 'includes/sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];

    $conn->query("INSERT INTO Product (name, description, price, category, stock) 
                  VALUES ('$name', '$desc', '$price', '$category', '$stock')");
    header("Location: manage_products.php");
    exit;
}
?>
<div class="main-content" id="main-content" style="margin-left: 260px; padding: 20px;">
    <a href="manage_products.php" class="go-back-btn"><i class="fas fa-arrow-left"></i> Go Back</a>
    <h2>Add Product</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Product Name" required><br>
        <textarea name="description" placeholder="Description"></textarea><br>
        <input type="number" name="price" step="0.01" placeholder="Price" required><br>
        <input type="text" name="category" placeholder="Category"><br>
        <input type="number" name="stock" placeholder="Stock"><br>
        <button type="submit">Add Product</button>
    </form>
</div>
<style> 
    .main-content {
        margin-left: 260px;
        padding: 20px;
        background-color: #fff;
        min-height: 100vh;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
    }

    form {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        max-width: 600px;
    }

    form label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    form input[type="text"],
    form input[type="number"],
    form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    form textarea {
        resize: vertical;
        height: 100px;
    }

    form button {
        padding: 10px 20px;
        background-color: #1F8EF1;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #0f7ae5;
    }
    
    .go-back-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 8px 14px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .go-back-btn i {
        margin-right: 6px;
    }

    .go-back-btn:hover {
        background-color: #5a6268;
    }

</style>