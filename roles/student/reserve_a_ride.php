<?php
session_start();

// Check if the necessary session variables are not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {

    header("Location: ../../login.php");
    exit();
}

//save the session
$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];

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
    <title>Student Page</title>
    <link rel="stylesheet" href="../../dist/output.css">
    <!-- flowbit js  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.css" rel="stylesheet" />
    <!-- daisy ui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.17/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
</head>

<body>

    <!-- Navigation bar -->
    <?php include '../student/includes/header.php'; ?>

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

    <!-- Confirmation modal for ensuring a reservation -->
    <div id="confirmation-modal_reserve" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full">
        <div class="relative p-4 w-full max-w-md h-full mx-auto flex justify-center items-center">
            <div class="relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
                <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" onclick="cancelReserve()">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <svg class="text-gray-400 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 2a8 8 0 100 16A8 8 0 0010 2zm0 14a6 6 0 110-12 6 6 0 010 12zm1-7a1 1 0 01-2 0V7.414l-1.293 1.293a1 1 0 11-1.414-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 11-1.414 1.414L11 7.414V9z" clip-rule="evenodd"></path>
                </svg>
                <p class="mb-4 text-gray-500">Are you sure you want to ensure this reservation?</p>
                <div class="flex justify-center items-center space-x-4">
                    <button onclick="cancelReserve()" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10">
                        No, cancel
                    </button>
                    <button onclick="Reserve()" class="py-2 px-3 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        Yes, I'm sure
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rides table -->
    <?php
    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Retrieve data from the (ride) table
    $ride_sql = "SELECT ride.*, driver.name AS driver_name, driver.seating_capacity, driver.car_make, driver.phone_number, driver.address
    FROM ride
    INNER JOIN driver ON ride.driver_id = driver.driver_id
    WHERE ride.seats_available > '0' AND ride.status = 'undone'";
    $ride_result = $con->query($ride_sql);

    // Close the database connection
    $con->close();
    ?>

    <h1 class="text-3xl text-center p-4 text-gray-800">Available Rides List</h1>
    <div class="flex items-center justify-center mb-2 p-5">
        <div class="relative w-72">
            <input type="text" id="searchInput_ride" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search by Destination area">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="ride" class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Destination
                    </th>
                    <th scope="col" class="px-6 py-3">
                        From
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Seats available
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Driver
                        <div tabindex="0" role="button" class="btn btn-circle btn-ghost btn-xs text-gray-700">
                            <img src="../../images/click.png" class="h-4 w-4" alt="">
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the retrieved data and display it in table rows
                if ($ride_result->num_rows > 0) {
                    while ($row = $ride_result->fetch_assoc()) {
                        echo '<tr class="bg-white border-b">';
                        echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">';
                        echo $row['ride_to'];
                        echo '</th>';
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
                        $car_make = $row['car_make'];
                        $phone = $row['phone_number'];
                        $address = $row['address'];
                        echo '<div class="tooltip" data-tip="' . 'Car Make: ' . $car_make . ' - Phone: ' . $phone . '">';
                        echo '<div class="font-bold bg-blue-200 px-2 py-1 rounded-lg cursor-help">';
                        echo $row['driver_name'];
                        echo '</div>';
                        echo '</div>';
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo '<a href="#" onclick="confirmReserve(' . $row['ride_id'] . ')" class="btn btn-sm text-white bg-blue-600 hover:bg-blue-400">Reserve</a>';
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

        function confirmReserve(rideId) {
            // Show the confirmation modal
            document.getElementById("confirmation-modal_reserve").classList.remove("hidden");

            // Store the ride ID in a variable
            rideIdToReserve = rideId;
        }

        function Reserve() {
            // Check if a ride ID is stored
            if (rideIdToReserve) {
                // Perform the reserve action with the stored ride ID
                window.location.href = "actions/create_reservation.php?id=" + rideIdToReserve;
            } else {
                console.log("No ride ID available for deletion.");
            }
        }

        function cancelReserve() {
            // Hide the confirmation modal
            document.getElementById("confirmation-modal_reserve").classList.add("hidden");
        }

        // For handling search (ride table)
        const searchInput_ride = document.getElementById('searchInput_ride');
        const tableRows_ride = document.querySelectorAll('#ride tbody tr');

        searchInput_ride.addEventListener('input', function() {
            const searchText_ride = searchInput_ride.value.toLowerCase();

            tableRows_ride.forEach(row => {
                const rideName = row.querySelector('th').textContent.toLowerCase();

                if (rideName.includes(searchText_ride)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>