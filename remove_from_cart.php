<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['success_message'] = 'Product removed from cart successfully.';
    } else {
        $_SESSION['error_message'] = 'Product not found in cart.';
    }
} else {
    $_SESSION['error_message'] = 'Invalid request.';
}
header('Location: cart.php');
exit();
?>
