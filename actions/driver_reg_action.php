<?php
require_once '../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Retrieve the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $dob = $_POST["dob"];
    $carMake = $_POST["carMake"];
    $carModel = $_POST["carModel"];
    $seatingCapacity = $_POST["seatingCapacity"];

    // Form submission successful, store the input values in session
    $_SESSION['registration_form_values_drv'] = $_POST;

    // Initialize an array to store validation errors
    $errors = [];

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


    // If there are validation errors, store them in a session variable and redirect back to the registration form
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        header("Location: ../registration_driver.php");
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists
    $check_query = "SELECT * FROM driver WHERE email = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Driver already exists
        header("Location: ../registration_driver.php?error=1");
        exit;
    } else {

        // Insert the form data into the database
        $query = "INSERT INTO driver (name, email, address, phone_number, password, date_of_birth, car_make, car_model, seating_capacity, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,'available')";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssssssi", $name, $email, $address, $phone, $hashedPassword, $dob, $carMake, $carModel, $seatingCapacity);
        if ($stmt->execute()) {

            // Get the ID of the user
            $user_id = $stmt->insert_id;

            // Save relevant data in session
            $_SESSION['user_role'] = 'driver';
            $_SESSION["name"] = $name;
            $_SESSION["user_id"] = $user_id;
            $_SESSION["email"] = $email;
            $_SESSION['notification'] = 'Registration success! Welcome ' . $_SESSION["name"] . '.';

            header("Location: ../roles/driver/driver.php");
            exit;
        } else {
            header("Location: ../registration_driver.php?error=2");
            exit();
        }
    }
}
