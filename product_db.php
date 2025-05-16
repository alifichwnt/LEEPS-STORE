<?php
include 'config.php'; // Sertakan file konfigurasi database

// Baca file JSON
$json_data = file_get_contents('product.json');

// Decode JSON menjadi array asosiatif
$data = json_decode($json_data, true);

// Buat tabel jika belum ada
$sql_create_table = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10, 2),
    image BLOB
)";

if ($conn->query($sql_create_table) === TRUE) {
    echo "Tabel products berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error: " . $sql_create_table . "<br>" . $conn->error;
}

// Siapkan pernyataan SQL INSERT
$stmt = $conn->prepare("INSERT INTO products (id, name, price, image) VALUES (?, ?, ?, ?)");

if ($stmt === FALSE) {
    die("Error preparing statement: " . $conn->error);
}

// Iterasi data dan masukkan ke dalam tabel
foreach ($data as $item) {
    $id = $item['id'];
    $name = $item['name'];
    $price = $item['price'];
    $image = $item['image'];

    // Bind parameter dan eksekusi pernyataan SQL
    $stmt->bind_param("isss", $id, $name, $price, $image);

    if ($stmt->execute() === TRUE) {
        echo "Data berhasil dimasukkan untuk ID: $id.<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>