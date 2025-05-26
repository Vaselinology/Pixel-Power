<?php
session_start();
include 'includes/db.php';
include 'includes/sidebar.php';

// Fetch stats
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM user"))['count'];
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM product"))['count'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM `order`"))['count'];
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price * quantity) AS revenue FROM orderitem"))['revenue'] ?? 0;

// Orders for Chart
$orderStats = mysqli_query($conn, "
  SELECT DATE(order_date) AS date, COUNT(*) AS count 
  FROM `order` 
  GROUP BY DATE(order_date) 
  ORDER BY DATE(order_date) ASC
");

$dates = [];
$counts = [];
while ($row = mysqli_fetch_assoc($orderStats)) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}
?>

<div class="main-content" id="main-content">
  <h2>Dashboard Overview</h2>

  <div class="dashboard-cards">
    <div class="card">
      <h3>Total Users</h3>
      <p><?= $totalUsers ?></p>
    </div>
    <div class="card">
      <h3>Total Products</h3>
      <p><?= $totalProducts ?></p>
    </div>
    <div class="card">
      <h3>Total Orders</h3>
      <p><?= $totalOrders ?></p>
    </div>
    <div class="card">
      <h3>Total Revenue</h3>
      <p>$<?= number_format($totalRevenue, 2) ?></p>
    </div>
  </div>

  <div class="chart-container">
    <h3>Orders Over Time</h3>
    <canvas id="ordersChart"></canvas>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
const ordersChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
            label: 'Orders',
            data: <?= json_encode($counts) ?>,
            backgroundColor: '#1F8EF1',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { beginAtZero: true, title: { display: true, text: 'Orders' } }
        }
    }
});
</script>

<style>
  .main-content {
      padding: 20px;
  }

  .dashboard-cards {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      margin-bottom: 30px;
  }

  .card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 220px;
      padding: 20px;
      text-align: center;
      margin: 10px;
  }

  .card h3 {
      margin: 0 0 10px;
  }

  .card p {
      font-size: 22px;
      font-weight: bold;
      margin: 0;
  }

  .chart-container {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 900px;
      margin: auto;
  }

  canvas {
      width: 100% !important;
      height: 400px !important;
  }
</style>
