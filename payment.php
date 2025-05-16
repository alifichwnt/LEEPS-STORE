<?php
session_start();
include 'function.php';
include 'config.php'; // Sertakan file konfigurasi database

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Set error message
    $_SESSION['error'] = 'Anda harus login terlebih dahulu untuk melakukan pembayaran.';
    // Redirect to login page
    echo "<script>window.alert('Anda harus login terlebih dahulu untuk melakukan pembayaran.');window.location.href='login.php';</script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = uniqid();
    $account_name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['paymentMethod'];
    $nominal = total($conn, $_SESSION['id']);


    // Handle file upload
    $image = '';
    if (isset($_FILES['bcaProof']) && $_FILES['bcaProof']['error'] === UPLOAD_ERR_OK) {
        $image = 'uploads/' . basename($_FILES['bcaProof']['name']);
        move_uploaded_file($_FILES['bcaProof']['tmp_name'], $image);
    } elseif (isset($_FILES['danaProof']) && $_FILES['danaProof']['error'] === UPLOAD_ERR_OK) {
        $image = 'uploads/' . basename($_FILES['danaProof']['name']);
        move_uploaded_file($_FILES['danaProof']['tmp_name'], $image);
    }

    $account_number = '';
    if ($payment_method === 'BCA') {
        $account_number = $_POST['bcaAccount'];
    } elseif ($payment_method === 'DANA') {
        $account_number = $_POST['danaAccount'];
    }

    // Save to database
    $stmt = $conn->prepare("INSERT INTO order_confirm (order_id, account_name, account_number, nominal, metode, image, status) VALUES (?, ?, ?, ?, ?, ?, 'waiting')");
    $stmt->bind_param('sssiss', $order_id, $account_name, $account_number, $nominal, $payment_method, $image);
    $stmt->execute();
    $stmt->close();

    $total = total($conn, $_SESSION['id']);

    $data = [
        'user_id' => $_SESSION['id'],
        'date' => date('Y-m-d'),
        'invoice' => $_SESSION['id'] . date('YmdHis'),
        'total' => $total,
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'status' => 'waiting'
    ];

    // Insert order
    $order_id = insertOrder($conn, $data);

    // Insert order details
    $cart = getCartByIdUser($conn, $_SESSION['id']);
    foreach ($cart as $item) {
        $order_detail = [
            'orders_id' => $order_id,
            'product_id' => $item['product_id'],
            'subtotal' => $item['subtotal']
        ];
        insertOrdersDetail($conn, $order_detail);
    }

    deleteCartPayment($conn, $_SESSION['id']);
    // Redirect to a confirmation or order history page
    header('Location: profile.php');
    exit;
}



$cart = getCart($conn, $_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - LEEPS STORE</title>
    <link rel="stylesheet" href="css/styles1.css">
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .Items h1 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        .table thead th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tfoot th {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .table tfoot th:last-child {
            color: #4CAF50;
        }

        .checkout-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .checkout-form h2 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            color: #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Arial', sans-serif;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .container h1 {
            color: #ccc;
        }

        .checkout-form label {
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            display: flex;
            margin-bottom: 5px;
            color: black;
            text-shadow: 2px 3px 1px #ffffffbe;
        }
    </style>
</head>

<body style="padding-top: 120px">
    <nav class="navbar">
        <a href="index.php" class="navbar-logo">LEEP's <span>STORE</span></a>
    </nav>
    <div class="container">
        <div class="Items">
            <h1>Checkout</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $c) : ?>
                        <tr>
                            <td><?= $c['name'] ?></td>
                            <td>Rp. <?= number_format($c['price'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>Rp. <?= number_format(array_sum(array_column($cart, 'subtotal')), 2, ',', '.') ?>,-</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="checkout-form">
            <h2>Informasi Pembayaran</h2>
            <form action="payment.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Alamat:</label>
                    <textarea id="address" name="address" required><?php echo isset($_SESSION['alamat']) ? htmlspecialchars($_SESSION['alamat']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Telepon:</label>
                    <input type="tel" id="phone" name="phone" required maxlength="12">
                </div>
                <div class="form-group">
                    <label for="paymentMethod">Metode Pembayaran:</label>
                    <select id="paymentMethod" name="paymentMethod" onchange="showPaymentInstructions()" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="BCA">BCA</option>
                        <option value="DANA">DANA</option>
                        <option value="COD">Cash on Delivery (COD)</option>
                    </select>
                </div>
                <div id="paymentInstructions"></div>
                <div id="bcaFields" style="display:none;">
                    <div class="form-group">
                        <label for="bcaAccount">Nomor Rekening BCA Anda:</label>
                        <input type="text" id="bcaAccount" name="bcaAccount" placeholder="Masukkan nomor rekening BCA">
                    </div>
                    <div class="form-group">
                        <label for="bcaProof">Bukti Pembayaran (foto):</label>
                        <input type="file" id="bcaProof" name="bcaProof">
                    </div>
                </div>
                <div id="danaFields" style="display:none;">
                    <div class="form-group">
                        <label for="danaAccount">Nomor Telepon DANA Anda:</label>
                        <input type="tel" id="danaAccount" name="danaAccount" placeholder="Masukkan nomor telepon DANA">
                    </div>
                    <div class="form-group">
                        <label for="danaProof">Bukti Pembayaran:</label>
                        <input type="file" id="danaProof" name="danaProof">
                    </div>
                </div>
                <div id="codFields" style="display:none;">
                    <p>Proses pembayaran akan dilakukan secara tunai saat barang diterima.</p>
                </div>
                <div class="form-group">
                    <button type="submit">Proses</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function showPaymentInstructions() {
            const paymentMethod = document.getElementById("paymentMethod").value;
            const bcaFields = document.getElementById("bcaFields");
            const danaFields = document.getElementById("danaFields");
            const codFields = document.getElementById("codFields");
            const paymentInstructions = document.getElementById("paymentInstructions");

            bcaFields.style.display = 'none';
            danaFields.style.display = 'none';
            codFields.style.display = 'none';

            let instructions = "";
            switch (paymentMethod) {
                case "BCA":
                    bcaFields.style.display = 'block';
                    instructions = "Silakan transfer ke rekening BCA 6630975164 a.n. Rizky Alif Ichwanto.";
                    break;
                case "DANA":
                    danaFields.style.display = 'block';
                    instructions = "Silakan transfer ke DANA 081290040388 a.n. Rizky Alif Ichwanto.";
                    break;
                case "COD":
                    codFields.style.display = 'block';
                    instructions = "Proses pembayaran akan dilakukan secara tunai saat barang diterima.";
                    break;
                default:
                    paymentInstructions.style.display = 'none';
                    return;
            }
            paymentInstructions.textContent = instructions;
            paymentInstructions.style.display = 'block';
        }
    </script>
</body>

</html>