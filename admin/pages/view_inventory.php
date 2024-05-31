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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare('SELECT p.name FROM inventory i JOIN products p ON i.product_id = p.id WHERE i.id = ?');
    $stmt->execute([$delete_id]);
    $product = $stmt->fetch();

    $stmt = $pdo->prepare('DELETE FROM inventory WHERE id = ?');
    $stmt->execute([$delete_id]);

    if ($product) {
        $stmt = $pdo->prepare('INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())');
        $stmt->execute([$user_id, 'Product "' . htmlspecialchars($product['name']) . '" is out of stock']);
    }

    header('Location: view_inventory.php');
    exit();
}

$inventory_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $inventory_per_page;

$total_inventory_stmt = $pdo->query('SELECT COUNT(*) FROM inventory');
$total_inventory = $total_inventory_stmt->fetchColumn();
$total_pages = ceil($total_inventory / $inventory_per_page);

$stmt = $pdo->prepare('SELECT i.id, p.name AS product_name, i.change_type, i.quantity, i.change_date
                       FROM inventory i
                       JOIN products p ON i.product_id = p.id
                       LIMIT :offset, :inventory_per_page');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':inventory_per_page', $inventory_per_page, PDO::PARAM_INT);
$stmt->execute();
$inventory_changes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Inventory</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewP.css">
</head>

<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Inventory Changes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Change Type</th>
            <th>Quantity</th>
            <th>Change Date</th>
            <?php if ($role !== 'editor'): ?>
            <th>Action</th>
            <?php endif ?>
        </tr>
        <?php foreach ($inventory_changes as $change) : ?>
            <tr>
                <td><?php echo htmlspecialchars($change['id']); ?></td>
                <td><?php echo htmlspecialchars($change['product_name']); ?></td>
                <td><?php echo htmlspecialchars($change['change_type']); ?></td>
                <td><?php echo htmlspecialchars($change['quantity']); ?></td>
                <td><?php echo htmlspecialchars($change['change_date']); ?></td>
                <?php if ($role !== 'editor'): ?>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="delete_id" value="<?php echo $change['id']; ?>">
                        <button type="submit" style="background-color: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Delete</button>
                    </form>
                </td>
                <?php endif ?>
            </tr>
        <?php endforeach; ?>
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
