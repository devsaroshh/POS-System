<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $sale_date = date('Y-m-d H:i:s'); 

    $stmt = $pdo->prepare('INSERT INTO sales (product_id, quantity, total_price, sale_date) VALUES (?, ?, ?, ?)');
    $stmt->execute([$product_id, $quantity, $total_price, $sale_date]);

    header('Location: view_sales.php');
}

$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Sale</title>
    <link rel="stylesheet" type="text/css" href="../public/css/editProduct.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <div class="container">
        <h2>Add Sale</h2>
        <form method="POST" action="add_sale.php">
            <label for="product_id">Product:</label>
            <select style="   width: 100%; padding: 8px; margin-bottom: 15px;border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;resize: vertical;" id="product_id" name="product_id" required>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <br>
            <label for="total_price">Total Price:</label>
            <input type="number" step="0.01" id="total_price" name="total_price" required>
            <br>
            <button type="submit">Add Sale</button>
        </form>
    </div>
    </div>
</body>
</html>
