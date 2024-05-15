<?php
session_start();

// Check if the necessary session variables are not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'driver') {

    header("Location: ../../login.php");
    exit();
}

$driver_id = $_SESSION["user_id"];
$driver_name = $_SESSION['name'];

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
    <title>My Rides Page</title>
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

    <!-- Confirmation modal for ride done -->
    <div id="confirmation-modal_done" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full">
        <div class="relative p-4 w-full max-w-md h-full mx-auto flex justify-center items-center">
            <div class="relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
                <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" onclick="cancelDone()">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <svg class="text-gray-400 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9.414l-2.293-2.293a1 1 0 011.414-1.414l1.879 1.879 4.879-4.879a1 1 0 111.414 1.414l-5.586 5.586a1.001 1.001 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <p class="mb-4 text-gray-500">Are you sure you want to mark this ride as done?</p>
                <div class="flex justify-center items-center space-x-4">
                    <button onclick="cancelDone()" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10">
                        No, cancel
                    </button>
                    <button onclick="Done()" class="py-2 px-3 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        Yes, I'm sure
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rides table  -->
    <?php

    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $ride_result = null;

    // Retrieve the ride ID where driver_id matches and status is "undone"
    $ride_sql = "SELECT r.*, d.seating_capacity
    FROM ride r
    JOIN driver d ON r.driver_id = d.driver_id
    WHERE r.driver_id = $driver_id AND r.status = 'undone'";
    $ride_result = $con->query($ride_sql);

    // Close the database connection
    $con->close();

    ?>

    <h1 class="text-3xl text-center p-4 my-8 text-gray-800">My Rides</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="reserve" class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Ride id
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Destination
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Source
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Seats Available
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the retrieved data and display it in table rows
                if ($ride_result !== null && $ride_result->num_rows > 0) {
                    while ($row = $ride_result->fetch_assoc()) {
                        echo '<tr class="bg-white border-b">';
                        echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">';
                        echo $row['ride_id'];
                        echo '</th>';
                        echo '<td class="px-6 py-4">';
                        echo $row['ride_to'];
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo $row['ride_from'];
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        $seatingCapacity = $row['seating_capacity'];
                        $availableSeats = $row['seats_available'];

                        for ($i = 1; $i <= $seatingCapacity; $i++) {
                            if ($i <= $availableSeats) {
                                // Display a green circle for an available seat
                                echo '<div class="tooltip" data-tip="Available seats">';
                                echo '<span class="inline-block h-4 w-4 mr-1 rounded-full bg-green-500"></span>';
                            } else {
                                // Display a red circle for a reserved seat
                                echo '<div class="tooltip" data-tip="Reserved seats">';
                                echo '<span class="inline-block h-4 w-4 mr-1 rounded-full bg-red-500"></span>';
                            }
                            echo '</div>';
                        }

                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo $row['time'];
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo '<a href="#" onclick="confirmDone(' . $row['ride_id'] . ')" class="btn btn-sm mr-4 text-white bg-blue-600 hover:bg-blue-400">Done</a>';
                        echo '<a href="#" onclick="confirmDelete(' . $row['ride_id'] . ')" class="btn btn-sm text-white bg-red-600 hover:bg-red-400">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" class="px-6 py-4 text-gray-900">No data found</td></tr>';
                }
                ?>
            </tbody>
        </table>
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
                window.location.href = "actions/delete_ride.php?id=" + reserveIdToDelete;
            } else {
                // Handle the case where no reserve ID is available
                console.log("No reserve ID available for deletion.");
            }
        }

        function cancelDelete() {
            // Hide the confirmation modal
            document.getElementById("confirmation-modal_delete").classList.add("hidden");
        }


        var rideIdToConfirm; // Variable to store the ride ID

        function confirmDone(rideId) {
            // Show the confirmation modal
            document.getElementById("confirmation-modal_done").classList.remove("hidden");

            // Store the ride ID in a variable
            rideIdToConfirm = rideId;
        }

        function Done() {
            // Check if a ride ID is stored
            if (rideIdToConfirm) {
                // Perform the delete action with the stored ride ID
                window.location.href = "actions/ride_done.php?id=" + rideIdToConfirm;
            } else {
                // Handle the case where no ride ID is available
                console.log("No ride ID available for confirmation.");
            }
        }

        function cancelDone() {
            // Hide the confirmation modal
            document.getElementById("confirmation-modal_done").classList.add("hidden");
        }
    </script>

</body>

</html>