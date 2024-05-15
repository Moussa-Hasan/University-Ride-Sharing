<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $reserve_id = $_GET['id'];

    // Get the ride ID associated with the reservation
    $ride_id_stmt = $con->prepare("SELECT ride_id FROM reservation WHERE reservation_id = ?");
    $ride_id_stmt->bind_param("i", $reserve_id);
    $ride_id_stmt->execute();
    $ride_id_result = $ride_id_stmt->get_result();

    if ($ride_id_result->num_rows > 0) {
        $row = $ride_id_result->fetch_assoc();
        $ride_id = $row['ride_id'];

        // Delete the reservation from the table
        $delete_stmt = $con->prepare("DELETE FROM reservation WHERE reservation_id = ?");
        $delete_stmt->bind_param("i", $reserve_id);

        if ($delete_stmt->execute()) {
            // Increment seats_available value
            $increment_stmt = $con->prepare("UPDATE ride SET seats_available = seats_available + 1 WHERE ride_id = ?");
            $increment_stmt->bind_param("i", $ride_id);
            $increment_stmt->execute();

            $_SESSION['notification'] = 'Reservation Deleted Successfully!';
            header("Location: ../student.php");
            exit();
        } else {
            header("Location: ../student.php?error=1");
            exit();
        }

        // Close the prepared statement for deleting reservation
        $delete_stmt->close();
    } else {
        // Reservation not found
        $_SESSION['notification'] = 'Reservation not found';
        header("Location: ../student.php");
        exit();
    }

    // Close the prepared statement for retrieving ride ID
    $ride_id_stmt->close();
}

// Close the database connection
$con->close();
