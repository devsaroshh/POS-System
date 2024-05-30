<?php
session_start();
include('admin/includes/db.php');

$stmt = $pdo->query("SELECT * FROM products WHERE stock > 0");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php include('includes/navbar.php');?>

    <div class="container">
        <h2>Products</h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id"  value="<?php echo $product['id']; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" min="1" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
