<?php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['identifier']);

    // Periksa apakah identifier adalah email atau username
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // Validasi format email
        $email = $identifier;

        // Periksa apakah email ada dalam database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $errors[] = 'Email tidak ditemukan!';
        }
    } else {
        // Validasi format username
        $username = $identifier;

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            $errors[] = 'Username hanya boleh mengandung huruf dan angka!';
        } else {
            // Periksa apakah username ada dalam database
            $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $errors[] = 'Username tidak ditemukan!';
            } else {
                $row = $result->fetch_assoc();
                $email = $row['email'];
            }
        }
    }

    if (empty($errors)) {
        // Generate token reset password
        $token = bin2hex(random_bytes(16));
        $resetLink = "http://localhost/PBW/ProjectAkhir/LEEPS_STORE/reset_password.php?token=$token"; // Ganti URL ini dengan URL halaman reset password Anda

        // Simpan token ke database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        if ($stmt->execute()) {
            // Pengaturan email
            $to = $email;
            $subject = 'Reset Password';
            $message = "Klik link berikut untuk mereset password Anda: $resetLink";
            $headers = 'From: no-reply@leepsstore.com' . "\r\n" .
                       'Reply-To: no-reply@leepsstore.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            // Kirim email
            if (mail($to, $subject, $message, $headers)) {
                echo 'Link reset password telah dikirim ke email Anda.';
            } else {
                echo 'Email gagal dikirim.';
            }
        } else {
            echo 'Gagal menyimpan token reset password.';
        }
    } else {
        $errorMessage = implode("<br>", $errors);
        header("Location: forgot_password.php?error=$errorMessage"); // Redirect kembali ke halaman forgot_password.php dengan pesan error
        exit();
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
