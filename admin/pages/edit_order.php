<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if (!$order) {
        echo "Order not found!";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$status, $order_id]);

    $stmt = $pdo->prepare('INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())');
    $stmt->execute([$_SESSION['user_id'], 'Order ID ' . $order_id . ' status updated to ' . $status]);

    header('Location: view_orders.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" type="text/css" href="../public/css/editProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Edit Order</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        <label style="color:#6BA9B9;" for="status">Status</label>
        <select style="width: 50%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;" name="status" id="status">
            <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>completed</option>
            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>cancelled</option>
        </select>
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>
