<?php
session_start();

// Check if the necessary session variables are not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'driver') {

    header("Location: ../../login.php");
    exit();
}

$driver_id = $_SESSION["user_id"];
$driver_name = $_SESSION['name'];
$driver_role = $_SESSION['user_role'];

if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    unset($_SESSION['notification']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Page</title>
    <link rel="stylesheet" href="../../dist/output.css">
    <!-- flowbit js  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.css" rel="stylesheet" />
    <!-- daisy ui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.17/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
</head>

<body>

    <!-- Navigation bar -->
    <?php include '../driver/includes/header.php'; ?>

    <!-- success alert -->
    <?php if (isset($notification)) : ?>
        <div id="alert" class="fixed top-16 left-0 right-0 flex items-center justify-center z-50">
            <div class="flex items-center p-4 text-green-800 rounded-lg bg-green-50 max-w-sm" role="alert">
                <div class="ms-3 text-sm font-medium">
                    <?php echo $notification; ?>
                </div>
            </div>
        </div>

        <script>
            setTimeout(function() {
                document.getElementById('alert').remove();
            }, 3000);
        </script>
    <?php endif; ?>

    <!-- Confirmation modal for logout -->
    <!-- Confirmation modal for logout -->
    <?php include '../../actions/logout_modal.php'; ?>

    <!-- Confirmation modal for delete -->
    <div id="confirmation-modal_delete" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full">
        <div class="relative p-4 w-full max-w-md h-full mx-auto flex justify-center items-center">
            <div class="relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
                <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" onclick="cancelDelete()">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <svg class="text-gray-400 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <p class="mb-4 text-gray-500">Are you sure you want to unset this ride for this student?</p>
                <div class="flex justify-center items-center space-x-4">
                    <button onclick="cancelDelete()" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10">
                        No, cancel
                    </button>
                    <button onclick="Delete()" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">
                        Yes, I'm sure
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations table  -->
    <?php

    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $reserve_result = null;

    // Retrieve the ride ID where driver_id matches and status is "undone"
    $ride_sql = "SELECT ride_id FROM ride WHERE driver_id = $driver_id AND status = 'undone'";
    $ride_result = $con->query($ride_sql);

    if ($ride_result->num_rows > 0) {
        $ride_row = $ride_result->fetch_assoc();
        $ride_id = $ride_row['ride_id'];

        // Retrieve data from the (reservation) table for the specific ride ID
        $reserve_sql = "SELECT r.*, s.name 
        FROM reservation r
        JOIN student s ON r.student_id = s.student_id
        WHERE r.ride_id = $ride_id";
        $reserve_result = $con->query($reserve_sql);
    }

    // Close the database connection
    $con->close();

    ?>

    <h1 class="text-3xl text-center p-4 my-8 text-gray-800">List of students in my ride</h1>
    <div class="flex justify-center flex-wrap gap-4">
        <?php
        // Loop through the retrieved data and display it in cards
        if ($reserve_result !== null && $reserve_result->num_rows > 0) {
            while ($row = $reserve_result->fetch_assoc()) {
                echo '<div class="card w-96 glass max-w-xs">';
                echo '<div class="px-6 py-4">';
                echo '<label class="text-gray-800 text-xs uppercase font-semibold">Reservation ID</label>';
                echo '<p class="text-gray-600 text-base">' . $row['reservation_id'] . '</p>';
                echo '<label class="text-gray-800 text-xs uppercase font-semibold">Student Name</label>';
                echo '<p class="text-gray-600 text-base">' . $row['name'] . '</p>';
                echo '<label class="text-gray-800 text-xs uppercase font-semibold">Reservation Date</label>';
                echo '<p class="text-gray-600 text-base">' . $row['reservation_date'] . '</p>';
                echo '<label class="text-gray-800 text-xs uppercase font-semibold">Ride ID</label>';
                echo '<p class="text-gray-600 text-base">' . $row['ride_id'] . '</p>';
                echo '</div>';
                echo '<div class="px-6 py-4">';
                echo '<a href="actions/chat.php?id=' . $row['student_id'] . '" class="btn btn-sm mr-4 text-white bg-blue-600 hover:bg-blue-400">Open Chat</a>';
                echo '<a href="#" onclick="confirmDelete(' . $row['reservation_id'] . ')" class="btn btn-sm text-white bg-red-600 hover:bg-red-400">Delete</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-gray-900 text-base">No data found</p>';
        }
        ?>
    </div>


    <!-- flowbit js  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
    <!-- daisy ui -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function logout() {
            // Perform the logout action
            window.location.href = "../../actions/logout.php";
        }

        var reserveIdToDelete; // Variable to store the reserve ID

        function confirmDelete(reserveId) {
            // Show the confirmation modal
            document.getElementById("confirmation-modal_delete").classList.remove("hidden");

            // Store the reserve ID in a variable
            reserveIdToDelete = reserveId;
        }

        function Delete() {
            // Check if a reserve ID is stored
            if (reserveIdToDelete) {
                // Perform the delete action with the stored reserve ID
                window.location.href = "actions/delete_reserve.php?id=" + reserveIdToDelete;
            } else {
                // Handle the case where no reserve ID is available
                console.log("No reserve ID available for deletion.");
            }
        }

        function cancelDelete() {
            // Hide the confirmation modal
            document.getElementById("confirmation-modal_delete").classList.add("hidden");
        }
    </script>

</body>

</html>