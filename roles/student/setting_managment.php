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
    <title>Settings Managment Page</title>
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

    <!-- student table -->
    <?php
    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Retrieve data from the "student" table
    $student_sql = "SELECT * FROM student WHERE student_id = $student_id";
    $student_result = $con->query($student_sql);

    // Close the database connection
    $con->close();
    ?>

    <section>
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto mt-14 lg:py-2">
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-800 md:text-2xl">
                        Edit Student Details
                    </h1>
                    <?php while ($student_row = $student_result->fetch_assoc()) { ?>
                        <form class="space-y-4 md:space-y-6" action="actions/update_student.php" method="POST">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-800">Name</label>
                                <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Jamil Hazem" value="<?php echo $student_row['name']; ?>">
                                <?php if (isset($_SESSION['registration_errors']['name'])) : ?>
                                    <span class="text-red-500 text-sm"><?php echo $_SESSION['registration_errors']['name']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-800">Password</label>
                                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" >
                                <?php if (isset($_SESSION['registration_errors']['password'])) : ?>
                                    <span class="text-red-500 text-sm"><?php echo $_SESSION['registration_errors']['password']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="address" class="block mb-2 text-sm font-medium text-gray-800">Address</label>
                                <input type="address" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo $student_row['address']; ?>">
                                <?php if (isset($_SESSION['registration_errors']['address'])) : ?>
                                    <span class="text-red-500 text-sm"><?php echo $_SESSION['registration_errors']['address']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-800">Phone</label>
                                <input type="phone" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo $student_row['phone_number']; ?>">
                                <?php if (isset($_SESSION['registration_errors']['phone'])) : ?>
                                    <span class="text-red-500 text-sm"><?php echo $_SESSION['registration_errors']['phone']; ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update Details</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Clear the registration errors from the session
    unset($_SESSION['registration_errors']);
    ?>

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