<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM Product";
if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
}
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html>
<head>
  <title>Products</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>
  <main>
  <div class="products-header">
    <h1 class="page-title">🕹️ Our Products</h1>
    <form method="GET" action="products.php" class="search-form">
      <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button type="submit">Search</button>
    </form>
  </div>
    <div class="product-grid">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="product-card">
            <img src="images/products/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p>$<?php echo number_format($row['price'], 2); ?></p>
            <a href="details.php?id=<?php echo $row['id']; ?>">View</a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products found.</p>
      <?php endif; ?>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>

</html>

<style>
  * { 
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .products-header {
    background-color: #0A1728;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    flex-wrap: wrap;
  }

  .page-title {
    font-size: 2.5rem;
    font-family: 'Orbitron', sans-serif;
    margin: 10px 0;
  }

  .search-form {
    display: flex;
    gap: 10px;
  }

  .search-form input[type="text"] {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    min-width: 200px;
  }

  .search-form button {
    padding: 10px 20px;
    background-color: #1F8EF1;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
  }

  .search-form button:hover {
    background-color: #0D6EFD;
  }

  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 40px;
  }

  .product-card {
    background: #fff;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    padding: 20px;
  }

  .product-card a {
    display: inline-block;
    background-color: #F06292; /* Pink color */
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none; /* Remove underline */
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .product-card a:hover {
    background-color: #000000; /* Black color on hover */
    color: #F06292; /* Pink text color on hover */
  }

  .product-card img {
    max-width: 100%;
    height: auto;
  }

</style>