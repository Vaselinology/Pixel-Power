<?php
include 'includes/db.php';
session_start();

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['fn'] ?? '');
    $lastName = trim($_POST['ln'] ?? '');
    $email = trim($_POST['mail'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $content = trim($_POST['msg'] ?? '');
    $fullName = $firstName . ' ' . $lastName;

    // Validation
    $required = [$firstName, $lastName, $email, $subject, $company, $content];
    if (in_array('', $required, true)) {
        $error_message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } else {
        try {
            // Check if email exists in user table
            $customerId = null;
            $stmt = $conn->prepare("SELECT id FROM user WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userId = $row['id'];
                
                // Check if this user exists in customer table
                $stmt2 = $conn->prepare("SELECT user_id FROM customer WHERE user_id = ? LIMIT 1");
                $stmt2->bind_param("i", $userId);
                $stmt2->execute();
                $customerResult = $stmt2->get_result();
                
                if ($customerResult->num_rows > 0) {
                    $customerRow = $customerResult->fetch_assoc();
                    $customerId = $customerRow['user_id'];
                }
                $stmt2->close();
            }
            $stmt->close();

            // Insert message
            $stmt = $conn->prepare("INSERT INTO message 
                                  (customer_id, name, email, type, subject, content, date_sent) 
                                  VALUES (?, ?, ?, 'partnership', ?, ?, NOW())");
            $stmt->bind_param("issss", $customerId, $fullName, $email, $subject, $content);

            if ($stmt->execute()) {
                $success_message = 'Partnership request submitted successfully!';
                // Clear form
                $firstName = $lastName = $email = $subject = $company = $content = '';
            } else {
                $error_message = 'Submission failed. Please try again.';
            }
            $stmt->close();
        } catch (Exception $e) {
            $error_message = 'Database error. Please try later.';
            error_log("Partnership Form Error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner With Us</title>
    <style>
        /* Your existing styles plus alert styles from contact page */
         @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body.contact-page {
    font-family: sans-serif;
    background-image: url("images/contactpage/backgroundpic.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-color: #1D1F2A;
  }

  .form-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    margin: 0 auto 200px auto;
    max-width: 1200px;
  }

  .form-content h2 {
    font-size: 50px;
    font-weight: 300;
    font-family: 'Arial', sans-serif;
    margin-top: 70px;
    color: white;
    text-transform: uppercase;
    letter-spacing: 6px;
    text-shadow:
      -2px -2px 0 black,
      2px -2px 0 black,
      -2px  2px 0 black,
      2px  2px 0 black,
      5px 5px 0 white,
      5px 1px 0 white;
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 70px;
  }

  #partner-form .input-row {
    display: flex;
    gap: 20px;
    width: 600px;
  }

  #partner-form .input-row div {
    flex: 1;
  }

  input, textarea {
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-family: Arial, sans-serif;
    width: 100%;
  }

  textarea {
    resize: vertical;
  }

  label {
    color: #fff;
  }

  button {
    padding: 15px;
    background-color: #000;
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

  .partnership-img {
    width: 600px;
    height: 630px;
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    margin-top: 130px;
    margin-left: 50px;
  }
        .input-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .input-row > div {
            flex: 1;
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
            <h2>PARTNER WITH US</h2>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            
            <form method="POST" id="partnerForm">
                <div class="input-row">
                    <div class="form-group">
                        <label for="fn">First name</label>
                        <input type="text" id="fn" name="fn" value="<?= htmlspecialchars($firstName ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ln">Last name</label>
                        <input type="text" id="ln" name="ln" value="<?= htmlspecialchars($lastName ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="input-row">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="<?= htmlspecialchars($company ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="mail">Email address</label>
                    <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="msg">Your proposal</label>
                    <textarea id="msg" name="msg" required><?= htmlspecialchars($content ?? '') ?></textarea>
                </div>
                
                <button type="submit">Submit Request</button>
            </form>
        </div>
        <img src="images/contactpage/console.jpg" alt="Partnership Illustration" class="partnership-img">
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        document.getElementById('partnerForm').addEventListener('submit', function(e) {
            const email = document.getElementById('mail').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address');
                e.preventDefault();
                return;
            }
            
            // Additional validation if needed
            if (document.getElementById('company').value.trim().length < 2) {
                alert('Please enter a valid company name');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>