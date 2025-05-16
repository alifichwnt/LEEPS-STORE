<?php
include 'config.php';
session_start();

// Menangani input dari form pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM products";
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$conditions = [];
if ($category !== 'all') {
    switch ($category) {
        case 'material':
            $conditions[] = "id_kategori = 1";
            break;
        case 'k3':
            $conditions[] = "id_kategori = 2";
            break;
        case 'alatberat':
            $conditions[] = "id_kategori = 3";
            break;
        default:
            break;
    }
}

if (!empty($search)) {
    $conditions[] = "name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "No products found";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <title>Daftar Produk - LEEPS STORE</title>
    <link rel="stylesheet" href="css/styles1.css">
</head>

<body class="showCart">
    <div class="container">
        <header>
            <div class="title">DAFTAR PRODUK</div>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Cari nama produk" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <select id="categoryFilter">
                <option value="all" <?php if ($category === 'all') echo 'selected'; ?>>ALL</option>
                <option value="material" <?php if ($category === 'material') echo 'selected'; ?>>MATERIAL</option>
                <option value="k3" <?php if ($category === 'k3') echo 'selected'; ?>>K3</option>
                <option value="alatberat" <?php if ($category === 'alatberat') echo 'selected'; ?>>ALAT BERAT</option>
            </select>

            <div class="icon-cart">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1" />
                </svg>
            </div>
        </header>
        <div class="listProduct">
            <?php foreach ($products as $product) : ?>
                <div data-id="<?= $product['id'] ?>" class="item">
                    <img src="css/image/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                    <h2><?= $product['name'] ?></h2>
                    <div class="price">IDR <?= number_format($product['price'], 0, ',', '.') ?>k</div>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Tambah ke Keranjang</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="cartTab">
        <h1>Keranjang Belanja</h1>
        <div class="listCart">
            <?php include 'get_cart_items.php'; ?>
        </div>
        <div class="btn">
            <button class="close">TUTUP</button>
            <button class="checkOut" onclick="window.location.href='checkout_action.php'">Checkout</button>
        </div>
    </div>

    <!-- Internal JS -->
    <script>
        let listProductHTML = document.querySelector('.listProduct');
        let listCartHTML = document.querySelector('.listCart');
        let iconCart = document.querySelector('.icon-cart');
        let iconCartSpan = document.querySelector('.icon-cart span');
        let body = document.querySelector('body');
        let closeCart = document.querySelector('.close');
        let searchInput = document.getElementById('searchInput');
        let categoryFilter = document.getElementById('categoryFilter');
        let products = [];
        let cart = [];
        let currentCategory = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            var categoryFilter = document.getElementById('categoryFilter');
            var searchInput = document.getElementById('searchInput');

            categoryFilter.addEventListener('change', function() {
                var category = this.value;
                var search = searchInput.value;
                window.location.href = 'catalog.php?category=' + category + '&search=' + search;
            });

            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    var search = searchInput.value;
                    var category = categoryFilter.value;
                    window.location.href = 'catalog.php?search=' + search + '&category=' + category;
                }, 300); // Adjust delay as needed
            });
        });


        iconCart.addEventListener('click', () => {
            body.classList.toggle('showCart');
        });
        closeCart.addEventListener('click', () => {
            body.classList.toggle('showCart');
        });

        const adjustProductImageSize = () => {
            const productImages = document.querySelectorAll('.listProduct .item img');
            productImages.forEach(img => {
                img.onload = () => {
                    const width = img.offsetWidth;
                    img.style.height = `${width}px`; // Menjadikan tinggi gambar sama dengan lebarnya
                };
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            listCartHTML.addEventListener('click', function(event) {
                const target = event.target;
                if (target.classList.contains('minus')) {
                    const productId = target.getAttribute('data-id');
                    updateCartItem(productId, -1); // Kurangi quantity
                }
                if (target.classList.contains('plus')) {
                    const productId = target.getAttribute('data-id');
                    updateCartItem(productId, 1); // Tambah quantity
                }
            });

            function updateCartItem(productId, change) {
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('change', change);

                fetch('update_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            refreshCart(); // Refresh daftar item di keranjang
                        } else {
                            console.error('Gagal mengupdate keranjang');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            function refreshCart() {
                fetch('get_cart_items.php')
                    .then(response => response.text())
                    .then(html => {
                        listCartHTML.innerHTML = html; // Update daftar item di keranjang
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        const initApp = () => {
            fetch('get_products.php')
                .then(response => response.json())
                .then(data => {
                    products = data;
                    addDataToHTML(products); // Ubah sesuai dengan fungsi yang akan Anda buat untuk menampilkan data produk
                })
        };

        initApp();
    </script>
</body>

</html>