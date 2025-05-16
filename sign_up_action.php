<?php

// Mulai sesi
session_start();

// Sertakan file konfigurasi
require 'config.php';

// Validasi input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

    // Validasi username hanya boleh huruf atau kombinasi huruf dan angka
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors[] = 'Username hanya boleh mengandung huruf dan angka!';
    }

    // Validasi email harus mengandung karakter "@"
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid!';
    }

    // Validasi password harus kombinasi huruf dan angka
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
        $errors[] = 'Password harus kombinasi huruf dan angka dan minimal 6 karakter!';
    }

    // Periksa apakah username atau email sudah ada
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = 'Akun sudah pernah digunakan!';
    }

    // Jika ada kesalahan, tampilkan semua kesalahan
    if (!empty($errors)) {
        $error_message = urlencode(implode("\\n", $errors));
        header("Location: sign_up.php?error=$error_message");
        exit();
    }

    // Hash password sebelum menyimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan dan bind untuk memasukkan data baru
    $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $hashed_password);
    
    // Eksekusi statement
    if ($stmt->execute()) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $stmt->error . "'); window.location.href='sign_up.php';</script>";
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
