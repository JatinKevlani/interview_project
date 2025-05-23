<?php
require_once 'db.php';

function registerUser($first_name, $last_name, $email, $phone, $hobbies, $password, $confirm_password, $role = 'user') {
    global $conn;
    $errors = [];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($hobbies) || empty($password)) {
        $errors[] = "All fields are required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if (!in_array($role, ['admin', 'user'])) {
        $errors[] = "Invalid role selected.";
    }

    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    if ($check_email->get_result()->num_rows > 0) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hobbies_str = implode(",", $hobbies);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, hobbies, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone, $hobbies_str, $hashed_password, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            $errors[] = "Registration failed.";
        }
    }
    return $errors;
}

function loginUser($email, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, first_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            return true;
        }
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: ../login.php");
        exit();
    }
}
?>