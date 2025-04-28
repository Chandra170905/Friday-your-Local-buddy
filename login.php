<?php
session_start();
require_once "config.php"; // Make sure this connects to your database

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user"] = $email;
            $_SESSION["role"] = $row["role"]; 

            
            if ($row["role"] === "Vendor") {
                header("Location: vendor.php");
            } else {
                header("Location: index.html");
            }
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No user found with that email.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Login</title>
</head>
<style>
/* Page Background and Font */
body {
    margin: 0;
    padding: 0;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background:rgb(101, 200, 250);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

/* Main Container */
.main-container {
    display: flex;
    background-color: #050e2d;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 800px;
    width: 100%;
}

/* Image Section */
.image-section {
    flex: 1;
    background: url('mobile-login-concept-illustration_114360-83-Photoroom.png') no-repeat center center/cover;
}

/* Form Section */
.form-section {
    flex: 1;
    padding: 35px 45px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* Heading */
.form-section h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #fff;
}

/* Input Fields */
.input-field {
    margin-bottom: 20px;
}

.input-field label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
    color: #fff;
}

.input-field input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 15px;
    box-sizing: border-box;
}

/* Button */
button {
    width: 95%;
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

button:hover {
    background: #1aeeef;
    color: #050e2d;
    box-shadow: 0 0 10px #1aeeef;
}

/* Error Message */
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

/* Link to Register */
.login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #fff;
}

.login-link a {
    color:  #1aeeef;
    text-decoration: none;
    font-weight: 500;
}

.login-link a:hover {
    text-decoration: underline;
}

.arrow{
    font-size: 24px;
    color: #fff;
    text-decoration: none;
}

</style>
<body>
    <div class="main-container">
        <!-- Image Section -->
        <div class="image-section">
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <a href="index.html" class="arrow" id="home-arrow">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2>Login</h2>
            <?php if (!empty($message)) : ?>
                <p style="color:red;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-field">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-field">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit">Login</button>
                <p class="login-link">Don't have an account? <a href="register.php">Register here</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>
