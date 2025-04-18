<?php
session_start();
include('includes/serviceheader.php');
include('includes/db.php');

// Redirect if user is not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ls.html");
    exit();
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
    $pet_type = mysqli_real_escape_string($conn, $_POST['pet_type']);
    $breed = mysqli_real_escape_string($conn, $_POST['breed']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $special_instructions = mysqli_real_escape_string($conn, $_POST['special_instructions']);
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $service_description = mysqli_real_escape_string($conn, $_POST['service_description']);
    $trainer_name = mysqli_real_escape_string($conn, $_POST['trainer_name']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);

    // Prepare SQL query to insert the new service booking into the database
    $stmt = $conn->prepare("INSERT INTO dog_training_service (
        pet_name, pet_type, breed, age, special_instructions, client_name, contact_email, contact_phone,
        address, service_name, service_description, trainer_name, appointment_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssisssssssss", $pet_name, $pet_type, $breed, $age, $special_instructions, $client_name, 
                      $contact_email, $contact_phone, $address, $service_name, $service_description, 
                      $trainer_name, $appointment_date);

    if ($stmt->execute()) {
        $success_message = "Service booking added successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add New Service</h1>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="add_service.php" method="POST">
            <label for="pet_name">Pet Name</label>
            <input type="text" id="pet_name" name="pet_name" required>

            <label for="pet_type">Pet Type</label>
            <input type="text" id="pet_type" name="pet_type" required>

            <label for="breed">Breed</label>
            <input type="text" id="breed" name="breed" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age">

            <label for="special_instructions">Special Instructions</label>
            <textarea id="special_instructions" name="special_instructions"></textarea>

            <label for="client_name">Client Name</label>
            <input type="text" id="client_name" name="client_name" required>

            <label for="contact_email">Contact Email</label>
            <input type="email" id="contact_email" name="contact_email">

            <label for="contact_phone">Contact Phone</label>
            <input type="tel" id="contact_phone" name="contact_phone">

            <label for="address">Address</label>
            <textarea id="address" name="address"></textarea>

            <label for="service_name">Service Name</label>
            <input type="text" id="service_name" name="service_name" required>

            <label for="service_description">Service Description</label>
            <textarea id="service_description" name="service_description"></textarea>

            <label for="trainer_name">Trainer Name</label>
            <input type="text" id="trainer_name" name="trainer_name" required>

            <label for="appointment_date">Appointment Date</label>
            <input type="datetime-local" id="appointment_date" name="appointment_date" required>

            <button type="submit">Add Service</button>
        </form>
    </div>
</body>
</html>
