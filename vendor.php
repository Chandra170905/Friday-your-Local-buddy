<?php
require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $vendor = htmlspecialchars(trim($_POST["vendor"]));
    $name = htmlspecialchars(trim($_POST["name"]));
    $price = floatval($_POST["price"]);
    $image_path = "";

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $message = "Failed to upload image.";
        }
    }

    if ($message === "") {
        $stmt = $conn->prepare("INSERT INTO products (vendor_username, name, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $vendor, $name, $price, $image_path);
        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $message = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        .form-container {
            position: relative;
            max-width: 600px;
            background-color: #1b2141;
            padding: 2.5rem;
            margin: 4rem auto;
            border-radius: 1rem;
            box-shadow: 0 0 10px #1aeeef;
        }

        .form-container .nav-links {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            margin: 0;
            text-align: right;
        }

        .form-container .nav-links a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            border: 1px solid #1aeeef;
            text-decoration: none;
            font-weight: 700;
            border-radius: 5px;
            color: #fff;
            letter-spacing: 1px;
        }

        .form-container .nav-links a:hover {
            background: #1aeeef;
            color: #050e2d;
            box-shadow: 0 0 10px #1aeeef;
        }

        body {
            background-color: #0d1530;
            font-family: 'Poppins', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }

        .form-container h3 {
            text-align: left;
            color: #1aeeef;
            margin-bottom: 1.5rem;
        }

        .form-container input,
        .form-container button {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 5px;
            font-size: 1em;
            background-color: #050e2d;
            color: #fff;
            border: 1px solid #1aeeef;
        }

        .form-container input:focus {
            outline: none;
            box-shadow: 0 0 10px #1aeeef;
        }

        .form-container button {
            display: block;
            width: 50%;
            margin: 1.5rem auto;
            padding: 10px 20px;
            border: 1px solid #1aeeef;
            text-decoration: none;
            font-weight: 700;
            border-radius: 5px;
            color: #fff;
        }
        .form-container button:hover {
            background: #1aeeef;
            color: #050e2d;
            box-shadow: 0 0 10px #1aeeef;
        }

        .message {
            text-align: center;
            font-size: 1em;
            color: lightgreen;
            margin-top: 1rem;
        }

        .vendor-info {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
            color: #1ea0a0;
        }

        .vendor-info p {
            margin-top: 10px;
            font-size: 1em;
            font-weight: 500;
        }

        .form-container button {
            margin-top: 1rem;
        }

        .form-container input[type="file"] {
            display: none;
        }

        .upload-label {
            display: inline-block;
            background-color: #050e2d;
            color: grey;
            padding: 0.75rem;
            width: 100%;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 1rem;
            transition: 0.3s;
            border: 1px solid #1aeeef;
        }

        .upload-label::before {
            content: '+ Upload';
        }

    </style>
</head>
<body>

<div class="form-container">
    <div class="nav-links">
        <a href="index.html" >Home</a>
        <a href="product.php" >View Products</a>
    </div>
        <br><br>
    <h1 class="vendor-info">Let your product shine! Add it now and get ready to wow your customers!🛍️</h1>

    <h3>Add a Product</h3>
    <form action="vendor.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="vendor" placeholder="Vendor Name" required>
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" step="0.01" placeholder="Price (₹)" required>

        <input type="file" name="image" id="fileUpload" accept="image/*" required>
        <label for="fileUpload" class="upload-label"></label>

        <button type="submit">Add Product</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
</div>

</body>
</html>
