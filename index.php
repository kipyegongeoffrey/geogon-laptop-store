<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Geogon Laptop Store</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; background: #f4f9ff; }
        header {
            background: #004080;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background: #002a5c;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .hero {
            text-align: center;
            padding: 60px 20px;
            background: #e0f0ff;
        }
        .hero h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 18px;
            color: #333;
        }
        .categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 40px 20px;
            gap: 20px;
        }
        .cat-box {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            width: 200px;
            transition: 0.3s;
        }
        .cat-box:hover {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transform: scale(1.05);
        }
        .cat-box a {
            text-decoration: none;
            color: #004080;
            font-weight: bold;
        }
        footer {
            text-align: center;
            background: #004080;
            color: white;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <h1>Geogon Laptop Store</h1>
    <p>Quality Laptops – Affordable Prices – M-PESA Checkout</p>
</header>

<nav>
    <a href="index.php">🏠 Home</a>
    <a href="products.php">🛒 Shop</a>
    <a href="cart.php">🧺 Cart</a>
    <a href="checkout.php">💳 Checkout</a>
</nav>

<div class="hero">
    <h1>Welcome to Geogon Store</h1>
    <p>Browse by your favorite laptop brand</p>
</div>

<div class="categories">
    <div class="cat-box"><a href="products.php?brand=Dell">💻 Dell</a></div>
    <div class="cat-box"><a href="products.php?brand=HP">💼 HP</a></div>
    <div class="cat-box"><a href="products.php?brand=Lenovo">🔋 Lenovo</a></div>
    <div class="cat-box"><a href="products.php?brand=Acer">🚀 Acer</a></div>
    <div class="cat-box"><a href="products.php?brand=Asus">🧠 Asus</a></div>
</div>

<footer>
    &copy; <?= date('Y') ?> Geogon Laptop Store. All rights reserved.
</footer>

</body>
</html>