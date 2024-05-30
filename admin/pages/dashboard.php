<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>   
    <link rel="stylesheet" type="text/css" href="../public/css/dashboard.css">
    
</head>
<body>
  
<?php include('../includes/sidebar.php'); ?>

    <div class="content">
        <h1>Welcome To POS System</h1>
    </div>
</body>
</html>
