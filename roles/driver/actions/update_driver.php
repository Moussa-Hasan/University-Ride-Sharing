<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $carMake = $_POST['carMake'];
    $carModel = $_POST['carModel'];
    $seatingCapacity = $_POST['seatingCapacity'];    // Initialize an array to store validation errors
    $errors = [];

    // Validate name
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match('/^[a-zA-Z ]+$/', $name)) {
        $errors['name'] = "Name should contain only letters and spaces.";
    } elseif (strlen($name) > 100) {
        $errors['name'] = "Name should not exceed 100 characters.";
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

    // Date of birth validation
    $dobTimestamp = strtotime($dob);
    $minAge = 18; // Minimum age required
    $minAgeTimestamp = strtotime("-$minAge years");
    if (empty($dobTimestamp)) {
        $errors['dob'] = "Date of birth is required.";
    } elseif ($dobTimestamp === false || $dobTimestamp > $minAgeTimestamp) {
        $errors['dob'] = "You must be at least $minAge years old.";
    }

    // Car Modal validation
    if (empty($carMake)) {
        $errors['carMake'] = "Car make is required.";
    }

    // Car model validation (optional)
    if (!empty($carModel) && strlen($carModel) > 40) {
        $errors['carModel'] = "Car model should not exceed 40 characters.";
    }

    // Seating capacity validation
    if (empty($seatingCapacity)) {
        $errors['seatingCapacity'] = "seating capacity is required.";
    } elseif (!is_numeric($seatingCapacity) || $seatingCapacity < 1 || $seatingCapacity > 12) {
        $errors['seatingCapacity'] = "Seating capacity must be a number between 1 and 12.";
    }


    // If there are validation errors, store them in a session variable and redirect back to the edit form
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        header("Location: ../setting_managment.php");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $driver_id = $_SESSION['user_id'];

    // Update the driver details in the database
    $update_stmt = $con->prepare("UPDATE driver SET name=?, address=?, phone_number=?, password=?, date_of_birth=?, car_make=?, car_model=?, seating_capacity=? WHERE driver_id = ?");
    $update_stmt->bind_param("ssssssssi", $name, $address, $phone, $hashedPassword, $dob, $carMake, $carModel, $seatingCapacity, $driver_id);

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
