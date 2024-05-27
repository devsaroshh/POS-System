<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $change_type = $_POST['change_type'];
    $quantity = $_POST['quantity'];
    $change_date = date('Y-m-d H:i:s'); 

    $stmt = $pdo->prepare('INSERT INTO inventory (product_id, change_type, quantity, change_date) VALUES (?, ?, ?, ?)');
    $stmt->execute([$product_id, $change_type, $quantity, $change_date]);

    header('Location: view_inventory.php');
}

$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Inventory Change</title>
    <link rel="stylesheet" type="text/css" href="../public/css/addI.css">

</head>

<body>
    <div class="container">
        <h2>Add Inventory Change</h2>
        <form method="POST" action="add_inventory_change.php">
            <label for="product_id">Product:</label>
            <select id="product_id" name="product_id" required>
                <?php foreach ($products as $product) : ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="change_type">Change Type:</label>
            <select id="change_type" name="change_type" required>
                <option value="add">In</option>
                <option value="remove">Out</option>
            </select>

            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <br>
            <button type="submit">Add Inventory Change</button>
        </form>
    </div>
</body>

</html>