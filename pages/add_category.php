<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
    $stmt->execute([$name]);

    header('Location: view_categories.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" type="text/css" href="../public/css/addCategory.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">

    <div class="container">
        
        <h2>Add Category</h2>
        <form method="POST" action="add_category.php">
            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button type="submit">Add Category</button>
        </form>
    </div>
    </div>
</body>
</html>
