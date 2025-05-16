// cart.js

// Function to toggle cart visibility
const toggleCartVisibility = () => {
    document.body.classList.toggle('showCart');
}

// Function to initialize the cart
const initCart = () => {
    // Fetch products data
    fetch('product.json')
        .then(response => response.json())
        .then(data => {
            products = data;
            addProductsToHTML();

            // Get cart data from localStorage
            if (localStorage.getItem('cart')) {
                cart = JSON.parse(localStorage.getItem('cart'));
                updateCartHTML();
            }
        });
}

// Function to add products to HTML
const addProductsToHTML = () => {
    // Clear existing products
    listProductHTML.innerHTML = '';

    // Add new products
    products.forEach(product => {
        let newProduct = document.createElement('div');
        newProduct.dataset.id = product.id;
        newProduct.classList.add('item');
        newProduct.innerHTML =
            `<img src="${product.image}" alt="">
            <h2>${product.name}</h2>
            <div class="price">IDR ${product.price}k</div>
            <button class="addCart">Add To Cart</button>`;
        listProductHTML.appendChild(newProduct);
    });
}

// Function to add item to cart
const addToCart = (product_id) => {
    let positionInCart = cart.findIndex(item => item.product_id == product_id);

    if (positionInCart < 0) {
        cart.push({ product_id: product_id, quantity: 1 });
    } else {
        cart[positionInCart].quantity += 1;
    }

    updateCartHTML(); // Memperbarui tampilan keranjang setelah menambah produk
    saveCartToMemory(); // Simpan keranjang ke localStorage

    // Update span 0 in icon-cart
    iconCartSpan.innerText = calculateTotalQuantity();
}

// Function to save cart to localStorage
const saveCartToMemory = () => {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Function to calculate total quantity of products in cart
const calculateTotalQuantity = () => {
    let totalQuantity = 0;
    cart.forEach(item => {
        totalQuantity += item.quantity;
    });
    return totalQuantity;
}

// Function to update cart HTML
const updateCartHTML = () => {
    listCartHTML.innerHTML = '';
    let totalQuantity = 0;

    if (cart.length > 0) {
        cart.forEach(item => {
            totalQuantity += item.quantity;

            let product = products.find(product => product.id == item.product_id);
            let newItem = document.createElement('div');
            newItem.classList.add('item');
            newItem.dataset.id = item.product_id;
            newItem.innerHTML = `<div class="image"><img src="${product.image}"></div>
                                 <div class="name">${product.name}</div>
                                 <div class="totalPrice">IDR ${product.price * item.quantity}k</div>
                                 <div class="quantity">
                                    <span class="minus"><</span>
                                    <span>${item.quantity}</span>
                                    <span class="plus">></span>
                                 </div>`;
            listCartHTML.appendChild(newItem);
        });
    }

    iconCartSpan.innerText = totalQuantity; // Update span 0 in icon-cart
}

// Function to change quantity in cart
const changeCartQuantity = (product_id, change) => {
    updateCartItem(product_id, change); // Memanggil fungsi untuk update jumlah produk
}

// Function to update cart item quantity
const updateCartItem = (product_id, change) => {
    const formData = new FormData();
    formData.append('product_id', product_id);
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

// Function to refresh cart items
const refreshCart = () => {
    fetch('get_cart_items.php')
    .then(response => response.text())
    .then(html => {
        listCartHTML.innerHTML = html; // Update daftar item di keranjang
        updateTotalQuantity(); // Memperbarui total kuantitas di icon-cart
    })
    .catch(error => console.error('Error:', error));
}

// Function to update total quantity in icon-cart
const updateTotalQuantity = () => {
    let totalQuantity = 0;
    cart.forEach(item => {
        totalQuantity += item.quantity;
    });
    iconCartSpan.innerText = totalQuantity; // Update span 0 in icon-cart
}

// Event listener to handle plus and minus buttons in cart
listCartHTML.addEventListener('click', (event) => {
    const target = event.target;
    if (target.classList.contains('minus')) {
        const productId = target.getAttribute('data-id');
        changeCartQuantity(productId, -1); // Kurangi quantity
    }
    if (target.classList.contains('plus')) {
        const productId = target.getAttribute('data-id');
        changeCartQuantity(productId, 1); // Tambah quantity
    }
});