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

    // Check if there are any subcategories associated with the category
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM subcategories WHERE category_id = ?');
    $stmt->execute([$delete_id]);
    $subcategories_count = $stmt->fetchColumn();

    // Check if there are any products associated with the category
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
    $stmt->execute([$delete_id]);
    $products_count = $stmt->fetchColumn();

    if ($subcategories_count > 0 || $products_count > 0) {
        echo "Cannot delete category. There are associated subcategories or products.";
        exit();
    }

    // Proceed with deletion if there are no associated subcategories or products
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$delete_id]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to delete category: " . $e->getMessage();
        exit();
    }

    header('Location: view_categories.php');
    exit();
}

$stmt = $pdo->query('SELECT * FROM categories');
$categories = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html>
<head>
    <title>View Categories</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewCategories.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Categories</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo htmlspecialchars($category['id']); ?></td>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td>
                    <div class="button-group">
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>" style="background-color: #5cb85c; color: white; text-align: center; padding: 5px 10px; border-radius: 4px;">Edit</a>
                        <?php if ($role !== 'editor'): ?>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display:inline;" id="deleteForm-<?php echo $category['id']; ?>">
                            <input type="hidden" name="delete_id" value="<?php echo $category['id']; ?>">
                            <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
