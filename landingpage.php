<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pixel Power - Gaming Products</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
</head>
<body>
  <?php include 'includes/header.php'; ?>
  
  <div class="container1">
    <img src="images\Landingpage\container1.jpg" alt="Gaming banner image">
    <div class="content">
      <h1>PIXEL <br><span>POWER</span></h1>
      <p>Unleash Your Gaming Potentials</p>
      <p>
        Experience the ultimate gaming adventure with our exclusive <br>
        virtual currency and game passes. Elevate your gameplay to new <br>
        heights and unlock a world of exclusive in-game content and <br>benefits
      </p>
      <div class="btn">
        <a href="about.php"><button class="cta-button">Learn More</button></a>
      </div>
    </div>
  </div>

  <div class="container2">
    <div class="content2">
      <div class="text2">
        <p>Your ultimate destination for all things gaming! Based 
          in Tunisia, we specialize in providing digital game 
          passes, high-quality gaming gear, and in-game 
          currency for top titles on PlayStation, Xbox, PC, and 
          mobile. Whether you're a casual player or a 
          competitive gamer, we offer secure payments, instant 
          delivery, and trusted service to level up your 
          experience.
        </p>
      </div>
      <div class="image2">
        <img src="images\Landingpage\container2.jpg" alt="Gaming products">
      </div>
    </div>
  </div>

  <div class="container3">
    <div class="content2">
      <div class="title">
        Discover Our Offers
      </div>
  
      <div class="images">
        <div class="image-item">
          <img src="images\Landingpage\blue.jpg" alt="gaming image">
          <h3>Figurines</h3>
          <p>Discover a variety of character <br>
            figurines from your favorite <br>
             games, delivered to your <br>
              doorstep.</p>
        </div>
        <div class="image-item">
          <img src="images\Landingpage\purple.jpg" alt="gaming image">
          <h3>Gaming <br>
            Equipment</h3>
          <p>Discover a vast array of goods,<br>
             from keyboards to high-end <br>
              gaming PCs, all designed to <br>
               enhance your gaming <br>
               experience and give you an <br>
                edge over the competition.</p>
        </div>
        <div class="image-item">
          <img src="images\Landingpage\pink.jpg" alt="gaming image">
          <h3>Game Currency <br>and Passes</h3>
          <p>Elevate your gameplay and <br>
             access exclusive content with <br>
              our diverse range of game <br>
              currency and game passes.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="image3">
    <img src="images\Landingpage\container3.jpg" alt="Gaming products" class="background-image">
    <div class="carousel-overlay">
      <div class="carousel-container">
        <div class="carousel">
          <img src="images\Landingpage\rev1.jpg" alt="Review 1" class="carousel-slide active">
          <img src="images\Landingpage\rev2.jpg" alt="Review 2" class="carousel-slide">
          <img src="images\Landingpage\rev3.jpg" alt="Review 3" class="carousel-slide">
        </div>
        <button class="carousel-btn prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="carousel-btn next" onclick="moveSlide(1)">&#10095;</button>
      </div>
    </div>
  </div>

  <div class="container4">
    <div class="content3">
      <div class="text3">
        <h2>PLAY WITH THE BEST <br> EQUIPEMENT</h2>
        <h3>Discover Our Products</h3>
        <p>
          Experience the pinnacle of gaming with our extensive <br>
          selection of virtual currency and game passes. Unlock a <br>
          world of exclusive content, enhance your skills, and <br>
          dominate the competition with our cutting-edge digital <br>
          solutions.
        </p>
        <a href="shopping.php"><button class="cta-button">Learn More</button></a>
      </div>
      <div class="image4">
        <img src="images\Landingpage\container4 (2).jpg" alt="Gaming products">
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>

  <script>
    let currentIndex = 0;
    const slides = document.querySelectorAll(".carousel-slide");
    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove("active");
        if (i === index) {
          slide.classList.add("active");
        }
      });
    }
    function moveSlide(step) {
      currentIndex += step;
      if (currentIndex < 0) currentIndex = slides.length - 1;
      if (currentIndex >= slides.length) currentIndex = 0;
      showSlide(currentIndex);
    }
    setInterval(() => moveSlide(1), 5000);
  </script>
</body>
</html>


<style>
 /* Reset */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Manrope', sans-serif;
    background-color: #0e0e0e;
    color: white;
    line-height: 1.6;
  }

  /* Shared Button Style */
  .button {
    padding: 12px 24px;
    font-size: 16px;
    border: none;
    background-color: black;
    color: white;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .button:hover {
    background-color: #e6005c;
  }

  /* Hero Section */
  .container1 {
    position: relative;
    text-align: left;
  }

  .container1 img {
    width: 100%;
    height: auto;
    object-fit: cover;
    display: block;
  }

  .container1 .content {
    position: absolute;
    top: 30%;
    left: 10%;
    transform: translateY(-50%);
  }

  .container1 h1 {
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

  .container1 h1 span {
    color: #ffffff;
  }

  .container1 p {
    font-size: 17px;
    margin-top: 20px;
    max-width: 600px;
    color: #b0b0b0;
  }

  .container1 .btn button {
    margin-top: 30px;
    padding: 12px 24px;
    font-size: 16px;
    border: none;
    background-color:rgb(0, 0, 0);
    color: white;
    cursor: pointer;
    border-radius: 30px;
  }

  .container1 .btn button:hover {
    background-color: #e6005c;
  }

  /* Intro Section */
  .container2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 60px 10%;
    background-color: #F7556A;  
    flex-wrap: wrap;
  }
  .container2 .content2 {
    display: flex;
    gap: 50px;
    flex-wrap: wrap;
  }


  .text2 {
    flex: 1;
    font-size: 27px;
    margin-top: 20px;
    font-family: 'Manrope', medium;
  }

  .image2 img {
    max-width: 500px;
    width: 100%;
    border-radius: 50px;
    height: 100%;
    border-radius: 50px;
  }

  /* Offers Section */
  .container3 {
    background-color: #000;
    padding: 80px 10%;
    text-align: center;
  }

  .container3 .title {
    font-size: 36px;
    font-family: 'Anton', sans-serif;
    text-align: center;
    margin-bottom: 40px;
  }

  .images {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
  }

  .image-item {
    max-width: 300px;
    text-align: center;
  }

  .image-item img {
    width: 100%;
    height: auto;
    border-radius: 20px;
  }

  .image-item h3 {
    font-size: 24px;
    margin: 15px 0 10px;
  }

  .image-item p {
    font-size: 16px;
    color: #b0b0b0;
  }

  /* Image3 Section with Carousel */
  .image3 {
    position: relative;
    width: 100%;
  }

  .image3 .background-image {
    width: 100%;
    display: block;
  }

  .carousel-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80%;
    max-width: 1000px;
  }

  /* Carousel Styles */
  .carousel-container {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    max-width: 500px;
    margin-top: -600px;
    margin-left: -150px;
  }

  .carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
  }

  .carousel-slide {
    min-width: 100%;
    display: none;
    transition: opacity 0.5s ease;
  }

  .carousel-slide.active {
    display: block;
    opacity: 1;
  }

  .carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 30px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    cursor: pointer;
    padding: 15px;
    z-index: 10;
    border-radius: 50%;
    transition: all 0.3s ease;
  }

  .carousel-btn.prev {
    left: 20px;
  }

  .carousel-btn.next {
    right: 20px;
  }

  .carousel-btn:hover {
    background-color: #e6005c;
    transform: translateY(-50%) scale(1.1);
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

  /* Product Promo Section */
  .container4 {
    background-color: #ffffff;
    padding: 60px 10%;
  }

  .content3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  .text3 {
    flex: 1;
  }

  .text3 h2 {
    font-family: 'Anton', sans-serif;
    font-size: 50px;
    color: #ff0066;
  }

  .text3 h3 {
    font-size: 24px;
    margin-top: -10px;
    font-family: 'Manrope', sans-serif;
    color: #000;
  }

  .text3 p {
    font-size: 16px;
    margin: 20px 0;
    color: #b0b0b0;
  }

  .image4 {
    flex: 1;
    text-align: right;
  }

  .image4 img {
    max-width: 100%;
    border-radius: 10px;
  }

  .cta-button {
    padding: 12px 24px;
    background-color: black;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .cta-button:hover {
    background-color: #e6005c;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .container1 .content {
      top: 20%;
      left: 5%;
      font-size: 40px;
    }

    .images {
      flex-direction: column;
      align-items: center;
    }

    .container3 .title {
      margin-left: 0;
      font-size: 28px;
    }

    .content3 {
      flex-direction: column;
      text-align: center;
    }

    .image4 {
      text-align: center;
      margin-top: 20px;
    }
  }


</style>