<?php
include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $specs = $_POST['specs'];
    $price = $_POST['price'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, brand, specs, price, image)
                VALUES ('$name', '$brand', '$specs', '$price', '$image')";
        if ($conn->query($sql) === TRUE) {
            $message = "Laptop added successfully!";
        } else {
            $message = "Database error: " . $conn->error;
        }
    } else {
        $message = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Laptop – Admin Panel</title>
    <style>
        body { font-family: sans-serif; background: #f8f8f8; padding: 20px; }
        form { background: white; padding: 20px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; margin-bottom: 10px; padding: 10px; }
        button { background: green; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .message { margin: 10px auto; color: green; text-align: center; }
        a { display: block; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Add New Laptop</h2>

<?php if ($message): ?>
<div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Laptop Name" required />
    <select name="brand" required>
        <option value="">Select Brand</option>
        <option value="Dell">Dell</option>
        <option value="HP">HP</option>
        <option value="Lenovo">Lenovo</option>
        <option value="Acer">Acer</option>
        <option value="Asus">Asus</option>
    </select>
    <textarea name="specs" placeholder="Specifications" rows="4" required></textarea>
    <input type="number" name="price" placeholder="Price (KES)" required />
    <input type="file" name="image" accept="image/*" required />
    <button type="submit">Add Laptop</button>
</form>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
