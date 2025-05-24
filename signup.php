<?php
session_start();
include('db.php');  // This sets up $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if the email already exists
    $check = $conn->prepare("SELECT id FROM User WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("This email is already registered.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user
    $stmt = $conn->prepare("INSERT INTO User (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error creating account. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container two-column">
        <div class="image-side">
            <img src="images/signup-side.jpg" alt="Signup Illustration">
        </div>
        <div class="form-container">
            <h1>Create Your Account</h1>
            <p class="subtitle">Join us today. It's your journey. You shape it.</p>
            <p class="subtitle">Sign up to start shopping.</p>
            
            <form id="signup-form" action="signup.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
                    <div class="password-strength">
                        <span class="strength-bar"></span>
                        <span class="strength-bar"></span>
                        <span class="strength-bar"></span>
                        <span class="strength-text"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                    <span id="password-match" class="validation-message"></span>
                </div>
                
                <button type="submit" class="signup-btn">Sign Up</button>
            </form>
            
            <div class="divider">
                <span>Or</span>
            </div>
            
            <div class="social-login">
                <button class="social-btn google-btn">
                    <i class="fab fa-google"></i> Sign up with Google
                </button>
                <button class="social-btn facebook-btn">
                    <i class="fab fa-facebook-f"></i> Sign up with Facebook
                </button>
            </div>
            
            <div class="switch-form">
                <p>Already have an account? <a href="signin.php">Sign in</a></p>
            </div>
            
            <footer>
                <p>© 2025 ALL RIGHTS RESERVED</p>
            </footer>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
