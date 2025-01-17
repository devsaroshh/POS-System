<?php
session_start();
include('../includes/db.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare('DELETE FROM sales WHERE id = ?');
    $stmt->execute([$delete_id]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination setup
$sales_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $sales_per_page;

$total_sales_stmt = $pdo->query('SELECT COUNT(*) FROM sales');
$total_sales = $total_sales_stmt->fetchColumn();
$total_pages = ceil($total_sales / $sales_per_page);

$stmt = $pdo->prepare('
    SELECT s.id, p.name AS product_name, s.quantity, s.total_price, s.sale_date 
    FROM sales s
    JOIN products p ON s.product_id = p.id
    LIMIT :offset, :sales_per_page
');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':sales_per_page', $sales_per_page, PDO::PARAM_INT);
$stmt->execute();
$sales = $stmt->fetchAll();

if (!$sales) {
    echo "No sales found.";
    exit();
}

$subtotal = 0;
foreach ($sales as $sale) {
    $subtotal += $sale['total_price'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>View Sales</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewP.css">

</head>

<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Sales</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Sale Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($sales as $sale) : ?>
            <tr>
                <td><?php echo htmlspecialchars($sale['id']); ?></td>
                <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                <td><?php echo htmlspecialchars($sale['total_price']); ?></td>
                <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="delete_id" value="<?php echo $sale['id']; ?>">
                        <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"></td>
            <td><strong>Total Earning: <?php echo htmlspecialchars($subtotal); ?></strong></td>
            <td colspan="2"></td>
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
