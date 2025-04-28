<?php
require_once "config.php";

$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 9";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0d1530;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 2rem 0 1rem;
            color: #ffffff;
        }

        .nav-links {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem 2rem 0 0;
        }

        .nav-links a {
            display: inline-block;
        padding: 10px 20px;
        margin: 20px 0;
        border: 1px solid #1aeeef;
        text-decoration: none;
        font-weight: 700;
        border-radius: 5px;
        color: #fff;
        letter-spacing: 1px;
        }

        .nav-links a:hover {
            background: #1aeeef;
        color: #050e2d;
        box-shadow: 0 0 10px #1aeeef;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: auto;
        }

        .card {
            background: #1b2141;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
            width: 90%; /* Decreased width */
            margin: auto; /* Center the card */
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card img {
            width: 100%;
            height: 300px; /* Increased height */
            object-fit: cover; /* Ensures proper fit */
        }

        .content {
            padding: 15px;
        }

        .content h4 {
            color: #fff;
            margin: 15px 0;
            font-size: 1.2rem;
        }

        .progress-line {
            position: relative;
            height: 10px;
            background: #35407e;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 0 5px rgba(53, 64, 126, 0.3);
        }

        .progress-line span {
            position: absolute;
            height: 100%;
            width: 80%;
            border-radius: 10px;
            background: #1aeeef;
            box-shadow: 0 0 5px #1aeeef, 0 0 10px #1aeeef;
        }

        .info {
            border-top: 2px solid #35407e;
            padding: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info p {
            font-size: 1em;
            color: #fff;
        }

        .info p span {
            color: #1aeeef;
        }

        .info a {
            display: inline-block;
        padding: 10px 20px;
        margin: 20px 0;
        border: 1px solid #1aeeef;
        text-decoration: none;
        font-weight: 700;
        border-radius: 5px;
        color: #fff;
        letter-spacing: 1px;
        }

        .info a:hover {
            background: #1aeeef;
        color: #050e2d;
        box-shadow: 0 0 10px #1aeeef;
        }

        @media screen and (max-width: 900px) {
            .product-grid {
            grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 600px) {
            .product-grid {
            grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="nav-links">
    <a href="index.html">Home</a>
    <a href="vendor.php">Add Product</a>
</div>

<h1>Latest Products</h1>

<div class="product-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <div class="content">
                <h4><?= htmlspecialchars($row['name']) ?></h4>
                <div class="progress-line"><span></span></div>
                <div class="info">
                    <p>Pricing<br><span>&#8377; <?= number_format($row['price'], 2) ?></span></p>
                    <a href="#">Shop Now</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>