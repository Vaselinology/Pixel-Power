<?php
session_start();
include('db.php');  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid credentials, please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container two-column">
        <div class="image-side">
            <img src="images/signin-side.jpg" alt="Sign In Illustration">
        </div>
        <div class="form-container">
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to continue shopping</p>
            
            <?php if (!empty($error)): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form id="signin-form" action="signin.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="signin-btn">Sign In</button>
            </form>
            
            <div class="switch-form">
                <p>Don't have an account? <a href="signup.php">Create one</a></p>
            </div>
            
            <footer>
                <p>© 2025 ALL RIGHTS RESERVED</p>
            </footer>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
