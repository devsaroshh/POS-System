<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category'];
    $subcategory_id = $_POST['subcategory'] ?? null;

    $stmt = $pdo->prepare('INSERT INTO products (name, description, price, stock, category_id, subcategory_id) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $description, $price, $stock, $category_id, $subcategory_id]);

    header('Location: view_products.php');
    exit();
}

$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
$subcategories = $pdo->query('SELECT * FROM subcategories')->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
    <script>
        function updateSubcategories() {
            var categorySelect = document.getElementById("category");
            var subcategorySelect = document.getElementById("subcategory");
            var subcategoryContainer = document.getElementById("subcategory-container");
            var categoryId = categorySelect.value;
            
            subcategorySelect.innerHTML = ""; 
            subcategoryContainer.style.display = "none";

            var subcategories = <?php echo json_encode($subcategories); ?>;

            if (subcategories[categoryId] && subcategories[categoryId].length > 0) {
                subcategoryContainer.style.display = "block";

                subcategories[categoryId].forEach(function(subcategory) {
                    var option = document.createElement("option");
                    option.value = subcategory.id;
                    option.text = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateSubcategories();
        });
    </script>
</head>

<body>
    <?php include('../includes/sidebar.php'); ?>
    <div class="content" style="margin-left: 250px; padding: 20px;">
        <div class="container" style="max-width: 600px; margin: 0 auto;">
            <h2 style="color: #6BA9B9;">Add Product</h2>
            <form method="POST" action="add_product.php">
                <label for="name" style="display: block; margin-bottom: 5px; color: #6BA9B9;">Product Name:</label>
                <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; border: 1px solid #6BA9B9; border-radius: 4px; margin-bottom: 10px;">
                <br>
                <label for="description" style="display: block; margin-bottom: 5px; color: #6BA9B9;">Description:</label>
                <textarea id="description" name="description" style="width: 100%; padding: 8px; border: 1px solid #6BA9B9; border-radius: 4px; margin-bottom: 10px;"></textarea>
                <br>
                <label for="price" style="display: block; margin-bottom: 5px; color: #6BA9B9;">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required style="width: 100%; padding: 8px; border: 1px solid #6BA9B9; border-radius: 4px; margin-bottom: 10px;">
                <br>
                <label for="stock" style="display: block; margin-bottom: 5px; color: #6BA9B9;">Stock:</label>
                <input type="number" id="stock" name="stock" required style="width: 100%; padding: 8px; border: 1px solid #6BA9B9; border-radius: 4px; margin-bottom: 10px;">
                <br>
                <label for="category" style="display: block; margin-bottom: 5px; color: #6BA9B9;">Category:</label>
                <select id="category" name="category" required style="width: 100%; padding: 8px; border: 1px solid #6BA9B9; border-radius: 4px; margin-bottom: 10px;" onchange="updateSubcategories()">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <div id="subcategory-container" style="display: none;">
                    <label for="subcategory" style="display: block; margin-bottom: 5px; color: #6BA9B9;">Subcategory:</label>
                    <select id="subcategory" name="subcategory" style="width: 100%; padding: 8px; border: 1px solid #6BA9B9; border-radius: 4px; margin-bottom: 10px;">
                    </select>
                </div>
                <br>
                <button type="submit" style="background-color: #6BA9B9; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; transition: background-color 0.3s, color 0.3s;">Add Product</button>
            </form>
        </div>
    </div>
</body>

</html>
