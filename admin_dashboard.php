<?php
session_start();
include('includes/serviceheader.php');
include('includes/db.php'); // Database connection

// Redirect if user is not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ls.html"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Fetch all service bookings from the database
$query = "SELECT * FROM dog_training_service ORDER BY appointment_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <a href="add_service.php" class="btn btn-primary">Add New Service</a>
        <table>
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Service Name</th>
                    <th>Trainer</th>
                    <th>Appointment Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['pet_name']; ?></td>
                        <td><?php echo $row['service_name']; ?></td>
                        <td><?php echo $row['trainer_name']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td>
                            <a href="edit_service.php?id=<?php echo $row['Service_ID']; ?>">Edit</a> |
                            <a href="delete_service.php?id=<?php echo $row['Service_ID']; ?>" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
