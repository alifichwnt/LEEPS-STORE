<?php
// Mulai sesi
session_start();

// Sertakan file konfigurasi
require 'config.php';

$errors = [];

// Validasi input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($username)) {
        $errors[] = 'Username atau Email tidak boleh kosong!';
    }

    if (empty($password)) {
        $errors[] = 'Password tidak boleh kosong!';
    }

    // Jika ada kesalahan, tampilkan semua kesalahan
    if (!empty($errors)) {
        $error_message = urlencode(implode("\\n", $errors));
        header("Location: login.php?error=$error_message");
        exit();
    }

    // Siapkan dan bind, di sini kita periksa baik username atau email
    $stmt = $conn->prepare("SELECT * FROM users WHERE BINARY username = ? OR BINARY email = ?");
    $stmt->bind_param("ss", $username, $username); // Bind parameter untuk username atau email

    // Eksekusi statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah username atau email ada, jika ya verifikasi password
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verifikasi password untuk pengguna biasa
        if (password_verify($password, $stored_password)) {
            // Password benar untuk pengguna biasa, mulai sesi baru dan simpan username
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['alamat'] = $row['alamat']; // Simpan alamat dalam sesi
            $_SESSION['pekerjaan'] = $row['pekerjaan']; // Simpan pekerjaan dalam sesi
            $_SESSION['jenis_kelamin'] = $row['jenis_kelamin']; // Simpan jenis kelamin dalam sesi
            header("Location: index.php");
            exit();
        } else {
            // Tambahkan pesan error jika password tidak valid
            $errors[] = 'Username atau Password yang Anda masukkan salah!';
        }
    } else {
        // Tambahkan pesan error jika username atau email tidak ada
        $errors[] = 'Akun yang Anda masukkan tidak terdaftar!';
    }

    // Tutup statement
    $stmt->close();

    // Jika ada kesalahan, tampilkan semua kesalahan
    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.location.href='login.php';</script>";
        exit();
    }
}

// Tutup koneksi
$conn->close();
?>