<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$role = $user['role'] ?? '';

$orders_per_page = 9; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $orders_per_page;

$total_orders_stmt = $pdo->query('SELECT COUNT(*) FROM orders');
$total_orders = $total_orders_stmt->fetchColumn();
$total_pages = ceil($total_orders / $orders_per_page);

$stmt = $pdo->prepare('SELECT orders.id, products.name AS product_name, order_items.quantity, order_items.price AS total_price, orders.status, orders.created_at 
                       FROM orders 
                       JOIN order_items ON orders.id = order_items.order_id
                       JOIN products ON order_items.product_id = products.id
                       LIMIT :offset, :orders_per_page');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':orders_per_page', $orders_per_page, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Orders</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewP.css">

</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Orders</h1>
    <a href="export_orders.php" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 4px;">Export to Excel</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php 
        $totalEarned = 0; 
        foreach ($orders as $order): 
            $totalEarned += $order['total_price']; 
        ?>
            <tr>
                <td><?php echo htmlspecialchars($order['id']); ?></td>
                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td>
                    <div class="button-group">
                        <a href="edit_order.php?id=<?php echo $order['id']; ?>" style="background-color: #5cb85c; color: white; text-align: center; padding: 5px 10px; border-radius: 4px;">Edit</a>
                        <?php if ($role !== 'editor'): ?>
                        <form method="POST" action="delete_order.php" onsubmit="return confirm('Are you sure you want to delete this order?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $order['id']; ?>">
                            <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2"></td>
            <td></td>
            <td>Total Earned: $<?php echo htmlspecialchars($totalEarned); ?></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
