<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>   
     <link rel="stylesheet" type="text/css" href="../public/css/dashboard.css">

</head>
<body>
    <h1>Welcome To POS System</h1>
    <a href="logout.php">Logout</a>
    <h2>Manage Products</h2>
    <a href="add_product.php">Add New Product</a>
    <a href="view_products.php">View Products</a>
    <h2>Sales</h2>
    <a href="add_sale.php">Add Sale</a>
    <a href="view_sales.php">View Sales</a>
    <h2>Inventory</h2>
    <a href="add_inventory_change.php">Add Inventory</a>
    <a href="view_inventory.php">View Inventory</a>
</body>
</html>
