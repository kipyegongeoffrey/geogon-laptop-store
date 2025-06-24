<?php
include 'db.php';

// Fetch all products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard – Admin Panel</title>
  <style>
    body { font-family: sans-serif; background: #f1f1f1; padding: 20px; }
    h2 { text-align: center; }
    .container { max-width: 1000px; margin: auto; }
    table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    img { width: 100px; height: auto; border-radius: 5px; }
    .actions a { color: red; text-decoration: none; font-weight: bold; }
    .top-bar { text-align: right; margin-bottom: 10px; }
    .top-bar a { padding: 10px 15px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; }
  </style>
</head>
<body>

<h2>📋 Product Dashboard</h2>

<div class="container">
  <div class="top-bar">
    <a href="add_product.php">➕ Add Laptop</a>
  </div>

  <table>
    <tr>
      <th>Image</th>
      <th>Name</th>
      <th>Brand</th>
      <th>Specs</th>
      <th>Price (KES)</th>
      <th>Action</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><img src="uploads/<?php echo $row['image']; ?>" alt="image" /></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['brand']; ?></td>
          <td><?php echo $row['specs']; ?></td>
          <td><?php echo number_format($row['price']); ?></td>
          <td class="actions">
            <a href="delete.php?id=<?php echo $row['id']; ?>
