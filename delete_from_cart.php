<?php
include 'config.php';
include 'function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    deleteCart($id, $conn);
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid']);
}
?>
