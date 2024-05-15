<?php
require_once '../../../includes/connection.php';
session_start();

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $comment_id = $_GET['id'];

    $delete_stmt = $con->prepare("DELETE FROM comment WHERE comment_id = ?");
    $delete_stmt->bind_param("i", $comment_id);

    if ($delete_stmt->execute()) {
        $_SESSION['notification'] = 'Comment Deleted Successfully!';
        header("Location: ../comments_managment.php");
    } else {
        header("Location: ../comments_managment.php?error=1");
    }

    // Close the prepared statement
    $delete_stmt->close();
}

// Close the database connection
$con->close();
