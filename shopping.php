<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<div class="background-wrapper">
    <header class="navbar">
      <div class="logo">
        <span>Pixel Power</span>
      </div>
      <nav class="nav-links">
        <a href="landingpage.php">Home</a>
        <a href="about.php">About</a>
        <a href="products.php">Products</a>
        <a href="contact.php">Contact</a>
      </nav>
    </header>

    <div class="hero">
      <img src="images\shopping page\header.jpg" alt="Gaming banner image">
      <div class="hero-text">
        <h1>SHOPPING <br><span>POWER</span></h1>
        <p>
          Experience the ultimate gaming adventure with our exclusive <br>
          virtual currency and game passes. Elevate your gameplay to new <br>
          heights and unlock a world of exclusive in-game content and <br>benefits
        </p>
    </div>
  </div>
  </div>
  <div class="white-bg-container">
    <div class="container5">
        <div class="image-container5">
            <img src="images\shopping page\bluemanette.jpg" alt="Blue gaming controller">
        </div>
        
        <div class="content5">
            <h2 class="minecraft-title">UNLOCK THE <br>POWER OF <br>VIRTUAL <br>CURRENCY</h2>
            <button class="purchase-btn">Purchase now</button>
        </div>
    </div>
    <section id="accessories" class="accessories-section">
      <h4 class="minecraft-title small-title">Gaming accessories</h4>
      <div class="accessories-grid">
        <div class="accessory-card">
          <img src="images\shopping page\stylich headphones.jpg" alt="Gaming Headset">
          <p>Stylish <br>Headphones</p>
        </div>
        <div class="accessory-card">
       <a href="#"> <img src="images\shopping page\gaming laptops.jpg" alt="Gaming Laptops"></a>
        <p>Gaming Laptops</p>
      </div>
  
      <div class="accessory-card">
       <a href="#"> <img src="images\shopping page\versatile power cords.jpg" alt="Power Cords"></a>
        <p>Versatile Power Cords</p>
      </div>
  
      <div class="accessory-card">
       <a href="#"> <img src="images\shopping page\consoles.jpg" alt="Consoles"></a>
        <p>Consoles</p>
      </div>
  
      <div class="accessory-card">
        <a href=""><img src="images\shopping page\ergonomic controls.jpg" alt="Controllers"></a>
        <p>Ergonomic Controllers</p>
      </div>
  
      <div class="accessory-card">
        <a href=""><img src="images\shopping page\keyboards.jpg" alt="Keyboards"></a>
        <p>Keyboards</p>
      </div>  
    </section>


    <div class="gaming-section">
      <h2 class="section-title minecraft-title">GAMING EQUIPMENT</h2>

      <!-- First Block -->
      <div class="equipment-row">
       <a href="products.php"> <img src="images\shopping page\eq1.jpg" alt="Laptop" class="equipment-image"></a>

        <div class="equipment-info">
          <h4 class="minecraft-title small-title">Powerful Rigs for Uncompromised Performance</h4>
          <p>
            Unleash your gaming potential with our cutting-edge <br>
            gaming laptops. Designed for maximum <br>
            performance, these powerhouses deliver exceptional <br>
            graphics, lightning-fast processing, and unrivaled <br>
            immersion
          </p>
        <a href="Products.php"> <button class="minecraft-button">Shop Laptops</button></a>
        </div>

        <a href="products.php"><img src="images\shopping page\eq2.jpg" alt="Tablet" class="equipment-image"></a>
      </div>

      <!-- Second Block -->
      <div class="equipment-row second">
        <div class="equipment-info">
          <h4 class="minecraft-title small-title">Tablet Essentials</h4>
          <p>
            Elevate your mobile gaming with our <br>
            collection of top-tier tablets. Enjoy <br>
            seamless performance, stunning visuals, <br>
            and unparalleled convenience on the go
          </p>
        <a href="Products.php"> <button class="minecraft-button">Discover Tablets</button></a>
        </div>

       <a href="products.php"> <img src="images\shopping page\eq3.jpg" alt="Accessories" class="equipment-image"></a>

        <div class="equipment-info">
          <h4 class="minecraft-title small-title">Accessories</h4>
          <p>
            Enhance your gaming experience with <br>
            our diverse range of high-quality accessories
          </p>
        <a href="Products.php">  <button class="minecraft-button">Explore Accessories</button></a>
        </div>
      </div>
    </div>

    <div class="carousel-container">
        <div class="carousel">
        <img src="images\shopping page\fortnite.jpg" alt="Fortnite">
        <img src="images\shopping page\fortnite2.jpg" alt="Fortnite 2">
        <img src="images\shopping page\roblox.jpg" alt="Roblox">
        <img src="images\shopping page\valorant.jpg" alt="Valorant">
        <img src="images\shopping page\pc games.jpg" alt="PC Games">
        <img src="images\shopping page\steam.jpg" alt="Steam">
        <img src="images\shopping page\xbox game pass ultimate.jpg" alt="Xbox Game Pass">
        <img src="images\shopping page\fortnite creaw yellow.jpg" alt="Fortnite Yellow">
        </div>
        <button class="carousel-btn prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="carousel-btn next" onclick="moveSlide(1)">&#10095;</button>
      </div>
    </div>

    <!-- 🔽 JavaScript goes here -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.querySelector('.pc-xbox-ps');
        const nextBtn = document.querySelector('.next-btn');
        const prevBtn = document.querySelector('.prev-btn');

        const scrollAmount = 320;

        nextBtn.addEventListener('click', () => {
          carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
          });
        });

        prevBtn.addEventListener('click', () => {
          carousel.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
          });
        });
      });
    </script>

    <div class="contact-partner">
    <h2>CUSTOMER SUPPORT</h2> 
    <p>At our gaming hub, we are committed to delivering exceptional customer service. Whether <br>
      you need assistance with your purchases, have inquiries about our products, or require <br>
        troubleshooting support, our dedicated team is here to ensure your satisfaction</p>
      </div>

    <div class="info-row">
      <!-- Card 1 -->
      <div class="info-box offset-up">
        <div class="left-section">
          <img class="top-img" src="images\shopping page\golden.jpg" alt="Golden">
          <div class="text-content">
            <h4 class="minecraft-title">CONTACT US</h4>
            <p>Do not hesitate to inquire or <br>
              report your problems! Our team <br>
              is here to insure the best <br>
              experience
            </p>
            <a href="contact.php"><button>Contact us</button></a>
          </div>
        </div>
        <img class="side-img" src="images\shopping page\valise.jpg" alt="Valise">
      </div>

      <!-- Card 2 -->
      <div class="info-box offset-down">
        <div class="left-section">
          <img class="top-img" src="images\shopping page\blue.jpg" alt="Blue">
          <div class="text-content">
            <h4 class="minecraft-title">PARTNER WITH US</h4>
            <p>Join forces with us and become a <br>
              valued partner. Together, we can <br>
              expand our reach, drive <br>
              innovation, and provide <br>
              exceptional gaming experiences <br>
              to our customers
            </p>
          <a href="partnership.php"><button>Explore Partnership</button></a>
          </div>
        </div>
        <img class="side-img" src="images\shopping page\manette.jpg" alt="Manette">
      </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.querySelector('.carousel');
        const nextBtn = document.querySelector('.next');
        const prevBtn = document.querySelector('.prev');

        const scrollAmount = 300;

        nextBtn.addEventListener('click', () => {
          carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
          });
        });

        prevBtn.addEventListener('click', () => {
          carousel.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
          });
        });
      });
    </script>

</body>
</html>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
  * { 
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body, html {
  height: 100%;
  font-family: 'Roboto', sans-serif;
  }

  .background-wrapper {
    position: relative;
    width: 100%;
    height: 100vh; /* Full screen height */
    overflow: hidden;
  }

  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 50px;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 2;
    background: rgba(0, 0, 0, 0); /* slight transparent bg for readability */
    color: white;
  }

  .logo span {
    font-family: 'Orbitron', sans-serif;
    font-size: 28px;
    font-weight: bold;
    color: #000;
  }

  .nav-links a {
    color: #000;
    margin: 0 15px;
    text-decoration: none;
    font-weight: 500;
  }

  .hero {
    position: relative;
    height: 100%;
    width: 100%;
  }

  .hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
  }

  .hero-text {
    position: absolute;
    top: 50%;
    left: 25%;
    transform: translate(-50%, -50%);
    color: white;
    z-index: 1;
    text-align: center;
    font-family: 'Orbitron', sans-serif;
  }

  .hero-text h1 {
    font-family: 'Orbitron', sans-serif;
    font-size: 70px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color:rgb(255, 255, 255);
    text-shadow:
      /* Outline */
      -5px -5px 0 #000,
      2px -2px 0 #000,
      -2px  2px 0 #000,
      2px  2px 0 #000,

      /* Colored layer */
      2px 2px 0 #000,
      4px 4px 0 #ff0055,
      6px 6px 0 #000,

      /* Glow behind */
      0 0 10px rgba(0,0,0,0.3);
  }

  .hero-text h1 span {
    color: #ffffff;
  }

  .hero-text p {
    font-size: 17px;
    margin-top: 20px;
    max-width: 600px;
    color:rgb(88, 85, 85);
    font-family: 'Monrope', medium;
  }

/*2nd container*/
  .btn button {
    margin-top: 30px;
    margin-left: 5px;
    padding: 25px 50px;
    font-size: 16px;
    background-color: #ff3366;
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .btn button:hover {
    background-color: #ff6699;
  }

  @media (max-width: 768px) {
    .container1 h1 {
      font-size: 50px;
    }

    .container1 .content {
      left: 5%;
      top: 15%;
      max-width: 90%;
    }
  }

  .container2 {
    background-color: #F94E6A;
    color: white;
    padding: 60px 30px;
  }

  .content2 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
    flex-wrap: wrap; /* makes it responsive */
  }

  .text2 {
    flex: 1;
    font-size: 23px;
    line-height: 1.5;
  }

  .image2 {
    flex: 1;
    text-align: right;
  }

  .image2 img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
  }

  .container3 {
    background-color: #000; /* Black background */
    padding: 80px 30px;
  }

  .content2 {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
  }

  .title {
    font-size: 50px;
    color: white;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 20px;
    margin-left: 350px; /* Adjusted margin for space between title and images */
  }

  .images {
    display: flex;
    justify-content: center;
    gap: 90px;
    margin-top: 20px;
    margin-left: 150px;
  }

  .image-item {
    text-align: center;
    
  }

  .images img {
    width: 230px;
    height: 230px;
    object-fit: cover;
    border-radius: 10px;
  }

  .image-item h3 {
    margin-top: 15px;
    font-size: 18px;
    font-family: Poor Story;
    color: white;
    line-height: 1.4;
  }
  .image-item p {
    margin-top: 40px;
    font-size: 18px;
    color: #B1B0AE;
    line-height: 1.7;
    align-items: center;
    display: flex;
  }
  .image3 {
    width: 100%;
    height: auto;
    overflow: hidden;
  }

  .image3 img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
  }

  .container4 {
    background-color: white;
    padding: 80px 30px;
  }

  .content3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    flex-wrap: wrap;
  }

  .text3 {
    flex: 1;
    color: #000;
  }

  .text3 h2 {
    font-size: 36px;
    margin-bottom: 10px;
    font-family: 'Bebas Neue', sans-serif;
  }

  .text3 h2 {
    font-size: 24px;
    margin-bottom: 20px;
    letter-spacing: 2px;
    font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    color: white;
  font-weight: 50px;
    text-shadow: 
      3px 3px 0 #e40273, 
      3px 3px 0 #ff69b4, 
      3px  3px 0 #ff69b4, 
      3px  3px 0 #ff69b4,
      0px  0px 10px #cb237c; /* glow intense */
  }

    .text3 h3 {
      font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; 
      font-weight: 10; /* Doit être un nombre comme 400, 500, 700 (pas px) */
      color: #000; /* Noir */
    }
    



  .text3 p {
    font-size: 18px;
    line-height: 1.6;
    color: #9E9FA4;
    margin-bottom: 50px;
    margin-top: 40px;
  }

  .cta-button {
    padding: 15px 40px;
    background-color: black;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .cta-button:hover {
    background-color: #333;
  }

  .image4 {
    flex: 1;
    text-align: right;
  }

  .image4 img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
  }

  .product-header {
    position: relative;
    height: 550px;
    background-color: #000; /* fallback */
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    color: white;
    overflow: hidden;
  }

  .product-header .header-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
  }

  .product-header .header-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.4; /* dim l'image pour laisser le texte visible */
  }

  .product-header .logo,
  .product-header .nav-links {
    z-index: 1;
    position: relative;
  }

  .product-header .logo p {
    font-size: 26px;
    font-family: 'Bebas Neue', sans-serif;
    color: white;
    margin-bottom: 500px;
  }

  .product-header .nav-links {
    list-style: none;
    display: flex;
    margin-bottom: 500px;
    gap: 20px;
  }

  .product-header .nav-links li a {
    text-decoration: none;
    color: white;
    font-size: 16px;
    transition: color 0.3s ease;
  }

  .product-header .nav-links li a:hover {
    color: #ff3366;
  }

  .white-bg-container {
    background-color:#FDFDFE;          
    width: 100%;
  }
/* Adjusted container5 styles for white background */
  .container5 {
    display: flex;
    max-width: 1200px;
    align-items: center;
    gap: 50px;
    padding: 20px;
    margin: 0 auto;
    margin-top: 100px;
  }

  .image-container5 {
    flex: 1;
  }

  .image-container5 img {
    height: 600px;
    width: 600px;
    border-radius: 10px;             
  }

  .content5 {
    flex: 1;
    text-align: center;
  }

  .minecraft-title {              
    font-family: 'Press Start 2P', cursive;
    color: #0E1129;
    font-size: 40px;
    letter-spacing: 12px;
    font-weight: bolder;
    padding: 10px;
    line-height: 1.6;
    display: inline-block;    
  }

  .purchase-btn {
    background-color: #000;
    color: #fff;
    border: 2px solid #000;
    padding: 15px 40px;
    font-size: 1.1rem;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-weight: bold;
    margin-top: 20px;
  }

  .purchase-btn:hover {
    background-color: #fff;
    color: #000;
  }

        

  @media (max-width: 768px) {
    .white-bg-container {
      padding: 40px 0;
    }
                
    .container5 {
      flex-direction: column;
      padding: 20px;
    }
                
    .minecraft-title {
      font-size: 2.5rem;
    }
  }

  .small-title {
    font-size: 20px;
    letter-spacing: 4px;
    margin-top: 90px;
    margin-bottom: 60px;
    text-align: center;
    margin-left: 160px;
  }
          
  .accessories-section {
    padding: 60px 20px;
    background-color: #FDFDFE;
    text-align: center;
  }

  .accessories-section h2 {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: #222;
  }

  .accessories-grid {
    display: grid;
    margin-top: 20px;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    justify-items: center;
  }


  .accessory-card {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transition: transform 0.3s ease;
    width: 100%;
    max-width: 220px;
    height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center; 
    text-align: center;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    transition: transform 0.2s ease;
  }

  .accessory-card:hover {
    transform: translateY(-5px);
  }

  .accessory-card img {
    width: 100%;
    border-radius: 12px;
    margin-bottom: 15px;
    height: auto;
    object-fit: cover;
  }

  .accessory-card h3 {
    font-family: 'Anton', sans-serif;
    font-size: 1.2rem;
    margin: 10px 0 5px;
    color: #333;
  }

  .accessory-card p {
    font-size: 1rem;
    color: #666;
  }

  
  .minecraft-title {
    font-family: 'Press Start 2P', cursive;
    color: #000000;
    font-weight: bolder;
    margin-left: 500px;
  }
          
  .section-title {
    font-size: 30px;
    text-align: center;
    margin-bottom: 40px;
    letter-spacing: 4px;
  }
          
  .small-title {
    font-size: 25px;
    font: weight 4px;
    letter-spacing: 3px;
    margin-bottom: 15px;
    margin-left: 30px;
    font-family:Signika;
  }
          
  .gaming-section {
    background-color: white;
    padding: 40px 20px;
  }
          
  .equipment-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 100px;
    margin-bottom: 60px;
    flex-wrap: wrap;
  }
          
  .equipment-info {
    max-width: 400px;
    text-align: center;
  }
          
  .equipment-info p {
    font-family: 'Monrope', medium;
    font-size: 15px;
    color: #333;
    margin-bottom: 10px;
    margin-left: 30px;
  }
          
  .equipment-image {
    width: 400px;
    height: 400px;
  }
          
  .minecraft-button {
    border-radius: 20px;
    background-color: #0E1129;
    color: white;
    border: none;
    padding: 15px 25px;
    font-size: 10px;
    cursor: pointer;
    margin-top: 10px;
    text-transform: uppercase;
  }
          
  .minecraft-button:hover {
    background-color: #000000;
  }

  .carousel-container {
  position: relative;
  max-width: 1200px;
  margin: 60px auto;
  overflow: hidden;
  border-radius: 15px;
}

.carousel {
  display: flex;
  overflow-x: auto;
  scroll-behavior: smooth;
  gap: 10px;
}

.carousel img {
  flex: 0 0 auto;
  width: 500px;
  height: auto;
  border-radius: 10px;
}


.carousel-slide {
  min-width: 100%;
  height: auto;
  display: none;
}

.carousel-slide.active {
  display: block;
}

.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 30px;
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  cursor: pointer;
  padding: 12px;
  z-index: 10;
  border-radius: 50%;
}

.carousel-btn.prev {
  left: 10px;
}

.carousel-btn.next {
  right: 10px;
}

.carousel-btn:hover {
  background-color: #e6005c;
}

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .carousel-overlay {
      width: 90%;
    }
    
    .carousel-btn {
      padding: 10px;
      font-size: 24px;
    }
  }
          
  .info-row {
    display: flex;
    justify-content: center;
    gap: 60px;
    margin: 100px auto;
    flex-wrap: wrap;
  }
          
  .info-box {
    display: flex;
    align-items: center;
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
    border-radius: 20px;
    max-width: 700px;
    flex-shrink: 0;
    padding: 30px;
  }
          
  .offset-up {
    background-color: #FEE7CD;
    transform: translateY(-30px);
  }
          
  .offset-down {
    background-color: #DAEBFD;
    transform: translateY(30px);
  }
          
  .left-section {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    gap: 20px;
    flex: 1;
  }
          
  .top-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 10px;
  }
          
  .text-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
          
  .minecraft-title {
    font-family: 'Press Start 2P', cursive;
    color: #0E1129;
    font-size: 20px;
    margin: 0;
  }
          
  .text-content p {
    font-family: Arial, sans-serif;
    font-size: 13px;
    margin:0;
    color: #827468;
    margin-bottom: 40px;
  }
          
  .text-content button {
    background-color: #0E1129;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    width: 160px;
    padding: 15px;
  }
          
  .side-img {
    width: 250px;
    height: 250px;
    object-fit: contain;
    margin-left: 40px;
    border-radius: 10px;
  }
          
  .contact-partner h2 {
    font-family: 'Press Start 2P', cursive;
    font-size: 40px;
    margin-left: 27%;
    margin-bottom: 20px;
    color: #212B43;
    letter-spacing: 10px;
  }
          
  .contact-partner p {
    margin-left: 30%;
    color: #7E7C82;
    line-height: 1.7;
    font-size: 18px;
  }
          
</style>