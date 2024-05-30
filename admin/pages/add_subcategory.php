<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];

    $stmt = $pdo->prepare('INSERT INTO subcategories (category_id, name) VALUES (?, ?)');
    $stmt->execute([$category_id, $name]);

    header('Location: view_subcategories.php');
    exit();
}

$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Subcategory</title>
    <link rel="stylesheet" type="text/css" href="../public/css/addSubcategory.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <div class="container">
        <h2>Add Subcategory</h2>
        <form method="POST" action="add_subcategory.php">
            <label for="name">Subcategory Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <button type="submit">Add Subcategory</button>
        </form>
    </div>
    </div>
</body>
</html>
