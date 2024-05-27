<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare('INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $description, $price, $stock]);

    header('Location: view_products.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="../public/css/addP.css">

</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form method="POST" action="add_product.php">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea>
            <br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required>
            <br>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
            <br>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
