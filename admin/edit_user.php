<?php
require_once '../includes/auth.php';
redirectIfNotAdmin();

$user_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $hobbies = isset($_POST['hobbies']) ? implode(",", $_POST['hobbies']) : '';
    $role = $_POST['role'];

    if (!in_array($role, ['admin', 'user'])) {
        $errors[] = "Invalid role selected.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, hobbies = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $hobbies, $role, $user_id);
        if ($stmt->execute()) {
            $success = "User updated successfully.";
        } else {
            $errors[] = "Failed to update user.";
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<h2>Edit User</h2>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
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
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Hobbies</label><br>
        <?php $hobbies = explode(",", $user['hobbies']); ?>
        <input type="checkbox" name="hobbies[]" value="Playing" <?php if (in_array("Playing", $hobbies)) echo "checked"; ?>> Playing
        <input type="checkbox" name="hobbies[]" value="Singing" <?php if (in_array("Singing", $hobbies)) echo "checked"; ?>> Singing
        <input type="checkbox" name="hobbies[]" value="Learning" <?php if (in_array("Learning", $hobbies)) echo "checked"; ?>> Learning
        <input type="checkbox" name="hobbies[]" value="Reading" <?php if (in_array("Reading", $hobbies)) echo "checked"; ?>> Reading
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-control" id="role" name="role" required>
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update User</button>
</form>
<?php include '../includes/footer.php'; ?>