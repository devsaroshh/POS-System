<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // User registration
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Default role for new users
        $role = 'editor';

        // Add the user to the database
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        if ($stmt->execute([$username, $password, $role])) {
            // User registered successfully
            $_SESSION['success_message'] = 'User registered successfully.';
        } else {
            // Error registering user
            $_SESSION['error_message'] = 'Failed to register user. Please try again.';
        }
    } elseif (isset($_POST['delete'])) {
        // Delete user
        $user_id = $_POST['user_id'];
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        if ($stmt->execute([$user_id])) {
            $_SESSION['success_message'] = 'User deleted successfully.';
        } else {
            $_SESSION['error_message'] = 'Failed to delete user. Please try again.';
        }
    } else {
        // Assign permission to editor if permission is valid and user is not an admin
        $editor_id = $_POST['editor_id'];
        $permission = $_POST['permission'];

        if ($permission !== 'admin' && $permission !== 'editor') {
            $stmt = $pdo->prepare('UPDATE users SET permission = ? WHERE id = ?');
            if ($stmt->execute([$permission, $editor_id])) {
                // Permission assigned successfully
                $_SESSION['success_message'] = 'Permission assigned successfully.';
            } else {
                // Error assigning permission
                $_SESSION['error_message'] = 'Failed to assign permission. Please try again.';
            }
        } else {
            $_SESSION['error_message'] = 'Admin permission cannot be assigned.';
        }
    }

    // Redirect back to the same page
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination logic
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Fetch users from the database with pagination
$stmt = $pdo->prepare('SELECT * FROM users LIMIT :limit OFFSET :offset');
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Count total number of users for pagination
$total_users_stmt = $pdo->query('SELECT COUNT(*) FROM users');
$total_users = $total_users_stmt->fetchColumn();
$total_pages = ceil($total_users / $limit);
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" type="text/css" href="../public/css/editProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/viewProduct.css">
    <link rel="stylesheet" type="text/css" href="../public/css/pagination.css">
</head>

<body>
    <?php include('../includes/sidebar.php'); ?>
    <div class="content">
        <h2>User Management</h2>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message"><?php echo $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Permission</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <?php if ($user['role'] !== 'admin'): ?> 
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo $user['permission']; ?></td>
                        <td>
                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input style=" background-color: #6BA9B9; color: white; padding: 10px 20px; border: none;border-radius: 4px;cursor: pointer; transition: background-color 0.3s, color 0.3s;" type="submit" name="delete" value="Delete">
                            </form>
                            <br>
                            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="editor_id" value="<?php echo $user['id']; ?>">
                                <select  style="   width: 30%; padding: 8px; margin-bottom: 15px;border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;resize: vertical;" name="permission">
                                    <option value="edit">Edit</option>
                                    <option value="view">View</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit">Assign Permission</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>

        <!-- Pagination links -->
        <div class="pagination">
            <?php if ($total_pages > 1): ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

       
        <h2>Register New User</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="username">Username:</label>
            <input  style="width: 50%;padding: 8px; margin-bottom: 15px;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;resize: vertical;"  type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input style="width: 50%;padding: 8px; margin-bottom: 15px;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;resize: vertical;"  type="password" id="password" name="password" required>
            <br>
            <input type="hidden" name="register" value="1">
            <br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>
