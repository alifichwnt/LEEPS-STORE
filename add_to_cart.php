<?php
include 'config.php';
include 'function.php';
session_start();

$product_id = $_POST['product_id'];
$product = getProductById($product_id, $conn);

$data = [
    'user_id' => $_SESSION['id'],
    'product_id' => $product_id,
    'qty'  => 1, // Initial quantity when adding to cart
    'subtotal' => $product['price'] // Subtotal based on product price
];

addToCart($data, $conn);

// Redirect back to the catalog page or any other desired page
header("Location: catalog.php");
exit();
?>
