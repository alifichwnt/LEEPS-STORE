<?php
session_start();
include 'config.php';
include 'function.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$user = getUserData($conn, $email);
$title = 'DASHBOARD ADMIN - LEEPS STORE';
$total_orders = getTotalOrderCount($conn);
$total_product = getProductCount($conn);
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
    <title><?php echo $title; ?></title>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">AdminLeeps</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
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
            <li>
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

    <!-- CONTENT -->
    <section id="content">
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-calendar-check'></i>
                    <span class="text">
                        <h3><?php echo $total_orders; ?></h3>
                        <p>Total Order</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">
                        <h3><?php echo $total_product; ?></h3>
                        <p>Products</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-dollar-circle'></i>
                    <span class="text">
                        <h3>$2543</h3>
                        <p>Total Sales</p>
                    </span>
                </li>
            </ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Recent Products</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Nama</th>
                                <th>Nominal</th>
                                <th>Nomor Akun</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM order_confirm ORDER BY order_id DESC LIMIT 10");

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['order_id']}</td>
                                <td>{$row['account_name']}</td>
                                <td>IDR {$row['nominal']}k</td>
                                <td>{$row['account_number']}</td>
                                <td>{$row['metode']}</td>
                                <td>{$row['status']}</td>
                              </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="js/admin.js"></script>
</body>

</html>
