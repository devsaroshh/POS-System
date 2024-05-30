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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && $role !== 'editor') {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE subcategory_id = ?');
    $stmt->execute([$delete_id]);
    $products_count = $stmt->fetchColumn();

    if ($products_count > 0) {
        echo "Cannot delete subcategory. There are associated products.";
        exit();
    }

    // Proceed with deletion if there are no associated products
    $stmt = $pdo->prepare('DELETE FROM subcategories WHERE id = ?');
    $stmt->execute([$delete_id]);

    header('Location: view_subcategories.php');
    exit();
}

// Pagination setup
$subcategories_per_page = 10; // Number of subcategories to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $subcategories_per_page;

$total_subcategories_stmt = $pdo->query('SELECT COUNT(*) FROM subcategories');
$total_subcategories = $total_subcategories_stmt->fetchColumn();
$total_pages = ceil($total_subcategories / $subcategories_per_page);

$stmt = $pdo->prepare('
    SELECT subcategories.*, categories.name AS category_name
    FROM subcategories
    JOIN categories ON subcategories.category_id = categories.id
    LIMIT :offset, :subcategories_per_page
');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':subcategories_per_page', $subcategories_per_page, PDO::PARAM_INT);
$stmt->execute();
$subcategories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Subcategories</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewSubcategory.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewP.css">

</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Subcategories</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php foreach ($subcategories as $subcategory): ?>
            <tr>
                <td><?php echo htmlspecialchars($subcategory['id']); ?></td>
                <td><?php echo htmlspecialchars($subcategory['name']); ?></td>
                <td><?php echo htmlspecialchars($subcategory['category_name']); ?></td>
                <td>
                    <div class="button-group">
                        <a href="edit_subcategory.php?id=<?php echo $subcategory['id']; ?>" style="background-color: #5cb85c; color: white; text-align: center; padding: 5px 10px; border-radius: 4px;">Edit</a>
                        <?php if ($role !== 'editor'): ?>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display:inline;" id="deleteForm-<?php echo $subcategory['id']; ?>">
                            <input type="hidden" name="delete_id" value="<?php echo $subcategory['id']; ?>">
                            <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
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
