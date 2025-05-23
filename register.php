<?php
require_once 'includes/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : [];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $result = registerUser($first_name, $last_name, $email, $phone, $hobbies, $password, $confirm_password);
    if ($result === true) {
        header("Location: login.php");
        exit();
    } else {
        $errors = $result;
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Register</h2>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form method="POST" action="">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Hobbies</label><br>
        <input type="checkbox" name="hobbies[]" value="Playing"> Playing
        <input type="checkbox" name="hobbies[]" value="Singing"> Singing
        <input type="checkbox" name="hobbies[]" value="Learning"> Learning
        <input type="checkbox" name="hobbies[]" value="Reading"> Reading
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>
<?php include 'includes/footer.php'; ?>