<?php
require_once '../includes/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Initialize an array to store validation errors
    $errors = [];

    // Retrieve the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];

    // Form submission successful, store the input values in session
    $_SESSION['registration_form_values_std'] = $_POST;
    
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

    // Validate address
    if (empty($address)) {
        $errors['address'] = "Address is required.";
    } elseif (strlen($address) > 150) {
        $errors['address'] = "Address should not exceed 150 characters.";
    }

    // Validate phone
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!preg_match('/^\d{8}$/', $phone)) {
        $errors['phone'] = "Phone number should be 8 digits.";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{6,}$/', $password)) {
        $errors['password'] = "Password must have at least one uppercase and one lowercase letter, and be at least 6 characters long.";
    }

    // If there are validation errors, redirect back to the registration form with the errors
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        header("Location: ../registration_student.php");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists
    $check_query = "SELECT * FROM student WHERE email = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User already exists
        header("Location: ../registration_student.php?error=1");
        exit;
    } else {
        // Insert the form data into the database
        $insert_query = "INSERT INTO student (name, email, address, phone_number, password) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $con->prepare($insert_query);
        $insert_stmt->bind_param("sssss", $name, $email, $address, $phone, $hashedPassword);

        if ($insert_stmt->execute()) {
            // Get the ID of the user
            $user_id = $insert_stmt->insert_id;

            // Save relevant data in session
            $_SESSION['user_role'] = 'student';
            $_SESSION["user_id"] = $user_id;
            $_SESSION["name"] = $name;
            $_SESSION["email"] = $email;
            $_SESSION['notification'] = 'Registration success! Welcome ' . $_SESSION["name"] . '.';

            header("Location: ../roles/student/student.php");
            exit;
        } else {
            header("Location: ../registration_student.php?error=2");
            exit();
        }
    }
}
