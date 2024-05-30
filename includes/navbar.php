<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" type="text/css" href="css/navbar.css">
<nav>
    <ul>
        <li><a href="index.php">Products</a></li>
        <li><a href="cart.php">Cart</a></li>
        <?php if (isset($_SESSION['customer_id'])): ?>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['customer_username']); ?></a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
