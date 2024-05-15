<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $ride_id = $_GET['id'];

    // Begin a transaction
    $con->begin_transaction();

    try {
        // Update the ride status to "done"
        $ride_update_sql = "UPDATE ride SET status = 'done' WHERE ride_id = ?";
        $ride_update_stmt = $con->prepare($ride_update_sql);
        $ride_update_stmt->bind_param("i", $ride_id);
        $ride_update_stmt->execute();

        // Update all associated reservations as "done"
        $reservation_update_sql = "UPDATE reservation SET status = 'done' WHERE ride_id = ?";
        $reservation_update_stmt = $con->prepare($reservation_update_sql);
        $reservation_update_stmt->bind_param("i", $ride_id);
        $reservation_update_stmt->execute();

        // Commit the transaction
        $con->commit();

        $_SESSION['notification'] = 'Ride and associated reservations updated successfully!';
        header("Location: ../rides.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $con->rollback();

        header("Location: ../rides.php?error=1");
        exit();
    }
} else {
    header("Location: ../rides.php?error=1");
    exit();
}

// Close the database connection
$con->close();
