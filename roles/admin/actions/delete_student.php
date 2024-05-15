<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Delete the student from the table
    $delete_stmt = $con->prepare("DELETE FROM student WHERE student_id = ?");
    $delete_stmt->bind_param("i", $student_id);

    if ($delete_stmt->execute()) {
        $_SESSION['notification'] = 'Students Deleted Successfully!';
        header("Location: ../students_managment.php");
    } else {

        header("Location: ../students_managment.php?error=1");
    }

    // Close the prepared statement
    $delete_stmt->close();
}

// Close the database connection
$con->close();
