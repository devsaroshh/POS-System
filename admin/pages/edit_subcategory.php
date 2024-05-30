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
    $stmt = $pdo->prepare('UPDATE subcategories SET name = ? WHERE id = ?');
    $stmt->execute([$name, $id]);
    header('Location: view_subcategories.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM subcategories WHERE id = ?');
    $stmt->execute([$id]);
    $subcategory = $stmt->fetch();
} else {
    header('Location: view_subcategories.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Subcategory</title>
    <link rel="stylesheet" type="text/css" href="../public/css/editCategory.css">
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
<h2>Edit Category</h2>
    <form method="POST" action="edit_subcategory.php">
        <input type="hidden" name="id" value="<?php echo $subcategory['id']; ?>">
        <label for="name">Subcategory Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($subcategory['name']); ?>" required>
        <br>
        <button type="submit">Update Subcategory</button>
    </form>
</div>
</body>
</html>
