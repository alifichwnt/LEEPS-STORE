<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE order_confirm SET status = ? WHERE order_id = ?");
    $stmt->bind_param('ss', $status, $order_id);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/admin.css">

    <title>DASHBOARD ADMIN - LEEPS STORE</title>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">AdminLeeps</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="admin.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="product.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Produk</span>
                </a>
            </li>
            <li class="active">
                <a href="order.php">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Orders</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout_action.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <section id="content">
        <!-- MAIN -->
        <main>
            <div class="container">
                <h1>Manage Orders</h1>
                <div class="table-data">
                    <div class="order-management">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Nama</th>
                                    <th>Nominal</th>
                                    <th>Nomor Akun</th>
                                    <th>Metode Bayar</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM order_confirm");

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['account_name']}</td>
                                <td>IDR {$row['nominal']}k</td>
                                <td>{$row['account_number']}</td>
                                <td>{$row['metode']}</td>
                                <td>{$row['status']}</td>
                                
                                <td>
                                    <form action='order.php' method='POST'>
                                        <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                        <select name='status'>
                                            <option value='waiting' " . ($row['status'] === 'waiting' ? 'selected' : '') . ">Waiting</option>
                                            <option value='paid' " . ($row['status'] === 'paid' ? 'selected' : '') . ">Paid</option>
                                            <option value='delivered' " . ($row['status'] === 'delivered' ? 'selected' : '') . ">Delivered</option>
                                            <option value='cancel' " . ($row['status'] === 'cancel' ? 'selected' : '') . ">Cancel</option>
                                        </select>
                                        <button type='submit'>Update</button>
                                    </form>
                                </td>
                              </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>

    <script src="js/admin.js"></script>
</body>

</html>