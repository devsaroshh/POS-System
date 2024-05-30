<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .sidebar {
            height: 100%;
            width: 250px; 
            position: fixed;
            top: 0;
            left: 0;
            background-color:  #6BA9B9;
            padding-top: 20px;
            overflow-y: auto; 
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: #6BA9B9; 
            display: block;
            background-color: #6BA9B9; 
        }

        .sidebar a:hover,
        .sidebar a:focus {
            background-color: #6BA9B9; 
            color: white; 
        }

        .sidebar a:active {
            background-color: #6BA9B9; 
            color: white; 
        }

        .content {
            margin-left: 250px; 
            padding: 16px;
        }

        .dropbtn {
            background-color: #6BA9B9; 
            color: white; 
            padding: 16px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            width: 100%; 
            text-align: left; /* Align text to the left */
        }

        .dropdown {
            position: relative;
            display: block; /* Make dropdown a block element */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            width: 100%; 
            text-align: left; 
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: gray;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        #logout{
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;

        }
    </style>
</head>
<body>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

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

    if ($role === 'admin') { ?>

        <div class="sidebar">
            <h2 style="color: white; text-align:center">Welcome</h2>
            <div class="dropdown">
                <button class="dropbtn">User Management</button>
                <div class="dropdown-content">
                    <a href="userManagement.php">Add User</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Manage Products</button>
                <div class="dropdown-content">
                    <a href="add_product.php">Add New Product</a>
                    <a href="view_products.php">View Products</a>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="dropbtn">Sales</button>
                <div class="dropdown-content">
                    <a href="add_sale.php">Add Sale</a>
                    <a href="view_sales.php">View Sales</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Inventory</button>
                <div class="dropdown-content">
                    <a href="add_inventory_change.php">Add Inventory</a>
                    <a href="view_inventory.php">View Inventory</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    <a href="add_category.php">Add New Category</a>
                    <a href="view_categories.php">View Categories</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Subcategories</button>
                <div class="dropdown-content">
                    <a href="add_subcategory.php">Add New Subcategory</a>
                    <a href="view_subcategories.php">View Subcategories</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Manage Orders</button>
                <div class="dropdown-content">
                <!-- <a href="add_order.php">Add Order</a> -->
                    <a href="view_orders.php">View Orders</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Alerts</button>
                <div class="dropdown-content">
                    <a href="view_orders.php">View Orders</a>
                </div>
            </div>
            <a href="logout.php" id="logout">Logout</a>
        </div>
    <?php } elseif ($role === 'editor') { ?>
        <div class="sidebar">
            <h2 style="color: white; text-align:center">Welcome</h2>
            <div class="dropdown">
                <button class="dropbtn">Manage Products</button>
                <div class="dropdown-content">
                    <a href="view_products.php">Edit Products</a>
                    <!-- <a href="view_products.php">View Products</a> -->
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Manage Orders</button>
                <div class="dropdown-content">
                <!-- <a href="add_order.php">Add Order</a> -->
                    <a href="view_orders.php">Edit Orders</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Sales</button>
                <div class="dropdown-content">
                    <a href="add_sale.php">Add Sale</a>
                    <a href="view_sales.php">View Sales</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Inventory</button>
                <div class="dropdown-content">
                    <a href="add_inventory_change.php">Add Inventory</a>
                    <a href="view_inventory.php">View Inventory</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    <!-- <a href="add_category.php">Edit Category</a> -->
                    <a href="view_categories.php">Edit Category</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Subcategories</button>
                <div class="dropdown-content">
                    <!-- <a href="edit_subcategory.php">Edit Subcategory</a> -->
                    <a href="view_subcategories.php">Edit Subcategory</a>
                </div>
            </div>
            <a href="logout.php" id="logout">Logout</a>
        </div>
    <?php } ?>
</body>

</html>
