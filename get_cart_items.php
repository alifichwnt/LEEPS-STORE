<?php
include 'config.php';
include 'function.php';

// Check if delete button is clicked
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    // Call the deleteCart function
    $deleted = deleteCart($delete_id, $conn);
    if ($deleted) {
        // Optionally, you can add a message or perform other actions after deletion
        echo "Item deleted successfully.";
    }
}

// Retrieve cart items
$cart_items = showCart($_SESSION['id'], $conn);

// Check if cart_items array is empty
if (empty($cart_items)) {
    echo '<div>No item in cart</div>';
} else {
    // Display cart items
    foreach ($cart_items as $item) {
        echo '<div class="item">';
        echo '<div class="image"><img src="css/image/' . $item['image'] . '"></div>';
        echo '<div class="name">' . $item['name'] . '</div>';
        echo '<div class="totalPrice">IDR ' . number_format($item['subtotal'], 0, ',', '.') . 'k</div>';
        echo '<div class="quantity">';
        echo '<span class="minus" data-id="' . $item['product_id'] . '">-</span>';
        echo '<span class="qty">' . $item['qty'] . '</span>';
        echo '<span class="plus" data-id="' . $item['product_id'] . '">+</span>';
        echo '</div>';
        echo '<div class="delete">';
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="delete_id" value="' . $item['id'] . '">';
        echo '<button type="submit" class="delete-item">Hapus</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
}
