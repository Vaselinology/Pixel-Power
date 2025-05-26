<?php
session_start();
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $address = isset($_POST['address']) ? trim($_POST['address']) : null; // New address field

    // Validate passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM User WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("This email is already registered.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into User table
        $stmt = $conn->prepare("INSERT INTO User (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        $stmt->execute();
        $user_id = $stmt->insert_id;

        // Get the next available ID for Customer table
        $get_max_id = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM Customer");
        $next_id = $get_max_id->fetch_assoc()['next_id'];

        // Insert into Customer table with explicit ID and address
        $stmt_customer = $conn->prepare("INSERT INTO Customer (id, user_id, name, address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt_customer->bind_param("iiss", $next_id, $user_id, $name, $address);
        $stmt_customer->execute();

        // Commit transaction
        $conn->commit();

        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;

        // Redirect to profile
        header("Location: userprofile.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Registration failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account – Pixel Power</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

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

  /* Password Strength */
  .password-strength {
    display: flex;
    gap: 6px;
    margin-top: 10px;
  }

  .strength-bar {
    height: 4px;
    flex: 1;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    transition: all 0.3s ease;
  }

  .strength-bar.weak { background: #ff4757; }
  .strength-bar.medium { background: #ffa502; }
  .strength-bar.strong { background: #2ed573; }

  .strength-text {
    font-size: 0.8rem;
    margin-top: 5px;
    color: #a1a1c2;
  }

  .validation-message {
    font-size: 0.8rem;
    margin-top: 5px;
  }

  .match { color: #2ed573; }
  .no-match { color: #ff4757; }

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

  /* Divider */
  .divider {
    display: flex;
    align-items: center;
    margin: 1.5rem 0;
    color: #4a4a6a;
    font-size: 0.9rem;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin: 0 10px;
  }

  /* Social Buttons */
  .social-login {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .social-btn {
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .google-btn {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .google-btn:hover {
    background: rgba(255, 255, 255, 0.1);
  }

  .facebook-btn {
    background: #1877f2;
    color: white;
    border: none;
  }

  .facebook-btn:hover {
    background: #166fe5;
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
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h1>Create Your Account</h1>
      <p class="subtitle">Join us today. It’s your journey. You shape it.</p>

      <form action="signup.php" method="POST">
    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required />
    </div>
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required />
    </div>
    <!-- Add the new address field here -->
    <div class="form-group">
        <label for="address">Address (Optional)</label>
        <input type="text" id="address" name="address" />
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
        <div class="password-strength">
            <span class="strength-bar"></span>
            <span class="strength-bar"></span>
            <span class="strength-bar"></span>
            <span class="strength-text"></span>
        </div>
    </div>
    <div class="form-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" required />
        <span id="password-match" class="validation-message"></span>
    </div>
    <button type="submit" class="signup-btn">Sign Up</button>
</form>

      <div class="divider">or</div>
      <div class="social-login">
        <button class="social-btn google-btn"><i class="fab fa-google"></i>&nbsp;Sign up with Google</button>
        <button class="social-btn facebook-btn"><i class="fab fa-facebook-f"></i>&nbsp;Sign up with Facebook</button>
      </div>

      <div class="switch-form">
        Already have an account? <a href="signin.php">Sign in here</a>
      </div>
      <footer>© 2025 Pixel Power – All Rights Reserved</footer>
    </div>

    <div class="image-side">
      <img src="images/signup.jpg" alt="Signup Illustration" />
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>

