<?php
require_once '../includes/auth.php';
redirectIfNotAdmin();
?>
<?php include '../includes/header.php'; ?>
<h2>Welcome, <?php echo $_SESSION['first_name']; ?>!</h2>
<p>This is the admin dashboard. Use the navigation to manage users or view your profile.</p>
<?php include '../includes/footer.php'; ?>