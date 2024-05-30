<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php include('includes/navbar.php'); // Include the navbar ?>

    <div class="container">
        <h2>Order Success</h2>
        <p>Your order has been placed successfully. <a href="index.php">Continue Shopping</a></p>
    </div>
</body>
</html>
