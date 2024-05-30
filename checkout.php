<?php
session_start();
include('admin/includes/db.php');

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_SESSION['customer_id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header('Location: index.php');
    exit();
}

$pdo->beginTransaction();

try {
    $total_price = 0;
    foreach ($cart as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
                if ($product && isset($product['price'])) {
            $total_price += $product['price'] * $quantity;
        } else {
            throw new Exception("Invalid product price for product ID: $product_id");
        }
    }
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total_price, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->execute([$customer_id, $total_price]);
    $order_id = $pdo->lastInsertId();

    foreach ($cart as $product_id => $quantity) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, (SELECT price FROM products WHERE id = ?))");
        $stmt->execute([$order_id, $product_id, $quantity, $product_id]);
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
    }

    $pdo->commit();
    unset($_SESSION['cart']);
    header('Location: order_success.php');
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed to place order: " . $e->getMessage();
}
?>
