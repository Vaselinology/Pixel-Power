<?php
session_start();
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name']; // Store the user's name in session
        $_SESSION['is_admin'] = ($user['role'] === 'admin'); // If you have admin roles
        
        header('Location: landingpage.php');
        exit();
    } else {
        $error = "Invalid credentials, please try again.";
    }
}
?>

<!-- You can reuse the same styles from above or split them out into CSS -->
<!-- For brevity, I'll assume styles are shared or already imported -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In – Pixel Power</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link rel="stylesheet" href="css/style.css" />
</head>


<style>
  /* Base Styles */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body {
    background-color: #0f0f1a;
    color: #fff;
    height: 100vh;
    overflow: hidden;
  }

  .container {
    display: flex;
    height: 100vh;
  }

  /* Form Side */
  .form-container {
    flex: 1;
    padding: 5% 10%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    position: relative;
    z-index: 2;
  }

  .form-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('images/noise.png') repeat;
    opacity: 0.03;
    z-index: -1;
  }

  h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    background: linear-gradient(90deg, #00dbde 0%, #fc00ff 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 800;
  }

  .subtitle {
    color: #a1a1c2;
    margin-bottom: 2rem;
    font-size: 1.1rem;
  }

  /* Form Elements */
  .form-group {
    margin-bottom: 1.5rem;
    position: relative;
  }

  label {
    display: block;
    margin-bottom: 0.5rem;
    color: #d1d1e9;
    font-size: 0.9rem;
  }

  input {
    width: 100%;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
  }

  input:focus {
    outline: none;
    border-color: #7b2cbf;
    box-shadow: 0 0 0 3px rgba(123, 44, 191, 0.2);
  }

  /* Button */
  .signup-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(90deg, #7b2cbf 0%, #3a0ca3 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
    position: relative;
    overflow: hidden;
  }

  .signup-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(123, 44, 191, 0.3);
  }

  .signup-btn::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
      to bottom right,
      rgba(255, 255, 255, 0.3) 0%,
      rgba(255, 255, 255, 0) 60%
    );
    transform: rotate(30deg);
    transition: all 0.3s ease;
  }

  .signup-btn:hover::after {
    left: 100%;
  }

  /* Error Message */
  .error-msg {
    background: rgba(255, 0, 0, 0.1);
    color: #ff6b6b;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #ff6b6b;
  }

  /* Footer Links */
  .switch-form {
    margin-top: 2rem;
    text-align: center;
    color: #a1a1c2;
  }

  .switch-form a {
    color: #7b2cbf;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .switch-form a:hover {
    color: #9d4edd;
    text-decoration: underline;
  }

  footer {
    margin-top: 3rem;
    text-align: center;
    color: #4a4a6a;
    font-size: 0.8rem;
  }

  /* Image Side */
  .image-side {
    flex: 1;
    position: relative;
    overflow: hidden;
  }

  .image-side img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.8) contrast(1.1);
  }

  .image-side::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
      135deg,
      rgba(123, 44, 191, 0.3) 0%,
      rgba(58, 12, 163, 0.5) 100%
    );
    z-index: 1;
  }

  /* Glow Effects */
  .form-container::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: radial-gradient(
      circle,
      rgba(123, 44, 191, 0.2) 0%,
      rgba(123, 44, 191, 0) 70%
    );
    border-radius: 50%;
    z-index: -1;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .container {
      flex-direction: column;
    }

    .image-side {
      display: none;
    }

    .form-container {
      padding: 20% 10%;
    }
  }

  /* Animation */
  @keyframes float {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-10px);
    }
  }

  .image-side img {
    animation: float 6s ease-in-out infinite;
  }
</style>
<body>
  <div class="container">
    <div class="form-container">
      <h1>Welcome Back</h1>
      <p class="subtitle">Sign in to continue shopping</p>

      <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="signin.php" method="POST">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit" class="signup-btn">Sign In</button>
      </form>

      <div class="switch-form">
        Don’t have an account? <a href="signup.php">Create one</a>
      </div>

      <footer>© 2025 Pixel Power – All Rights Reserved</footer>
    </div>

    <div class="image-side">
      <img src="images/signin (1).jpg" alt="Sign In Illustration" />
    </div>
  </div>

  <!-- Optional script, if needed later -->
  <script src="script.js"></script>
</body>
</html>
