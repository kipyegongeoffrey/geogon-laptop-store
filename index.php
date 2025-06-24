<!DOCTYPE html>
<html>
<head>
    <title>Geogon Laptop Store – Home</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #004080;
            text-align: center;
            padding: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .container {
            padding: 40px;
            text-align: center;
        }
        .brand-buttons a {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
        }
        .brand-buttons a:hover {
            background-color: #005fa3;
        }
        footer {
            text-align: center;
            background-color: #e9ecef;
            padding: 15px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Geogon Laptop Store</h1>
    <p>Your trusted source for quality laptops</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="products.php">View All Products</a>
    <a href="cart.php">Cart</a>
    <a href="checkout.php">Checkout</a>
</nav>

<div class="container">
    <h2>Browse by Brand</h2>
    <div class="brand-buttons">
        <a href="products.php?brand=Dell">Dell</a>
        <a href="products.php?brand=HP">HP</a>
        <a href="products.php?brand=Lenovo">Lenovo</a>
        <a href="products.php?brand=Acer">Acer</a>
        <a href="products.php?brand=Asus">Asus</a>
    </div>
    <p><a href="products.php" style="text-decoration: underline;">Or view all products</a></p>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Geogon Laptop Store. All rights reserved.
</footer>

</body>
</html>
