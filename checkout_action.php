<?php
session_start();

include 'config.php'; // Sertakan file konfigurasi database

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Set error message
    $_SESSION['error'] = 'Anda harus login terlebih dahulu untuk melakukan pembayaran.';
    // Redirect to login page
    echo "<script>window.alert('Anda harus login terlebih dahulu untuk melakukan pembayaran.');window.location.href='login.php';</script>";
    exit;
} else {
    // If user is logged in, redirect to payment page
    header("Location: payment.php");
    exit;
}
?>
