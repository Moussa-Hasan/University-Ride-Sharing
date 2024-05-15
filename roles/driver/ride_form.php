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
    <title>Ride Form Page</title>
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

    <!-- fetch for seating capacity -->
    <?php
    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare the query
    $query = "SELECT seating_capacity FROM driver WHERE driver_id = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $driver_id);

    $stmt->execute();
    $stmt->bind_result($seatingCapacity);
    $stmt->fetch();
    $stmt->close();

    // Store the seating capacity in a variable
    $availableSeats = $seatingCapacity;

    // Close the database connection
    $con->close();
    ?>

    <script>
        $(document).ready(function() {
            // Update the value of the seats input field
            $('#seats').val(<?php echo $seatingCapacity; ?>);
        });
    </script>

    <section>
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto mt-8 lg:py-2">
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-800 md:text-2xl">
                        Set a Ride
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="actions/create_ride.php" method="POST">
                        <div>
                            <label for="start" class="block mb-2 text-sm font-medium text-gray-800">Starting Point</label>
                            <input type="text" name="start" id="start" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter starting point" required="">
                        </div>
                        <div>
                            <label for="destination" class="block mb-2 text-sm font-medium text-gray-800">Destination</label>
                            <input type="text" name="destination" id="destination" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Enter destination" required="">
                        </div>
                        <div>
                            <label for="seats" class="block mb-2 text-sm font-medium text-gray-800">Available Seats</label>
                            <input type="number" name="seats" id="seats" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo $availableSeats; ?>" required readonly>
                        </div>
                        <div>
                            <label for="time" class="block mb-2 text-sm font-medium text-gray-800">Time</label>
                            <input type="time" name="time" id="time" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Set Ride</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- flowbit js  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
    <!-- daisy ui -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
         function logout() {
            // Perform the logout action
            window.location.href = "../../actions/logout.php";
        }
    </script>

</body>

</html>