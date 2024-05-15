<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $ride_id = $_GET['id'];

    // Delete the ride from the table
    $delete_stmt = $con->prepare("DELETE FROM ride WHERE ride_id = ?");
    $delete_stmt->bind_param("i", $ride_id);

    if ($delete_stmt->execute()) {
        $_SESSION['notification'] = 'Ride Deleted Successfully!';
        header("Location: ../rides_managment.php");
    } else {

        header("Location: ../rides_managment.php?error=1");
    }

    // Close the prepared statement
    $delete_stmt->close();
}

// Close the database connection
$con->close();
