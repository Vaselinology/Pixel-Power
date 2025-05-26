<?php
include 'includes/db.php';
session_start();

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST["fn"] ?? '');
    $lastName = trim($_POST["ln"] ?? '');
    $email = trim($_POST["mail"] ?? '');
    $message = trim($_POST["msg"] ?? '');
    $fullName = $firstName . ' ' . $lastName;

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
        $error_message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        try {
            // Initialize customer_id as NULL
            $customerId = null;
            
            // Step 1: Check if email exists in user table
            $stmt = $conn->prepare("SELECT id FROM user WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $userId = $user['id'];
                
                // Step 2: Check if user has a customer record
                $stmt2 = $conn->prepare("SELECT id FROM customer WHERE user_id = ? LIMIT 1");
                $stmt2->bind_param("i", $userId);
                $stmt2->execute();
                $customerResult = $stmt2->get_result();
                
                if ($customerResult->num_rows > 0) {
                    $customer = $customerResult->fetch_assoc();
                    $customerId = $customer['id'];
                }
                $stmt2->close();
            }
            $stmt->close();

            // Insert the message
            $stmt = $conn->prepare("INSERT INTO message (customer_id, name, email, type, subject, content, date_sent) 
                                   VALUES (?, ?, ?, 'feedback', 'Contact Form', ?, NOW())");
            $stmt->bind_param("isss", $customerId, $fullName, $email, $message);

            if ($stmt->execute()) {
                $success_message = 'Your message has been sent successfully!';
                // Clear form
                $firstName = $lastName = $email = $message = '';
            } else {
                $error_message = 'Failed to send message. Error: ' . $conn->error;
            }
            $stmt->close();
        } catch (Exception $e) {
            $error_message = 'Database error. Please try again later.';
            error_log("Contact Form Error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* Your existing styles plus these additions */
         @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
* { 
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: sans-serif;
  background-color:#1D1F2A ;

}

.header1 {
  background-color: #0A1728;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 30px;
  height: 100px;
}

  .form-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
   
    padding: 20px;
    background-color: transparent;
   margin-left: 40px;
    margin-bottom: 200px;
    width: 1000px;
  }

  .form-content h2 {
    font-size: 50px;
  font-weight: 300;
  font-family: 'Arial', sans-serif;
  margin-top: 70px;
  color: rgb(255, 255, 255);
  text-transform: uppercase;
  letter-spacing: 6px;
  text-shadow: 
    -2px -2px 0 rgb(9, 3, 3), 
     2px -2px 0 rgb(13, 4, 4),  
    -2px  2px 0 rgb(6, 2, 2),  
     2px  2px 0 rgb(15, 7, 7),  
     5px 5px 0 rgb(255, 255, 255),
     5px 1px 0 rgb(255, 255, 255);
  }
  
  form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 70px;
  }
  
  input{
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-family: Arial, sans-serif;
    width: 300px;
    
  }
  textarea {
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-family: Arial, sans-serif;
    
  }
  label{
    color:#fff;
  }
  
  button {
    padding: 15px;
    background-color: #000000;
    color: white;
    border: none;
   
    font-size: 16px;
    cursor: pointer;
    border-radius: 6px;
   
    transition: background-color 0.3s ease;
  }
  
  button:hover {
    background-color: #0E1129;
  }
  
  .contact-img {
    width: 600px;
    height: 700px;
   
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(0,0,0,0.15);
    margin-top: 130px;
    margin-left: 250px;
  }
  .contact-page {
    background-image: url("images/contactpage/backgroundpic.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
  }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="contact-page">
    <?php include 'includes/header.php'; ?>
    
    <div class="form-container">
        <div class="form-content">
            <h2>CONTACT US</h2>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            
            <form method="POST" id="contactForm">
                <div class="form-group">
                    <label for="fn">First name</label>
                    <input type="text" id="fn" name="fn" value="<?= htmlspecialchars($firstName ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="ln">Last name</label>
                    <input type="text" id="ln" name="ln" value="<?= htmlspecialchars($lastName ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="mail">Email address</label>
                    <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="msg">Your message</label>
                    <textarea id="msg" name="msg" required><?= htmlspecialchars($message ?? '') ?></textarea>
                </div>
                
                <button type="submit">Submit</button>
            </form>
        </div>
        <img src="images/contactpage/console.jpg" alt="Contact Illustration" class="contact-img">
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Client-side validation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const email = document.getElementById('mail').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>