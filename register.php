<?php
// Include DB config
require_once "config.php";

$message = ""; // Message for email already registered

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $fullname = clean($_POST["fullname"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $username = clean($_POST["username"]);
    $password_raw = $_POST["password"];
    $role = clean($_POST["role"]);
    $shop_type = ($role === "Vendor") ? clean($_POST["shop_type"]) : NULL;

    // Validations
    if (strlen($fullname) > 16) {
        $message = "Full name must be 16 characters or less.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (strlen($password_raw) < 8) {
        $message = "Password must be at least 8 characters.";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password_raw)) {
        $message = "Password must include at least one special character.";
    } else {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);

        $sql_check = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "Email already registered. ";
        } else {
            $sql = "INSERT INTO users (fullname, email, username, password, role, shop_type)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $fullname, $email, $username, $password, $role, $shop_type);

            if ($stmt->execute()) {
                session_start();
                $_SESSION["user"] = $username;
                $_SESSION["role"] = $role;

                if ($role === "Vendor") {
                    echo "<script>alert('Registration successful! Welcome, $username.'); window.location.href = 'vendor.php';</script>";
                } else {
                    echo "<script>alert('Registration successful! Welcome, $username.'); window.location.href = 'index.html';</script>";
                }
                exit;
            } else {
                $message = "Error: " . $conn->error;
            }

            $stmt->close();
        }

        $stmt_check->close();
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Vendor/User Registration</title>
</head>
<style>
    /* ===== Google Font Import - Poppins ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

/* ===== Reset & Base Styles ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif; /* Use Poppins font globally */
}

/* ===== Body Layout ===== */
body {
    min-height: 100vh;
    background: rgb(55, 186, 252);
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ===== Main Container ===== */
.container {
    width: 100%;
    max-width: 600px;
    background: #050e2d;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
    position: relative;
}

/* ===== Header Section ===== */
header {
    text-align: center;
    margin-bottom: 30px;
}

header h1 {
    font-size: 28px;
    font-weight: 600;
    color: #fff;
}

header p {
    font-size: 16px;
    color: #fff;
}

/* ===== Toggle Buttons (User/Vendor) ===== */
.toggle-container {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
}

.toggle-btn {
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

.toggle-btn:hover,
.toggle-btn.active {
    background: #1aeeef;
    color: #050e2d;
    box-shadow: 0 0 10px #1aeeef;
}


/* ===== Form Layout ===== */
form {
    display: flex;
    flex-direction: column;
}

/* Form section titles (e.g. Personal Details, Business Info) */
form .title {
    font-size: 18px;
    color: #fff;
    margin-bottom: 10px;
}

/* Group of form fields */
form .fields {
    display: flex;
    flex-direction: column;
}

/* ===== Input Field Styles ===== */
.input-field {
    margin-bottom: 20px;
}

/* Labels above inputs */
.input-field label {
    font-size: 14px;
    color: #fff;
    margin-bottom: 5px;
    display: block;
}

/* Input and Select elements */
.input-field input,
.input-field select {
    padding: 10px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
    outline: none;
    width: 100%;
}

/* Highlight border on focus */
.input-field input:focus,
.input-field select:focus {
    border: 1px solid #4070f4;
}

/* ===== Submit Button ===== */
button.submit {
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

/* Submit button hover effect */
button.submit:hover {
    background: #1aeeef;
    color: #050e2d;
    box-shadow: 0 0 10px #1aeeef;
}

/* ===== Vendor Fields (Hidden by Default, shown via JS) ===== */
.vendor {
    display: none;
}
/* login link */
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
/* ===== Error Message Styles ===== */
.error-message {
    color: red;
    text-align: center;
    margin-bottom: 15px;
    font-weight: 500;
}

.arrow{
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 24px;
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}
</style>
<body>
    <div class="container">
        <a href="index.html" class="arrow" id="home-arrow">
            <i class="fas fa-arrow-left"></i>
        </a>
        <header>
            <h1>Join Us Today</h1>
        </header>
        <!-- Toggle Buttons -->
        <div class="toggle-container">
            <button type="button" class="toggle-btn active" id="userBtn">User</button>
            <button type="button" class="toggle-btn" id="vendorBtn">Vendor</button>
        </div>

        <!-- Registration Form -->
        <form action="register.php" method="POST" id="registrationForm">
            <div class="form first">
                <div class="details personal">
                    <div class="fields">
                        <!-- Full Name -->
                        <div class="input-field">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Enter your name" required>
                        </div>

                        <!-- Username -->
                        <div class="input-field">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter username" required>
                        </div>

                        <!-- Email -->
                        <div class="input-field">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <!-- Password -->
                        <div class="input-field">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter password" required>
                        </div>

                        <!-- Hidden Role -->
                        <input type="hidden" id="role" name="role" value="User">
                    </div>
                </div>

                <!-- Vendor Specific Details -->
                <div class="details vendor" style="display: none;">
                    <div class="fields">
                        <!-- Business Name -->
                        <div class="input-field">
                            <input type="text" id="business_name" name="business_name" placeholder="Enter business name">
                        </div>

                        <!-- Business Address -->
                        <div class="input-field">
                            <input type="text" id="business_address" name="business_address" placeholder="Enter address">
                        </div>

                        <!-- Business Phone -->
                        <div class="input-field">
                            <input type="text" id="business_phone" name="business_phone" placeholder="Enter phone number">
                        </div>

                        <!-- Shop Type -->
                        <div class="input-field">
                            <label for="shop_type">Shop Type</label>
                            <select name="shop_type" id="shop_type">
                                <option value="Grocery">Grocery</option>
                                <option value="Stationary">Stationary</option>
                                <option value="Sports">Sports</option>
                                <option value="Cosmetics">Cosmetics</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit">Submit</button>
            <!-- Login Redirect -->
            <p class="login-link">
            Already registered? <a href="login.php">Please login</a>
                </p>
                <?php if (!empty($message)): ?>
    <div class="error-message"><?php echo $message; ?></div>
<?php endif; ?>
        </form>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const userBtn = document.getElementById("userBtn");
    const vendorBtn = document.getElementById("vendorBtn");
    const roleInput = document.getElementById("role");
    const vendorFields = document.querySelector('.vendor');

    // Toggle active class on buttons
    userBtn.addEventListener("click", () => {
        userBtn.classList.add("active");
        vendorBtn.classList.remove("active");
        roleInput.value = "User";
        vendorFields.style.display = "none";

        // Clear vendor fields if user is selected
        document.getElementById("business_name").value = "";
        document.getElementById("business_address").value = "";
        document.getElementById("business_phone").value = "";
        document.getElementById("shop_type").selectedIndex = 0;
    });

    vendorBtn.addEventListener("click", () => {
        vendorBtn.classList.add("active");
        userBtn.classList.remove("active");
        roleInput.value = "Vendor";
        vendorFields.style.display = "block";
    });

    // Set correct default view on load
    if (roleInput.value === "Vendor") {
        vendorBtn.classList.add("active");
        userBtn.classList.remove("active");
        vendorFields.style.display = "block";
    } else {
        userBtn.classList.add("active");
        vendorBtn.classList.remove("active");
        vendorFields.style.display = "none";
    }
});

  userBtn.addEventListener('click', () => {
    userBtn.classList.add('active');
    vendorBtn.classList.remove('active');
    // Show user content if needed
  });

  vendorBtn.addEventListener('click', () => {
    vendorBtn.classList.add('active');
    userBtn.classList.remove('active');
    // Show vendor content if needed
  });
</script>
</html>