<?php
// Mulai sesi
session_start();

// Hapus semua data sesi kecuali ID sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Alihkan ke halaman login dengan membersihkan keranjang di localStorage
echo "<script>
    localStorage.removeItem('cart');
    window.location.href = 'index.php';
</script>";
exit();
?>
