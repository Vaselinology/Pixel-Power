<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST["fn"]);
    $lastName = trim($_POST["ln"]);
    $email = trim($_POST["mail"]);
    $message = trim($_POST["msg"]);
    $fullName = $firstName . ' ' . $lastName;

    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO message (name, email, type, subject, content, date_sent) VALUES (?, ?, 'feedback', ?, ?, NOW())");
        $subject = "Contact Form Feedback";
        $stmt->bind_param("ssss", $fullName, $email, $subject, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Your message has been sent successfully.');</script>";
        } else {
            echo "<script>alert('Failed to send your message.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>
</head>
<body class="contact-page">
  <?php include 'includes/header.php'; ?>
      <div class="form-container">
        <div class="form-content">
          <h2>CONTACT US</h2>
          <form>
            <label for="fn">First name</label>
            <input type="text" id="fn" name="fn" placeholder="Jane" >
            <label for="ln">Last name</label>
            <input type="text" id="ln" name="ln" placeholder="Smitherton" >
            <label for="mail">Email address</label>
            <input type="email" id="mail" name="mail" placeholder="email@janesfakedomain.net">
            <label for="msg">Your message</label>
            <textarea id="msg" name="msg" placeholder="Enter your question or message" cols="60" rows="10"></textarea>
            <button type="submit">Submit</button>
          </form>
        </div>
        <img src="images\contactpage\console.png" alt="Contact Illustration" class="contact-img">
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
</style>