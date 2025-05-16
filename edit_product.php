<?php
include 'config.php';
// Proses update produk ke database saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];
    $target_dir = "assets/img/upload/";
    $target_file = $target_dir . basename($_FILES['image']['name']);

    $id_kategori = 0; // Default value
    switch ($category) {
        case 'material':
            $id_kategori = 1;
            break;
        case 'k3':
            $id_kategori = 2;
            break;
        case 'alat_berat':
            $id_kategori = 3;
            break;
        default:
            $id_kategori = 1; // Kategori default jika tidak ada yang cocok
            break;
    }

    // Check if user uploaded a new image
    if (!empty($image)) {
        // Move uploaded file to designated folder
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Update record in database with new image
            $sql = "UPDATE products SET name = '$name', price = '$price', category = '$category', image = '$image', id_kategori ='$id_kategori' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                // Remove old image from server
                unlink($target_dir . $old_image);
                echo "<script>alert('Product updated successfully');</script>";
                echo "<script>window.location.href = 'product.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "<script>alert('Failed to upload image');</script>";
        }
    } else {
        // Update record in database without changing the image
        $sql = "UPDATE products SET name = '$name', price = '$price', category = '$category', id_kategori ='$id_kategori' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product updated successfully');</script>";
            echo "<script>window.location.href = 'product.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
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
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="#" class="logout">
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
                        <h2>Edit Product</h2>
                    </div>
                    <div class="col float-right">
                        <a href="product.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <?php
                        $id = $_GET['id'];
                        $sql = "SELECT * FROM products WHERE id = $id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                        ?>
                            <form action="edit_product.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                                <!-- Form fields -->
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input type="number" step="any" class="form-control" id="price" name="price" value="<?= $row['price'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="material" <?php echo ($row['category'] === 'material') ? 'selected' : ''; ?>>Material</option>
                                        <option value="k3" <?php echo ($row['category'] === 'k3') ? 'selected' : ''; ?>>K3</option>
                                        <option value="alat_berat" <?php echo ($row['category'] === 'alat_berat') ? 'selected' : ''; ?>>Alat Berat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image:</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                    <input type="hidden" name="old_image" value="<?= $row['image'] ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        <?php
                        } else {
                            echo "Product not found";
                        }
                        ?>
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