<?php
// Mulai sesi
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve user data
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Variabel untuk menampung nilai input sebelumnya
$alamatValue = isset($_POST['alamat']) ? $_POST['alamat'] : ''; // Nilai input alamat
$pekerjaanValue = isset($_POST['pekerjaan']) ? $_POST['pekerjaan'] : ''; // Nilai input pekerjaan
$jenisKelaminValue = isset($_POST['jenis-kelamin']) ? $_POST['jenis-kelamin'] : ''; // Nilai input jenis kelamin
$user_avatar = $user['avatar'];

// Query data pembelian terakhir dari tabel listbuying
$sqlPurchase = "SELECT * FROM listbuying WHERE username = ? ";
$stmtPurchase = $conn->prepare($sqlPurchase);
$stmtPurchase->bind_param("s", $user['username']);
$stmtPurchase->execute();
$resultPurchase = $stmtPurchase->get_result();
$purchase = $resultPurchase->fetch_assoc();

// Menampilkan pesan sukses atau error jika ada
if (isset($_SESSION['success'])) {
    echo '<p class="success-message">' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <title>Profile User - LEEPS STORE</title>
    <style>
        /* CSS Internal */
        :root {
            --primary: rgba(146, 131, 48, 0.89);
            --bg: rgba(206, 177, 120, 0.925);
        }

        html {
            scroll-behavior: smooth;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
        }

        body {
            background-image: url("css/image/background-body.jpg");
        }

        .profile {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .profile-info {
            margin-left: 30px;
            height: 30%;
        }

        .profile-info,
        .edit-profile {
            flex-basis: 80%;
            background-color: whitesmoke;
            padding: 5px;
            border-radius: 10px;
            margin-right: 20%;
            margin-top: 6rem;
        }

        .profile-info:hover,
        .edit-profile:hover {
            background-color: greenyellow;
        }

        .edit-profile form {
            margin-top: 1rem;
        }

        .profile h2 {
            text-align: center;
            text-shadow: 2px 1px 3px rgba(1, 1, 3, 0.5);
        }

        .edit-profile form label {
            font-size: medium;
            text-decoration: underline;
            text-shadow: 2px 1px 3px rgba(1, 1, 3, 0.5);
        }

        .edit-profile form label,
        .edit-profile form input,
        .edit-profile form select {
            display: block;
            margin-bottom: 1rem;
        }

        .edit-profile form input[type="file"] {
            margin-top: 0.5rem;
        }

        .edit-profile form button {
            padding: 10px 20px;
            background-color: #2ba8fb;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-profile form button:hover {
            background-color: #6fc5ff;
        }

        /* CSS untuk avatar */
        .avatar-container {
            margin-top: 1rem;
        }

        .avatar-container img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 50%;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2ba8fb;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #6fc5ff;
        }

        .signout-button {
            justify-content: center;
            display: flex;
            padding: 5px 5px;
            background-color: #2ba8fb;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .signout-button:hover {
            background-color: #6fc5ff;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.4rem 7%;
            background-color: rgba(1, 1, 1, 0.8);
            border-bottom: 1px solid rgb(202, 159, 102);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
        }

        .navbar .navbar-logo {
            font-size: 3rem;
            font-weight: 1000;
            color: rgb(236, 225, 195);
            font-style: oblique;
        }

        .navbar .navbar-logo span {
            color: var(--primary);
        }

        .purchase-history {
            margin-top: 20px;
            background-color: whitesmoke;
            padding: 20px;
            border-radius: 10px;
        }

        .purchase-history h2,
        p {
            text-align: center;
        }

        .purchase-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .purchase-table th,
        .purchase-table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .purchase-table th {
            background-color: #f2f2f2;
            color: #333;
        }

        /* Media Queries */
        /* Laptop */
        @media (max-width: 1366px) {
            html {
                font-size: 100%;
            }
        }

        /* Tablet */
        @media (max-width: 758px) {
            html {
                font-size: 80%;
            }

            .profile-info {
                padding: 20px;
                margin-right: 15%;
            }

            .profile-info,
            .edit-profile {
                flex-basis: 45%;
                width: 60%;
                border-radius: 5px;
            }

            .purchase-history {
                margin-left: 2.5rem;
                position: relative;
                text-align: center;
                width: 90%;
            }

            .purchase-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .purchase-table th,
            .purchase-table td {
                padding: 10px;
                text-align: center;
                border-bottom: 1px solid #ddd;
                width: 50%;
            }
        }

        /* Mobile Phone */
        @media (max-width: 450px) {
            html {
                font-size: 60%;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-logo">LEEP's <span>STORE</span></a>
    </nav>

    <div class="profile">
        <div class="profile-info">
            <h2>Informasi Profil</h2>
            <br>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <br>
            <a href="logout_action.php" class="signout-button">Log Out</a>
        </div>

        <div class="edit-profile">
            <h2>Edit Profil</h2>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <label for="alamat">Alamat Tempat Tinggal:</label>
                <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat tempat tinggal" value="<?php echo isset($_SESSION['alamat']) ? htmlspecialchars($_SESSION['alamat']) : ''; ?>">

                <label for="pekerjaan">Pekerjaan:</label>
                <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Masukkan pekerjaan" value="<?php echo isset($_SESSION['pekerjaan']) ? htmlspecialchars($_SESSION['pekerjaan']) : ''; ?>">

                <label for="jenis-kelamin">Jenis Kelamin:</label>
                <select id="jenis-kelamin" name="jenis-kelamin">
                    <option value="laki-laki" <?php echo (isset($_SESSION['jenis_kelamin']) && $_SESSION['jenis_kelamin'] === 'laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="perempuan" <?php echo (isset($_SESSION['jenis_kelamin']) && $_SESSION['jenis_kelamin'] === 'perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>

                <label for="avatar">Avatar:</label>
                <input type="file" id="avatar" name="avatar">

                <!-- Tampilkan avatar -->
                <div class="avatar-container">
                    <?php if (!empty($user_avatar)) : ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($user_avatar); ?>" alt="Avatar">
                    <?php else : ?>
                        <p>Belum ada avatar</p>
                    <?php endif; ?>
                </div>

                <button type="submit">Simpan Perubahan</button>
                <a href="index.php" class="back-button">Kembali</a>
            </form>
        </div>
    </div>
    <br>
    <div class="purchase-history">
        <h2>Riwayat Pembelian</h2>
        <?php if ($resultPurchase->num_rows > 0) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Nama</th>
                        <th>Metode</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM order_confirm WHERE user_id = ?");
                    $stmt->bind_param('s', $_SESSION['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['account_name']}</td>
                                <td>{$row['metode']}</td>
                                <td>IDR{$row['nominal']}k</td>
                                <td>{$row['status']}</td>
                              </tr>";
                    }
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Belum ada riwayat pembelian.</p>
        <?php endif; ?>
    </div>
</body>

</html>