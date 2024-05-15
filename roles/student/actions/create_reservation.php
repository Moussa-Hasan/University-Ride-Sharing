<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $ride_id = $_GET['id'];

    // Save the session
    $student_id = $_SESSION['user_id'];

    // Check if there is already a reservation
    $check_stmt = $con->prepare("SELECT * FROM reservation WHERE student_id = ? AND status=''");
    $check_stmt->bind_param("i", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows == 0) {
        // No existing reservation

        // Get the current date and time
        $reservation_date = date('Y-m-d H:i:s');

        // Set the reserved_seat value
        $reserved_seat = 1;

        // Check if seats are available
        $ride_stmt = $con->prepare("SELECT seats_available FROM ride WHERE ride_id = ?");
        $ride_stmt->bind_param("i", $ride_id);
        $ride_stmt->execute();
        $ride_result = $ride_stmt->get_result();

        if ($ride_result->num_rows > 0) {
            $row = $ride_result->fetch_assoc();
            $seats_available = $row['seats_available'];

            if ($seats_available > 0) {
                // Seats are available, create the reservation

                $insert_stmt = $con->prepare("INSERT INTO reservation (ride_id, student_id, reservation_date, reserved_seats) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("iiss", $ride_id, $student_id, $reservation_date, $reserved_seat);

                if ($insert_stmt->execute()) {
                    // Decrement seats_available value
                    $new_seats_value = $seats_available - 1;
                    $decrement_stmt = $con->prepare("UPDATE ride SET seats_available = ? WHERE ride_id = ?");
                    $decrement_stmt->bind_param("ii", $new_seats_value, $ride_id);
                    $decrement_stmt->execute();

                    // Reservation created successfully
                    $_SESSION['notification'] = 'Reservation created successfully!';
                    header("Location: ../student.php");
                    exit();
                } else {
                    // Failed to create reservation
                    header("Location: ../reserve_a_ride.php?error=1");
                    exit();
                }

                // Close the prepared statement for reservation creation
                $insert_stmt->close();
            } else {
                // No seats available
                $_SESSION['notification'] = 'No seats available';

                header("Location: ../reserve_a_ride.php");
                exit();
            }
        } else {
            // Ride not found
            $_SESSION['notification'] = 'Ride not found';
            header("Location: ../reserve_a_ride.php");
            exit();
        }
    } else {
        // Existing reservation found
        $_SESSION['notification'] = 'You have already reserved a seat for a ride.';
        header("Location: ../reserve_a_ride.php");
        exit();
    }

    // Close the prepared statement for checking existing reservation
    $check_stmt->close();
}

// Close the database connection
$con->close();
