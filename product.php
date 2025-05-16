<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <li class="active">
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
    <section id="content">


        <!-- MAIN -->
        <main>
            <div class="container">
                <div class="row mt-4 mb-3">
                    <div class="col-11">
                        <h2>Product List</h2>
                    </div>
                    <div class="col float-right">
                        <a href="add_product.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </div>
                </div>
                <div class="table-data">
                    <div class="order">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'config.php';
                                // Query untuk mengambil data produk dari database
                                $sql = "SELECT * FROM products";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['price'] . "</td>";
                                        echo "<td>" . $row['category'] . "</td>";;
                                        echo "<td><img src='assets/img/upload/" . $row['image'] . "' width='100'></td>";
                                        echo "<td>";
                                        echo "<a href='edit_product.php?id=" . $row['id'] . "' class='status completed'><i class='fas fa-pencil-alt'></i></a>";
                                        echo "<a href='delete_product.php?id=" . $row['id'] . "' class='status pending' onclick='return confirm(\"Are you sure?\");'><i class='fas fa-trash-alt'></i></a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No products found</td></tr>";
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
    <!-- CONTENT -->


    <script src="js/admin.js"></script>
</body>

</html>