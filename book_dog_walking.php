<?php
session_start();
include('includes/db.php');

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $dog_name = $_POST['dog_name'];
    $walk_date = $_POST['walk_date'];

    // Validate the input data
    if (empty($dog_name) || empty($walk_date)) {
        echo "All fields are required.";
        exit();
    }

    // Insert the booking into the database
    $query = "INSERT INTO dog_walking_bookings (user_id, dog_name, walk_date, status) VALUES (?, ?, ?, 'Pending')";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("iss", $user_id, $dog_name, $walk_date);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Your dog walking session has been successfully booked!";
        } else {
            echo "There was an error with your booking. Please try again.";
        }
        $stmt->close();
    } else {
        echo "Error preparing the query.";
    }
} else {
    echo "Invalid request.";
}
?>
