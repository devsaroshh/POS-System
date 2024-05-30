<?php
session_start();
include('admin/includes/db.php');

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$products_in_cart = [];
$total_price = 0;

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products_in_cart = $stmt->fetchAll();

    foreach ($products_in_cart as $product) {
        $total_price += $product['price'] * $cart[$product['id']];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php include('includes/navbar.php'); // Include the navbar ?>

    <div class="container">
        <h2>Cart</h2>
        <?php if (empty($cart)): ?>
            <p>Your cart is empty. <a href="index.php">Go back to products</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th> <!-- New column for Remove button -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products_in_cart as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($cart[$product['id']]); ?></td>
                            <td>$<?php echo htmlspecialchars($product['price'] * $cart[$product['id']]); ?></td>
                            <td>
                                <form method="POST" action="remove_from_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td>$<?php echo htmlspecialchars($total_price); ?></td>
                        <td></td> <!-- Empty column for alignment -->
                    </tr>
                </tfoot>
            </table>
            <form method="POST" action="checkout.php">
                <button type="submit">Checkout</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
