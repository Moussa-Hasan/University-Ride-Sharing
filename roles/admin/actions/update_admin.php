<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Initialize an array to store validation errors
    $errors = [];

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate name
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match('/^[a-zA-Z ]+$/', $name)) {
        $errors['name'] = "Name should contain only letters and spaces.";
    } elseif (strlen($name) > 100) {
        $errors['name'] = "Name should not exceed 100 characters.";
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{6,}$/', $password)) {
        $errors['password'] = "Password must have at least one uppercase and one lowercase letter, and be at least 6 characters long.";
    }

    // If there are validation errors, redirect back to the edit form with the errors
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        header("Location: ../setting_managment.php");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //save the session
    $admin_id = $_SESSION['user_id'];

    // Update the admin details in the database
    $update_stmt = $con->prepare("UPDATE admin SET name=?, email=?, password=? WHERE id=?");
    $update_stmt->bind_param("ssss", $name, $email, $hashedPassword, $admin_id);

    if ($update_stmt->execute()) {

        $_SESSION['notification'] = 'Details Updated Successfully!';
        header("Location: ../setting_managment.php");
    } else {

        header("Location: ../setting_managment.php?error=1");
    }

    // Close the prepared statement
    $update_stmt->close();
}

// Close the database connection
$con->close();
