<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
    $stmt->execute([$name, $id]);

    header('Location: view_categories.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $category = $stmt->fetch();

    if (!$category) {
        echo "Category not found";
        exit();
    }
} else {
    echo "Category ID not provided";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" type="text/css" href="../public/css/editCategory.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h2>Edit Category</h2>
    <form method="POST" action="edit_category.php">
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>
