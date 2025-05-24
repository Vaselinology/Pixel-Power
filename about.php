<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>about</title>
    <link rel="stylesheet" href="styleabout.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>
      <div class="about1">
        <div class="text-content">
          <h1>ABOUT <br> PIXEL POWER</h1>
          <p>
            Welcome to Pixel Power, your trusted destination for Game Pass <br>
            subscriptions, premium gaming equipment, and in-game currency in <br>
            Tunisia. We’re passionate about gaming and committed to <br>
            providing fast, secure, and affordable digital products to enhance your <br>
            gaming experience. <br><br>
            With instant delivery, competitive prices, and 24/7 customer support, <br>
            we ensure a seamless and reliable service. Whether you're looking for <br>
            Xbox Game Pass, PS Plus, FIFA FC Points, or top-tier gaming gear, we’ve <br>
            got you covered.
          </p>
        </div>
        <img class="about-img" src="images/contactpage/image.png" alt="About Pixel Power">
      </div>
      
      <!-- TEAM SECTION -->
      <h1 class="team-title">OUR TEAM</h1>
      <div class="team-pictures">
        <img src="images/contactpage/yasmine.png" alt="Yasmine">
        <img src="images/contactpage/nour.png" alt="Nour">
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

.about1 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 60px;
    gap: 40px;
    background-color: #1D1F2A;
  }
  
  .about1 .text-content {
    flex: 1;
  }
  
  .about1 h1 {
    font-size: 60px;
    font-weight: 300;
    font-family: 'Arial', sans-serif;
    margin-top: 70px;
    margin-bottom: 60px;
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
  
  .about1 p {
    font-size: 16px;
    line-height: 1.8;
    color: #7E7C82;
    font-family: Arial, sans-serif;
  }
  
  .about-img {
    width: 600px;
    height: 500px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-top: 100px;
  }
  
  /* TEAM SECTION */
  .team-title {
    font-size: 60px;
    font-weight: 300;
    font-family: 'Arial', sans-serif;
    margin-top: 70px;
    margin-bottom: 60px;
    margin-left: 30px;
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
  
  .team-pictures {
    background-color: #17171b;
    display: flex;
    justify-content: center;
    gap: 150px;
    padding: 60px 0;
    margin-bottom: 60px;
    margin-left: 60px;
    margin-right: 60px;
  }
  
  .team-pictures img {
    width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 4px 20px rgba(255,255,255,0.2);
  }
</style>