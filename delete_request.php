<?php
session_start();
include('includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if a request ID is provided
if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Delete the request from the database
    $query = "DELETE FROM requests WHERE request_id = '$request_id' AND adopter_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to the adoption status page with a success message
        header("Location: adoption_status.php?status=deleted");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
