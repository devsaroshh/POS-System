<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?');
    $stmt->execute([$name, $description, $price, $stock, $id]);

    header('Location: view_products.php');
    exit();
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo "Product not found.";
        exit();
    }
} else {
    header('Location: view_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" type="text/css" href="../public/css/addP.css">
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" action="edit_product.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
            <br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            <br>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
            <br>
            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>
