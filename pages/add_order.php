<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch products to populate the dropdown
$stmt = $pdo->query('SELECT id, name, price FROM products');
$products = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product price
    $stmt = $pdo->prepare('SELECT price FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $total_price = $product['price'] * $quantity;

        $stmt = $pdo->prepare('INSERT INTO orders (product_id, quantity, total_price) VALUES (?, ?, ?)');
        $stmt->execute([$product_id, $quantity, $total_price]);

        header('Location: view_orders.php');
        exit();
    } else {
        echo "Invalid product selected.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Order</title>
    <link rel="stylesheet" type="text/css" href="../public/css/editProduct.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h2>Add New Order</h2>
    <form method="POST">
        <label style="color:#6BA9B9;"  for="product_id">Product</label>
        <select style="   width: 100%; padding: 8px; margin-bottom: 15px;border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;resize: vertical;" name="product_id" id="product_id" required>
            <option  value="">Select Product</option>
            <?php foreach ($products as $product): ?>
                <option value="<?php echo htmlspecialchars($product['id']); ?>">
                    <?php echo htmlspecialchars($product['name']); ?> - $<?php echo htmlspecialchars($product['price']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" min="1" required>

        <button type="submit">Add Order</button>
    </form>
</div>
</body>
</html>
