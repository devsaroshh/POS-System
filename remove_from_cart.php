<?php
session_start();

// Check if the product ID is provided and valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Check if the product exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['success_message'] = 'Product removed from cart successfully.';
    } else {
        $_SESSION['error_message'] = 'Product not found in cart.';
    }
} else {
    $_SESSION['error_message'] = 'Invalid request.';
}

// Redirect back to the cart page
header('Location: cart.php');
exit();
?>
