<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare('DELETE FROM inventory WHERE id = ?');
    $stmt->execute([$delete_id]);

    header('Location: view_inventory.php');
    exit();
}

$stmt = $pdo->query('SELECT i.id, p.name AS product_name, i.change_type, i.quantity, i.change_date
                     FROM inventory i
                     JOIN products p ON i.product_id = p.id');
$inventory_changes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Inventory</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
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
            <?php endif?>
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
                        <button type="submit" style="background-color: #5cb85c; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Delete</button>
                    </form>
                </td>
                <?php endif?>

            </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>

</html>