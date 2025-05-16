<?php
require 'config.php';

$id = $_GET['id'];

// Query untuk menghapus produk dari database
$sql = "SELECT image FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row['image'];
    $target_dir = "assets/img/upload/";

    // Hapus file gambar terkait dari server
    if (unlink($target_dir . $image)) {
        // Hapus record dari database
        $sql_delete = "DELETE FROM products WHERE id = $id";
        if ($conn->query($sql_delete) === TRUE) {
            echo "<script>alert('Product deleted successfully');</script>";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error deleting image file";
    }
} else {
    echo "Product not found";
}
?>
