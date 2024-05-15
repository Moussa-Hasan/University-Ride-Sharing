<?php
session_start();

// Check if the necessary session variables are not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {

    header("Location: ../../login.php");
    exit();
}

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
    <title>Dashboard Page</title>
    <link rel="stylesheet" href="../../dist/output.css">
    <!-- flowbit js  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.css" rel="stylesheet" />
    <!-- daisy ui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.17/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <!-- Navigation bar -->
    <?php include '../admin/includes/header.php'; ?>

    <!-- success alert -->
    <?php if (isset($notification)) : ?>
        <div id="alert" class="fixed top-16 left-0 right-0 flex items-center justify-center z-50">
            <div class="flex items-center p-4 text-green-800 rounded-lg bg-green-100 max-w-sm" role="alert">
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

    <!-- Analysis -->
    <?php
    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Query to get the count of students
    $studentQuery = "SELECT COUNT(*) AS studentCount FROM student";
    $studentResult = $con->query($studentQuery);
    $studentCount = $studentResult->fetch_assoc()['studentCount'];

    // Query to get the count of drivers
    $driverQuery = "SELECT COUNT(*) AS driverCount FROM driver";
    $driverResult = $con->query($driverQuery);
    $driverCount = $driverResult->fetch_assoc()['driverCount'];

    // Query to get the count of reservations
    $reservationQuery = "SELECT COUNT(*) AS reservationCount FROM reservation";
    $reservationResult = $con->query($reservationQuery);
    $reservationCount = $reservationResult->fetch_assoc()['reservationCount'];

    // Query to get the count of comments
    $commentQuery = "SELECT COUNT(*) AS commentCount FROM comment";
    $commentResult = $con->query($commentQuery);
    $commentCount = $commentResult->fetch_assoc()['commentCount'];

    // Close the database connection
    $con->close();
    ?>

    <section class="bg-white">
        <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
            <dl class="grid max-w-screen-md gap-8 mx-auto text-gray-900 md:grid-cols-4 sm:grid-cols-2">
                <div class="bg-gray-200 rounded-lg p-4 flex flex-col items-center justify-center shadow-md">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"><?php echo $studentCount; ?></dt>
                    <dd class="font-light text-gray-500">students</dd>
                </div>

                <div class="bg-gray-200 rounded-lg p-4 flex flex-col items-center justify-center shadow-md">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"><?php echo $driverCount; ?></dt>
                    <dd class="font-light text-gray-500">drivers</dd>
                </div>

                <div class="bg-gray-200 rounded-lg p-4 flex flex-col items-center justify-center shadow-md">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"><?php echo $reservationCount; ?></dt>
                    <dd class="font-light text-gray-500">reservations</dd>
                </div>

                <div class="bg-gray-200 rounded-lg p-4 flex flex-col items-center justify-center shadow-md">
                    <dt class="mb-2 text-3xl md:text-4xl font-extrabold"><?php echo $commentCount; ?></dt>
                    <dd class="font-light text-gray-500">comments</dd>
                </div>

            </dl>
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