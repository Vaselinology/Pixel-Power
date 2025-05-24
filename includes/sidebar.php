<!-- sidebar.php -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Dashboard</h2>
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fas fa-angle-double-left" id="toggle-icon"></i>
        </button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span class="link-text">Dashboard</span></a></li>
        <li><a href="manage_products.php"><i class="fas fa-cogs"></i> <span class="link-text">Manage Products</span></a></li>
        <li><a href="manage_orders.php"><i class="fas fa-box"></i> <span class="link-text">Manage Orders</span></a></li>
        <li><a href="manage_users.php"><i class="fas fa-users"></i> <span class="link-text">Manage Users</span></a></li>
        <li><a href="manage_reviews.php"><i class="fas fa-star"></i> <span class="link-text">Manage Reviews</span></a></li>
        <li><a href="manage_admins.php"><i class="fas fa-address-card"></i> <span class="link-text">Manage Admins</span></a></li>
        <li><a href="manage_messages.php"><i class="fas fa-envelope"></i> <span class="link-text">View Messages</span></a></li>
        <li><a href="landingpage.php"><i class="fas fa-sign-out-alt"></i> <span class="link-text">Logout</span></a></li>
    </ul>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const icon = document.getElementById("toggle-icon");
    const main = document.getElementById("main-content");

    sidebar.classList.toggle("collapsed");
    if (main) {
        main.classList.toggle("collapsed");
    }

    icon.classList.toggle("fa-angle-double-left");
    icon.classList.toggle("fa-angle-double-right");
}
</script>

<style>
.sidebar {
    width: 250px;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #333;
    color: white;
    padding-top: 20px;
    transition: width 0.3s ease;
    overflow-x: hidden;
    z-index: 1000;
}

.sidebar.collapsed {
    width: 70px;
}

.sidebar-header {
    text-align: center;
    margin-bottom: 30px;
    position: relative;
}

.sidebar-header h2 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .sidebar-header h2 {
    opacity: 0;
}

.sidebar-toggle-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
}

.sidebar-menu {
    list-style-type: none;
    padding: 0;
}

.sidebar-menu li {
    padding: 15px 20px;
    border-bottom: 1px solid #444;
}

.sidebar-menu li a {
    color: white;
    text-decoration: none;
    font-size: 1rem;
    display: flex;
    align-items: center;
    transition: background-color 0.3s ease;
}

.sidebar-menu li a:hover {
    background-color: #575757;
    border-radius: 5px;
}

.sidebar-menu li a i {
    margin-right: 10px;
    font-size: 1.2rem;
}

.link-text {
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .link-text {
    opacity: 0;
    pointer-events: none;
}

.sidebar-menu li.active a {
    background-color: #1F8EF1;
}

/* Main content shifting */
.main-content {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.main-content.collapsed {
    margin-left: 70px;
}

/* Responsive sidebar */
@media (max-width: 768px) {
    .sidebar {
        width: 0;
    }

    .sidebar.active {
        width: 250px;
    }

    .main-content {
        margin-left: 0;
    }

    .main-content.collapsed {
        margin-left: 0;
    }
}
</style>
