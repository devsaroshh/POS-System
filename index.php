<?php
session_start();
include('admin/includes/db.php');

$products_per_page = 9;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$offset = ($page - 1) * $products_per_page;

$total_products_stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE stock > 0");
$total_products = $total_products_stmt->fetchColumn();
$total_pages = ceil($total_products / $products_per_page);

$stmt = $pdo->prepare("SELECT * FROM products WHERE stock > 0 LIMIT :offset, :products_per_page");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':products_per_page', $products_per_page, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/paginate.css">
</head>
<body>
    <?php include('includes/navbar.php');?>

    <div class="container">
        <h2>Products</h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
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
    </div>
</body>
</html>
