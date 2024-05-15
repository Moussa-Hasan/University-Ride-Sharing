<?php
require_once '../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize an array to store validation errors
$errors = [];

// Retrieve the form inputs
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

// Form submission successful, store the input values in session
$_SESSION['login_form_values'] = $_POST;

// Validate email
if (empty($email)) {
    $errors['email'] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
}

// Validate password
if (empty($password)) {
    $errors['password'] = "Password is required.";
}

// If there are validation errors, redirect back to the login form with the errors
if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    header("Location: ../login.php");
    exit;
}

// Prepare the student query
$studentQuery = "SELECT * FROM student WHERE email = ?";
$studentStmt = $con->prepare($studentQuery);
$studentStmt->bind_param("s", $email);
$studentStmt->execute();
$studentResult = $studentStmt->get_result();
$studentRow = $studentResult->fetch_assoc();

// Prepare the driver query
$driverQuery = "SELECT * FROM driver WHERE email = ?";
$driverStmt = $con->prepare($driverQuery);
$driverStmt->bind_param("s", $email);
$driverStmt->execute();
$driverResult = $driverStmt->get_result();
$driverRow = $driverResult->fetch_assoc();

// Prepare the admin query
$adminQuery = "SELECT * FROM admin WHERE email = ?";
$adminStmt = $con->prepare($adminQuery);
$adminStmt->bind_param("s", $email);
$adminStmt->execute();
$adminResult = $adminStmt->get_result();
$adminRow = $adminResult->fetch_assoc();

// Check if the user is a student
if ($studentRow && password_verify($password, $studentRow['password'])) {
    // Session Information 
    $_SESSION['user_role'] = 'student';
    $_SESSION['user_id'] = $studentRow['student_id'];
    $_SESSION['user_email'] = $studentRow['email'];
    $_SESSION["name"] = $studentRow['name'];
    $_SESSION['notification'] = 'Login success! Welcome ' . $_SESSION["name"] . '.';

    header("Location: ../roles/student/student.php");
    exit();
}

// Check if the user is a driver
if ($driverRow && password_verify($password, $driverRow['password'])) {
    // Session Information 
    $_SESSION['user_role'] = 'driver';
    $_SESSION['user_id'] = $driverRow['driver_id'];
    $_SESSION['user_email'] = $driverRow['email'];
    $_SESSION["name"] = $driverRow['name'];
    $_SESSION['notification'] = 'Login success! Welcome ' . $_SESSION["name"] . '.';

    header("Location: ../roles/driver/driver.php");
    exit();
}

// Check if the user is an admin
if ($adminRow && password_verify($password, $adminRow['password']) && $adminRow['status'] == 'admin') {
    // Session Information 
    $_SESSION['user_role'] = 'admin';
    $_SESSION['user_id'] = $adminRow['id'];
    $_SESSION['user_email'] = $adminRow['email'];
    $_SESSION['notification'] = 'Login success!';

    header("Location: ../roles/admin/dashboard.php");
    exit();
}

header("Location: ../login.php?error=1");
exit();
