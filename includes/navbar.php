<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

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

<style>
nav {
    background-color: #007bff;
    padding: 10px;
}

nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
}

nav ul li {
    margin: 0 10px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    display: block;
}

nav ul li a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}
</style>
