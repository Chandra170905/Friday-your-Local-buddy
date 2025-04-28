<?php
require_once "config.php"; // Your database config file

$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Set a cookie for username
    setcookie("username", $username, time() + (86400 * 30), "/");

    $stmt = $conn->prepare("INSERT INTO contact_messages (username, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $phone, $message);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "Something went wrong. Please try again later.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: rgb(101, 200, 250);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    
    header {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        padding: 7px 80px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #1b2141;
        transition: 0.5s;
        z-index: 1000;
    }

    header.sticky {
        background: #1b2141;
        box-shadow: 0 5px 15px rgba(89, 67, 148, 0.9);
    }

    header .logo {
        position: relative;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 2em;
    }

    header .nav {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    header ul li {
        list-style: none;
        margin: 10px;
    }

    header ul li a {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
    }

    header ul li a:hover {
        background-color: #1aeeef;
        color: #050e2d;
        box-shadow: 0 0 10px #1aeeef;
    }

    header .action .searchbx {
        padding: 5px;
        margin: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #fff;
        border-radius: 5px;
    }

    header .action .searchbx i {
        font-size: 1.4em;
        margin: 5px;
        color: #222;
    }

    header .action .searchbx input {
        outline: none;
        border: none;
        font-size: 1em;
        color: #222;
    }

    .container {
      display: flex;
      background: white;
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(7, 3, 6, 0.899);
      overflow: hidden;
      width: 90%;
      max-width: 1000px;
    }

    .left-panel {
      background-color: #eaf8f7;
      padding: 40px;
      flex: 1;
    }

    .left-panel h2 {
      color: #050e2d;
      margin-bottom: 15px;
    }

    .left-panel p {
      color: #444;
      margin-bottom: 20px;
    }

    .info {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      color: #0d010a;
    }

    .info i {
      color: #108686;
      margin-right: 10px;
    }

    .social-icons {
      margin-top: 20px;
    }
    .social-icons {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
      }
      
      .social-icons::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #050e2d;
        z-index: 0;
      }
      
      .social-icons a {
        position: relative;
        z-index: 1;
        margin: 0 20px;
        color: white;
        background-color: #050e2d;
        padding: 10px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: 0.3s;
        border: 2px solid white;
      }
      
      .social-icons a:hover {
        background-color: #108686;
        border-color: #108686;
      }
      
    .right-panel {
      background-color: #050e2d;
      color: #fff;
      padding: 40px;
      flex: 1;
      position: relative;
    }

    .right-panel h3 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: none;
      outline: none;
      font-size: 14px;
      color: #333;
    }

    .form-group textarea {
      resize: vertical;
      border-radius: 20px;
      height: 100px;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
      color: #aaa;
    }

    .btn-submit {
      background-color: #050e2d;
    color: #fff;
    border: 2px solid #1aeeef;
    padding: 10px 30px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 25px;
    margin: 0 10px;

    }

    .btn-submit:hover {
      background: #1aeeef;
    color: #050e2d;
    box-shadow: 0 0 10px #1aeeef;

    }

    .success-message {
      margin-top: 10px;
      color: green;
      font-weight: bold;
    }

    .error-message {
      margin-top: 10px;
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
<header>
        <a href="#" class="logo">Friday</a>
        <ul class="nav">
            <li><a href="index.html">Home</a></li>
            <li><a href="product.php">Products</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact_us.php">Contact-us</a></li>
            <li><a href="register.php">Register/login</a></li>
        </ul>
        <div class="action">
            <div class="searchbx">
                <a href="#"><i class="bx bx-search"></i></a>
                <input type="text" placeholder="Search here...">
            </div>
        </div>
        <div class="togglemenu">

        </div>
    </header>
  <div class="container">
    <div class="left-panel">
      <h2>For any Query contact us: </h2>
      <br>
      <div class="info"><i class="fas fa-map-marker-alt"></i>Lovely Professional University</div>
      <div class="info"><i class="fas fa-envelope"></i>nibhavkaushik@gmail.com</div>
      <div class="info"><i class="fas fa-phone"></i>8283868115</div>
      <br><br><br><br><br><br><br><br><br><br><br>
      <div class="social-icons">
        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
        <a href="https://x.com/?lang=en"><i class="fab fa-twitter"></i></a>
        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
        <a href="https://in.linkedin.com"><i class="fab fa-linkedin-in"></i></a>
      </div>
      
    </div>

    <div class="right-panel">
      <h3>Contact Us</h3>
      <form method="POST" action="">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Your name" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Your email" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" placeholder="Your phone number">
        </div>

        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" placeholder="Your message" required></textarea>
        </div>

        <button type="submit" class="btn-submit">Send</button>
      </form>
    </div>
</div>

</body>
</html>