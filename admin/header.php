<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

// Set a default profile image or retrieve it from session/database
$profileImage = $isLoggedIn && isset($_SESSION['profile_image'])
    ? $_SESSION['profile_image']
    : '/assets/default-avatar.png'; // Use your own default path
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pixel Power</title>
  <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .site-header {
      background-color: #0e1726;
      padding: 15px 0;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo {
      font-family: 'Chewy', cursive;
      font-size: 24px;
      color: white;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 30px;
      margin: 0;
      padding: 0;
      align-items: center;
    }

    .nav-links a {
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color: #1abc9c;
    }

    .profile-icon {
      display: flex;
      align-items: center;
    }

    .profile-icon img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 15px;
      cursor: pointer;
      border: 2px solid #1abc9c;
      transition: transform 0.2s;
    }

    .profile-icon img:hover {
      transform: scale(1.1);
    }

    .profile-icon .fa-user {
      color: white;
      font-size: 20px;
      margin-left: 15px;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .profile-icon .fa-user:hover {
      color: #1abc9c;
    }
  </style>
</head>
<body>

<header class="site-header">
  <div class="container">
    <div class="logo">Pixel Power</div>
    <nav>
      <ul class="nav-links">
        <li><a href="landingpage.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="shopping.php">Products</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li class="profile-icon">
          <?php if ($isLoggedIn): ?>
            <a href="/edit_profile.php">
              <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile">
            </a>
          <?php else: ?>
            <a href="signup.php" title="Login / Sign up">
              <i class="fas fa-user"></i>
            </a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
  </div>
</header>

<main>
