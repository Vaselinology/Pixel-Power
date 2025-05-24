<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .site-footer {
        background-color: #0b0b0b;
        color: #e3b6c7;
        padding: 40px 0 20px;
        font-family: Arial, sans-serif;
    }

    .footer-container {
        display: flex;
        justify-content: space-around;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        flex-wrap: wrap;
    }

    .footer-section {
        flex: 1;
        min-width: 200px;
        margin-bottom: 20px;
        padding: 0 15px;
    }

    .footer-section h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #e3b6c7;
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 10px;
    }

    .footer-section a {
        color: #e3b6c7;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-section a:hover {
        color: #1abc9c; 
    }

    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #333;
        margin-top: 20px;
        font-size: 14px;
        color: #e3b6c7;
    }

    @media (max-width: 768px) {
        .footer-container {
        flex-direction: column;
        }

        .footer-section {
        margin-bottom: 30px;
        }
    }
</style>

</head>
<body>

  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="/">Home</a></li>
          <li><a href="/about">About</a></li>
          <li><a href="/products">Products</a></li>
          <li><a href="/contact">Contact</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3>Explore More</h3>
        <ul>
          <li><a href="/faq">FAQ</a></li>
          <li><a href="/support">Support</a></li>
          <li><a href="/blog">Blog</a></li>
          <li><a href="/partners">Partners</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3>Connect With Us</h3>
        <ul class="social-links">
          <li><a href="https://twitter.com" target="_blank">Twitter</a></li>
          <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
          <li><a href="https://facebook.com" target="_blank">Facebook</a></li>
          <li><a href="https://youtube.com" target="_blank">YouTube</a></li>
        </ul>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p>&copy; 2025 Pixel Power, Inc. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>