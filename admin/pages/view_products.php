<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    if (isset($_POST['delete_option']) && $_POST['delete_option'] === 'whole') {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('DELETE FROM inventory WHERE product_id = ?');
            $stmt->execute([$delete_id]);

            $stmt = $pdo->prepare('DELETE FROM sales WHERE product_id = ?');
            $stmt->execute([$delete_id]);

            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$delete_id]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed to delete product: " . $e->getMessage();
            exit();
        }
    } elseif (isset($_POST['delete_option']) && $_POST['delete_option'] === 'quantity') {
        if (!isset($_POST['quantity']) || !is_numeric($_POST['quantity'])) {
            echo "Invalid quantity specified.";
            exit();
        }

        $delete_quantity = intval($_POST['quantity']);

        $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
        $stmt->execute([$delete_id]);
        $product = $stmt->fetch();

        if (!$product) {
            echo "Product not found.";
            exit();
        }

        $current_stock = $product['stock'];

        if ($delete_quantity > $current_stock) {
            echo "Cannot delete more than the current stock.";
            exit();
        }

        $new_stock = $current_stock - $delete_quantity;
        $stmt = $pdo->prepare('UPDATE products SET stock = ? WHERE id = ?');
        $stmt->execute([$new_stock, $delete_id]);
    }

    header('Location: view_products.php');
    exit();
}

// Pagination setup
$products_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

$total_products_stmt = $pdo->query('SELECT COUNT(*) FROM products');
$total_products = $total_products_stmt->fetchColumn();
$total_pages = ceil($total_products / $products_per_page);

$stmt = $pdo->prepare('
    SELECT products.*, categories.name AS category_name, subcategories.name AS subcategory_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    LEFT JOIN subcategories ON products.subcategory_id = subcategories.id
    LIMIT :offset, :products_per_page
');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':products_per_page', $products_per_page, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

if (!$products) {
    echo "No products found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products</title>
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewP.css">

</head>
<body>
    <?php include('../includes/sidebar.php'); ?>
    <div class="content">
        <h1>Products</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['subcategory_name']); ?></td>
                    <td>
                        <div class="button-group">
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" style="background-color: #5cb85c; color: white; text-align: center; padding: 5px 10px; border-radius: 4px;">Edit</a>
                            <br><br>
                            <?php if ($role !== 'editor') : ?>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                                    <select name="delete_option" style="width: 40%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;">
                                        <option value="whole">Delete Entire Product</option>
                                        <option value="quantity">Delete Specific Quantity</option>
                                    </select>
                                    <input type="number" name="quantity" min="0" placeholder="Quantity" style="width: 20%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;">
                                    <button type="submit" style="background-color: #6BA9B9; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s, color 0.3s;">Delete</button>
                                </form>
                            <?php endif ?>
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
