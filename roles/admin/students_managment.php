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

// Check if an error parameter is present in the URL
if (isset($_GET['error']) && $_GET['error'] == 1) {
    // Display the error notification with a close button
    echo '<div class="flex justify-between items-center bg-red-500 text-white px-4 py-2 mb-4">
              <span>Delete fail.</span>
              <a href="login.php" class="text-white ml-2">&times;</a>
          </div>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Managment Page</title>
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
    <?php include '../../actions/logout_modal.php'; ?>

    <!-- Confirmation modal for delete -->
    <?php include '../admin/includes/delete_modal.php'; ?>

    <!-- Users table -->
    <?php
    require_once '../../includes/connection.php';

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Retrieve data from the (student) table
    $student_sql = "SELECT * FROM student";
    $student_result = $con->query($student_sql);

    // Close the database connection
    $con->close();
    ?>

    <h1 class="text-3xl text-center p-4 text-gray-800">Students List</h1>
    <div class="flex items-center justify-center mb-2 p-5">
        <div class="relative w-64">
            <input type="text" id="searchInput_student" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search by Student name">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="student" class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Phone number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the retrieved data and display it in table rows
                if ($student_result->num_rows > 0) {
                    while ($row = $student_result->fetch_assoc()) {
                        echo '<tr class="bg-white border-b">';
                        echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">';
                        echo $row['name'];
                        echo '</th>';
                        echo '<td class="px-6 py-4">';
                        echo $row['email'];
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo $row['address'];
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo $row['phone_number'];
                        echo '</td>';
                        echo '<td class="px-6 py-4">';
                        echo '<a href="#" onclick="confirmDelete(' . $row['student_id'] . ')" class="btn btn-sm text-white bg-red-600 hover:bg-red-400">Delete</a>';
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

        var studentIdToDelete; 

        function confirmDelete(studentId) {
            // Show the confirmation modal
            document.getElementById("confirmation-modal_delete").classList.remove("hidden");

            // Store the student ID in a variable
            studentIdToDelete = studentId;
        }

        function Delete() {
            // Check if a student ID is stored
            if (studentIdToDelete) {
                // Perform the delete action with the stored student ID
                window.location.href = "actions/delete_student.php?id=" + studentIdToDelete;
            } else {
                // Handle the case where no student ID is available
                console.log("No student ID available for deletion.");
            }
        }

        function cancelDelete() {
            // Hide the confirmation modal
            document.getElementById("confirmation-modal_delete").classList.add("hidden");
        }

        // For handling search (student table)
        const searchInput_student = document.getElementById('searchInput_student');
        const tableRows_student = document.querySelectorAll('#student tbody tr');

        searchInput_student.addEventListener('input', function() {
            const searchText_student = searchInput_student.value.toLowerCase();

            tableRows_student.forEach(row => {
                const studentName = row.querySelector('th').textContent.toLowerCase();

                if (studentName.includes(searchText_student)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>