<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if there is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the driver's ID from the session
    $driver_id = $_SESSION['user_id'];

    // Check if the driver already has an available ride
    $existingRideQuery = "SELECT * FROM ride WHERE driver_id = ? AND status = 'undone'";
    $existingRideStmt = $con->prepare($existingRideQuery);
    $existingRideStmt->bind_param("i", $driver_id);
    $existingRideStmt->execute();
    $existingRideResult = $existingRideStmt->get_result();

    if ($existingRideResult->num_rows > 0) {
        // Driver already has an available ride
        $_SESSION['notification'] = "You already have an available ride. Cannot create another ride.";
        header("Location: ../ride_form.php");
        exit;
    }

    // Retrieve the form input values
    $start = $_POST['start'];
    $destination = $_POST['destination'];
    $seats = $_POST['seats'];
    $time = $_POST['time'];

    // Insert the ride details into the database
    $query = "INSERT INTO ride (driver_id, ride_from, ride_to, seats_available, time, status) VALUES (?, ?, ?, ?, ?, 'undone')";

    $stmt = $con->prepare($query);
    $stmt->bind_param("issss", $driver_id, $start, $destination, $seats, $time);

    if ($stmt->execute()) {
        // Ride creation successful
        $_SESSION['notification'] = "Ride set successfully.";
        header("Location: ../rides.php");
    } else {
        // Ride creation failed
        header("Location: ../ride_form.php");
        $_SESSION['notification'] = "Failed to set the ride. Please try again.";
    }

    $stmt->close();
    $existingRideStmt->close();
} else {
    // No POST request
    header("Location: ../ride_form.php");
}

// Close the database connection
$con->close();

exit;
