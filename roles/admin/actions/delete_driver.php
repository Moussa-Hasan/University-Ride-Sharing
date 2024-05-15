<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $driver_id = $_GET['id'];

    // Delete the driver from the table
    $delete_stmt = $con->prepare("DELETE FROM driver WHERE driver_id = ?");
    $delete_stmt->bind_param("i", $driver_id);

    if ($delete_stmt->execute()) {
        $_SESSION['notification'] = 'Drivers Deleted Successfully!';
        header("Location: ../drivers_managment.php");
    } else {

        header("Location: ../drivers_managment.php?error=1");
    }

    // Close the prepared statement
    $delete_stmt->close();
}

// Close the database connection
$con->close();
