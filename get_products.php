<?php
// Sertakan file config.php untuk koneksi database
require_once 'config.php';

// Query untuk mengambil data produk dari tabel products
$query = "SELECT * FROM products";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    // Mengembalikan data produk dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    echo json_encode(array()); // Mengembalikan array kosong jika tidak ada produk
}

$conn->close();
?>
