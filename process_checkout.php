<?php
session_start();
include 'config.php'; // Sertakan file konfigurasi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $alamat = $_POST['address'];
    $paymentType = $_POST['paymentMethod'];
    $norek = '';
    if ($paymentType == 'BCA') {
        $norek = $_POST['bcaAccount'];
    } elseif ($paymentType == 'DANA') {
        $norek = $_POST['danaAccount'];
    }

    // Ambil data keranjang belanja dari POST
    $cartData = isset($_POST['cartData']) ? json_decode($_POST['cartData'], true) : [];
    $products = [];
    $totalProduct = 0;
    $totalPrice = 0;

    foreach ($cartData as $item) {
        $products[] = $item['name'] . ' (' . $item['quantity'] . ')';
        $totalProduct += $item['quantity'];
        $totalPrice += $item['price'] * $item['quantity'];
    }

    $productList = implode(', ', $products);

    // Dapatkan tanggal saat ini
    $currentDate = date('Y-m-d');

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO listbuying (username, alamat, product, totalProduct, totalPrice, paymentType, norek, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssiiiss', $username, $alamat, $productList, $totalProduct, $totalPrice, $paymentType, $norek, $currentDate);

    if ($stmt->execute()) {
        // Jika berhasil, kosongkan keranjang belanja (localStorage)
        echo "<script>
                localStorage.removeItem('cart');
                window.alert('Pembayaran berhasil diproses. Terima kasih telah berbelanja di LEEP\'s STORE.');
                window.location.href = 'profile.php'; // Arahkan ke halaman profile.php
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>
                window.alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                window.location.href = 'payment.php';
              </script>";
    }
    $stmt->close();
    $conn->close();
} else {
    // Jika bukan metode POST, redirect ke halaman payment
    header("Location: payment.php");
    exit;
}
?>