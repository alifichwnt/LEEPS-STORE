<?php
include 'config.php';
include 'function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $change = $_POST['change']; // nilai +1 atau -1

    $current_cart_item = getCartItem($_SESSION['id'], $product_id, $conn);

    if (!$current_cart_item) {
        echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan dalam keranjang']);
        exit();
    }

    $new_qty = $current_cart_item['qty'] + $change;

    if ($new_qty <= 0) {
        // Jika quantity menjadi 0 atau negatif, hapus dari keranjang
        deleteCart($current_cart_item['id'], $conn);
    } else {
        // Update quantity di dalam keranjang
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_qty, $current_cart_item['id']);
        $stmt->execute();
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid']);
}
?>
