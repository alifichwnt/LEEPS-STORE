<?php
// Mulai sesi
session_start();

// Sertakan file konfigurasi
require 'config.php';

// Cek apakah pengguna sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari sesi
$userId = $_SESSION['id'];

// Inisialisasi variabel untuk pesan kesalahan
$errors = [];

// Validasi input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alamat = trim($_POST['alamat']);
    $pekerjaan = trim($_POST['pekerjaan']);
    $jenis_kelamin = trim($_POST['jenis-kelamin']);

    // Simpan nilai-nilai input sebelumnya ke dalam sesi
    $_SESSION['alamat'] = $alamat;
    $_SESSION['pekerjaan'] = $pekerjaan;
    $_SESSION['jenis_kelamin'] = $jenis_kelamin;

    // Validasi input kosong
    if (empty($alamat)) {
        $errors[] = 'Alamat tempat tinggal tidak boleh kosong!';
    }
    if (empty($pekerjaan)) {
        $errors[] = 'Pekerjaan tidak boleh kosong!';
    }
    if (empty($jenis_kelamin)) {
        $errors[] = 'Jenis kelamin tidak boleh kosong!';
    }

    // Validasi unggahan avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $errors[] = 'Format file avatar tidak valid!';
        }

        if ($fileSize > 2 * 1024 * 1024) { // Batas ukuran file 2MB
            $errors[] = 'Ukuran file avatar terlalu besar!';
        }

        $avatarData = file_get_contents($fileTmpPath);
    } else {
        $avatarData = null;
    }

    // Jika tidak ada kesalahan, perbarui informasi pengguna di database
    if (empty($errors)) {
        try {
            // Reconnect jika koneksi terputus
            if (!$conn->ping()) {
                $conn->close();
                require 'config.php';
            }

            if ($avatarData !== null) {
                $stmt = $conn->prepare("UPDATE users SET alamat = ?, pekerjaan = ?, jenis_kelamin = ?, avatar = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $alamat, $pekerjaan, $jenis_kelamin, $avatarData, $userId);
            } else {
                // Jika avatar tidak diunggah, tetap perbarui informasi lain tanpa mengubah avatar
                $stmt = $conn->prepare("UPDATE users SET alamat = ?, pekerjaan = ?, jenis_kelamin = ? WHERE id = ?");
                $stmt->bind_param("sssi", $alamat, $pekerjaan, $jenis_kelamin, $userId);
            }

            if ($stmt->execute()) {
                // Berikan feedback kepada pengguna
                echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='profile.php';</script>";
            } else {
                $errors[] = 'Gagal memperbarui profil. Silakan coba lagi.';
            }

            // Tutup statement
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $errors[] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    // Jika ada kesalahan, tampilkan semua kesalahan
    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.location.href='profile.php';</script>";
    }
}

// Tutup koneksi
$conn->close();
?>
