<?php
// Mulai sesi
session_start();
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
                <input type="text" id="searchInput" placeholder="Cari nama produk">
            </div>
            <select id="categoryFilter">
                <option value="all">ALL</option>
                <option value="material">MATERIAL</option>
                <option value="k3">K3</option>
                <option value="alatberat">ALAT BERAT</option>
            </select>

            <div class="icon-cart">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1" />
                </svg>
                <span>0</span>
            </div>
        </header>
        <div class="listProduct">
        </div>
    </div>
    <div class="cartTab">
        <h1>Keranjang Belanja</h1>
        <div class="listCart">
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

        categoryFilter.addEventListener('change', (event) => {
            currentCategory = event.target.value;
            filterProductsByCategory(currentCategory);
            searchProducts(); // Update search results when category changes
        });

        const filterProductsByCategory = (category) => {
            let filteredProducts = [];
            switch (category) {
                case 'material':
                    filteredProducts = products.filter(product => product.id >= 1 && product.id <= 19);
                    break;
                case 'k3':
                    filteredProducts = products.filter(product => product.id >= 20 && product.id <= 41);
                    break;
                case 'alatberat':
                    filteredProducts = products.filter(product => product.id >= 42);
                    break;
                default:
                    filteredProducts = products;
                    break;
            }
            addDataToHTML(filteredProducts);
        };

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

        const addDataToHTML = (filteredProducts = products) => {
            // Hapus produk sebelumnya
            listProductHTML.innerHTML = '';
            // Tambah produk baru
            if (filteredProducts.length > 0) {
                filteredProducts.forEach(product => {
                    let newProduct = document.createElement('div');
                    newProduct.dataset.id = product.id;
                    newProduct.classList.add('item');
                    newProduct.innerHTML =
                        `<img src="${product.image}" alt="">
                        <h2>${product.name}</h2>
                        <div class="price">IDR ${product.price}k</div>
                        <button class="addCart">Tambah ke Keranjang</button>`;
                    listProductHTML.appendChild(newProduct);
                });
            }
        };

        listProductHTML.addEventListener('click', (event) => {
            let positionClick = event.target;
            if (positionClick.classList.contains('addCart')) {
                let id_product = positionClick.parentElement.dataset.id;
                addToCart(id_product);
            }
        });

        const addToCart = (product_id) => {
            let positionThisProductInCart = cart.findIndex((value) => value.product_id == product_id);
            if (cart.length <= 0) {
                cart = [{
                    product_id: product_id,
                    quantity: 1
                }];
            } else if (positionThisProductInCart < 0) {
                cart.push({
                    product_id: product_id,
                    quantity: 1
                });
            } else {
                cart[positionThisProductInCart].quantity = cart[positionThisProductInCart].quantity + 1;
            }
            addCartToHTML();
            addCartToMemory();
        };

        const addCartToMemory = () => {
            localStorage.setItem('cart', JSON.stringify(cart));
        };

        const addCartToHTML = () => {
            listCartHTML.innerHTML = '';
            let totalQuantity = 0;
            if (cart.length > 0) {
                cart.forEach(item => {
                    totalQuantity = totalQuantity + item.quantity;
                    let newItem = document.createElement('div');
                    newItem.classList.add('item');
                    newItem.dataset.id = item.product_id;

                    let positionProduct = products.findIndex((value) => value.id == item.product_id);
                    let info = products[positionProduct];
                    listCartHTML.appendChild(newItem);
                    newItem.innerHTML = `<div class="image"><img src="${info.image}"></div>
                    <div class="name">${info.name}</div>
                    <div class="totalPrice">IDR ${info.price * item.quantity}k</div>
                    <div class="quantity">
                        <span class="minus"><</span>
                        <span>${item.quantity}</span>
                        <span class="plus">></span>
                    </div>`;
                });
            }
            iconCartSpan.innerText = totalQuantity;
        };

        listCartHTML.addEventListener('click', (event) => {
            let positionClick = event.target;
            if (positionClick.classList.contains('minus') || positionClick.classList.contains('plus')) {
                let product_id = positionClick.parentElement.parentElement.dataset.id;
                let type = 'minus';
                if (positionClick.classList.contains('plus')) {
                    type = 'plus';
                }
                changeQuantityCart(product_id, type);
            }
        });

        const changeQuantityCart = (product_id, type) => {
            let positionItemInCart = cart.findIndex((value) => value.product_id == product_id);
            if (positionItemInCart >= 0) {
                let info = cart[positionItemInCart];
                switch (type) {
                    case 'plus':
                        cart[positionItemInCart].quantity = cart[positionItemInCart].quantity + 1;
                        break;

                    default:
                        let changeQuantity = cart[positionItemInCart].quantity - 1;
                        if (changeQuantity > 0) {
                            cart[positionItemInCart].quantity = changeQuantity;
                        } else {
                            cart.splice(positionItemInCart, 1);
                        }
                        break;
                }
            }
            addCartToHTML();
            addCartToMemory();
        };

        const initApp = () => {
            // Ambil data produk
            fetch('product.json')
                .then(response => response.json())
                .then(data => {
                    products = data;
                    addDataToHTML();

                    // Ambil data keranjang dari local storage
                    if (localStorage.getItem('cart')) {
                        cart = JSON.parse(localStorage.getItem('cart'));
                        addCartToHTML();
                    }
                });
        };

        const searchProducts = () => {
            let searchTerm = searchInput.value.toLowerCase();
            let filteredProducts = products.filter(product => product.name.toLowerCase().includes(searchTerm));

            // Filter by current category
            switch (currentCategory) {
                case 'material':
                    filteredProducts = filteredProducts.filter(product => product.id >= 1 && product.id <= 19);
                    break;
                case 'k3':
                    filteredProducts = filteredProducts.filter(product => product.id >= 20 && product.id <= 41);
                    break;
                case 'alatberat':
                    filteredProducts = filteredProducts.filter(product => product.id >= 42);
                    break;
                default:
                    break;
            }

            addDataToHTML(filteredProducts);
        };

        searchInput.addEventListener('input', searchProducts);

        initApp();
    </script>
</body>

</html>