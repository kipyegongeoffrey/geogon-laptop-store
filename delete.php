<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the image filename
    $getImage = $conn->query("SELECT image FROM products WHERE id = $id");
    if ($getImage && $getImage->num_rows > 0) {
        $row = $getImage->fetch_assoc();
        $image = $row['image'];

        // Delete image file from uploads folder
        if (file_exists("uploads/$image")) {
            unlink("uploads/$image");
        }
    }

    // Delete record from database
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?deleted=1");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid ID.";
}
?>
