<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['fn'] ?? '';
    $lastName = $_POST['ln'] ?? '';
    $fullName = $firstName . ' ' . $lastName;
    $email = $_POST['mail'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $company = $_POST['company'] ?? '';
    $content = $_POST['msg'] ?? '';

    // Get logged-in user's customer ID
    $customerId = $_SESSION['customer_id'] ?? NULL;

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "store");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO message (customer_id, name, email, type, subject, content) VALUES (?, ?, ?, 'partnership', ?, ?)");
    $type = 'partnership';
    $stmt->bind_param("isssss", $customerId, $fullName, $email, $type, $subject, $content);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your message!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Partnership</title>
</head>
<body class="contact-page">
<?php include 'includes/header.php'; ?>

<div class="form-container">
  <div class="form-content">
    <h2>Partner with us</h2>
    <form id="partner-form" method="post">
      <div class="input-row">
        <div>
          <label for="fn">First name</label>
          <input type="text" id="fn" name="fn" placeholder="Jane" required>
        </div>
        <div>
          <label for="ln">Last name</label>
          <input type="text" id="ln" name="ln" placeholder="Smitherton" required>
        </div>
      </div>

      <div class="input-row">
        <div>
          <label for="company">Company</label>
          <input type="text" id="company" name="company" placeholder="ihec carthage" required>
        </div>
        <div>
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="Partnership" required>
        </div>
      </div>

      <label for="mail">Email address</label>
      <input type="email" id="mail" name="mail" placeholder="email@domain.com" required>

      <label for="msg">Your message</label>
      <textarea id="msg" name="msg" placeholder="Enter your message" cols="60" rows="10" required></textarea>

      <button type="submit">Submit</button>
    </form>
  </div>
  <img src="images/contactpage/console.png" alt="Contact Illustration" class="partnership-img">
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

<style>
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
</style>