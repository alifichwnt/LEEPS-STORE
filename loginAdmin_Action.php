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
        header("Location: loginAdmin.php?error=$error_message");
        exit();
    }

    // Siapkan dan bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE BINARY username = ? OR BINARY email = ?");
    $stmt->bind_param("ss", $username, $username); // Bind parameter untuk username atau email

    // Eksekusi statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah username atau email ada, jika ya verifikasi password
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Tambahkan kondisional untuk admin
        if ($row['username'] == 'admin') {
            if ($password == $stored_password) {
                // Password benar untuk admin, mulai sesi baru dan simpan username
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['alamat'] = $row['alamat']; // Simpan alamat dalam sesi
                $_SESSION['pekerjaan'] = $row['pekerjaan']; // Simpan pekerjaan dalam sesi
                $_SESSION['jenis_kelamin'] = $row['jenis_kelamin']; // Simpan jenis kelamin dalam sesi
                header("Location: admin.php");
                exit();
            } else {
                // Password admin salah
                $errors[] = 'Username atau Password yang Anda masukkan salah!';
            }
        } else {
            // Verifikasi password untuk pengguna non-admin
            if (password_verify($password, $stored_password)) {
                // Password benar, mulai sesi baru dan simpan username
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['alamat'] = $row['alamat']; // Simpan alamat dalam sesi
                $_SESSION['pekerjaan'] = $row['pekerjaan']; // Simpan pekerjaan dalam sesi
                $_SESSION['jenis_kelamin'] = $row['jenis_kelamin']; // Simpan jenis kelamin dalam sesi
                header("Location: index.php");
                exit();
            } else {
                // Password pengguna non-admin salah
                $errors[] = 'Username atau Password yang Anda masukkan salah!';
            }
        }
    } else {
        // Username atau email tidak ditemukan
        $errors[] = 'Akun yang Anda masukkan tidak terdaftar!';
    }

    // Tutup statement
    $stmt->close();

    // Jika ada kesalahan, tampilkan semua kesalahan
    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.location.href='loginAdmin.php';</script>";
        exit();
    }
}

// Tutup koneksi
$conn->close();
?>
