<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Check if cart session exists, if not, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve product_id and quantity from POST request
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;

// If product_id and quantity are provided, update cart session
if ($product_id !== null && $quantity !== null) {
    // Sanitize inputs
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    // Update cart session with new product quantity
    $_SESSION['cart'][$product_id] = $quantity;
}

// Redirect back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
