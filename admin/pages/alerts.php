<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alerts</title>
    <link rel="stylesheet" type="text/css" href="../public/css/alert.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include('../includes/sidebar.php'); ?>
<div class="content">
    <h1>Alerts</h1>
    <ul id="alert-list">
        <?php foreach ($notifications as $notification): ?>
            <li>
                <?php echo htmlspecialchars($notification['message']); ?>
                <button class="delete-btn" data-id="<?php echo $notification['id']; ?>">Delete</button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<script>
$(document).ready(function() {
    $('.delete-btn').click(function() {
        var notificationId = $(this).data('id');
        $.ajax({
            url: 'delete_notification.php',
            type: 'POST',
            data: { id: notificationId },
            success: function(response) {
                location.reload(); 
            }
        });
    });
});
</script>
</body>
</html>
