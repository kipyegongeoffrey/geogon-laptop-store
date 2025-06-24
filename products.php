<?php
session_start();
include 'db.php';

$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : '';

// Fetch products from DB
$sql = "SELECT * FROM products";
if ($brandFilter) {
    $sql .= " WHERE brand = '" . $conn->real_escape_string($brandFilter) . "'";
}
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Laptops – Geogon Store</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f9fc; }
        header, nav, footer {
            background: #004080;
            color: white;
            text-align: center;
            padding: 15px;
        }
        nav a { color: white; margin: 0 10px; text-decoration: none; font-weight: bold; }
        .container { padding: 20px; max-width: 1200px; margin: auto; }
        .product {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            width: 270px;
            float: left;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product h3 { margin: 10px 0 5px 0; }
        .product p { font-size: 14px; }
        .product span { font-weight: bold; color: #0d6efd; }
        .product form { margin-top: 10px; }
        .product button {
            background: green;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .clear { clear: both; }
    </style>
</head>
<body>

<header>
    <h2>Geogon Laptop Store</h2>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="products.php">All Products</a>
    <a href="cart.php">Cart</a>
</nav>

<div class="container">
    <h2>
        <?php echo $brandFilter ? htmlspecialchars($brandFilter) . " Laptops" : "All Laptops"; ?>
    </h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="uploads/<?php echo $row['image']; ?>" alt="Laptop">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><strong>Brand:</strong> <?php echo $row['brand']; ?></p>
                <p><?php echo $row['specs']; ?></p>
                <p><span>KES <?php echo number_format($row['price']); ?></span></p>
                <form action="cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No laptops available in this category.</p>
    <?php endif; ?>

    <div class="clear"></div>
</div>

</body>
</html>
