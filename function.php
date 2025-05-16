<?php
function is_login()
{
    return isset($_SESSION['id']);
}
function getProductCount($conn)
{
    $sql = "SELECT COUNT(*) as count FROM products";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
function getPaidOrderCount($conn)
{
    $sql = "SELECT COUNT(*) as count FROM orders WHERE status = 'paid'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
function getDeliverOrderCount($conn)
{
    $sql = "SELECT COUNT(*) as count FROM orders WHERE status = 'delivered'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
function getCancelOrderCount($conn)
{
    $sql = "SELECT COUNT(*) as count FROM orders WHERE status = 'cancel'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
function getTotalOrderCount($conn)
{
    $sql = "SELECT COUNT(*) as count FROM orders";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
function getUserData($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getAllProduct($conn)
{
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

function getProduct($conn, $id)
{
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getProductPhotos($conn, $id)
{
    $sql = "SELECT * FROM products_photo WHERE product_id = $id";
    $result = $conn->query($sql);

    $photos = [];
    while ($row = $result->fetch_assoc()) {
        $photos[] = $row;
    }
    return $photos;
}

function insertProduct($conn, $data)
{
    $sql = "INSERT INTO products (name, description, price, stock) VALUES ('" . $data['name'] . "', '" . $data['description'] . "', '" . $data['price'] . "', '" . $data['stock'] . "')";
    return $conn->query($sql);
}

function updateProduct($conn, $id, $data)
{
    $sql = "UPDATE products SET name = '" . $data['name'] . "', description = '" . $data['description'] . "', price = '" . $data['price'] . "', stock = '" . $data['stock'] . "' WHERE id = $id";
    return $conn->query($sql);
}

function deleteProduct($conn, $id)
{
    $sql = "DELETE FROM products WHERE id = $id";
    return $conn->query($sql);
}
function getProductById($id, $conn)
{
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
function addToCart($data, $conn)
{
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, qty, subtotal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $data['user_id'], $data['product_id'], $data['qty'], $data['subtotal']);
    $stmt->execute();
}

function showCart($user_id, $conn)
{
    $stmt = $conn->prepare("SELECT cart.*, products.name, products.price, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function deleteCart($id, $conn)
{
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->affected_rows;
}
function getCartItem($user_id, $product_id, $conn)
{
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
//payment
function getCart($conn, $id)
{
    $query = "SELECT cart.id, cart.qty, cart.subtotal, products.name, products.price, products.image
              FROM cart
              JOIN products ON cart.product_id = products.id
              WHERE cart.user_id = $id";
    $result = $conn->query($query);
    $cart = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart[] = $row;
        }
    }
    return $cart;
}



function total($conn, $id)
{
    $query = "SELECT SUM(subtotal) AS total FROM cart WHERE user_id = $id";
    $result = $conn->query($query);
    $total = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }
    return $total;
}

function insertOrder($conn, $data)
{
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $query = "INSERT INTO orders ($columns) VALUES ($values)";
    $conn->query($query);
    return $conn->insert_id;
}

function getCartByIdUser($conn, $id)
{
    $query = "SELECT * FROM cart WHERE user_id = $id";
    $result = $conn->query($query);
    $cart = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart[] = $row;
        }
    }
    return $cart;
}

function insertOrdersDetail($conn, $data)
{
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $query = "INSERT INTO orders_detail ($columns) VALUES ($values)";
    $conn->query($query);
}

function deleteCartPayment($conn, $id)
{
    $query = "DELETE FROM cart WHERE user_id = $id";
    $conn->query($query);
}
