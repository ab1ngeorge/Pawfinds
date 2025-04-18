<?php
session_start();
include('includes/serviceheader.php');
include('includes/db.php'); // Include the database connection

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

$success_message = ""; // Variable to store success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form and sanitize if needed
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

    // Check if preparation was successful
    if ($stmt === false) {
        die('Error preparing the SQL query: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssisssssssss", $pet_name, $pet_type, $breed, $age, $special_instructions, $client_name, 
                      $contact_email, $contact_phone, $address, $service_name, $service_description, 
                      $trainer_name, $appointment_date);

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Dog training session booked successfully! Payment will be collected after the session is completed.";
    } else {
        $success_message = "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Training Service</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Animation Container */
        .animation-container {
            text-align: center;
            margin: 20px 0;
        }

        .animation-container dotlottie-player {
            width: 200px; /* Smaller size */
            height: 200px;

            margin: 0 auto;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            color: #555;
            margin-bottom: 30px;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="datetime-local"], select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus, input[type="number"]:focus, input[type="datetime-local"]:focus, select:focus, textarea:focus {
            border-color: #28a745;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.5);
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        input[type="submit"] {
            grid-column: span 2;
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .success-message {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }

            input[type="submit"] {
                grid-column: span 1;
            }
        }
    </style>
    <!-- Include DotLottie Player -->
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</head>
<body>
    <!-- Animation Container -->
    <div class="animation-container">
    <dotlottie-player src="https://lottie.host/aacdc7be-195b-4c0a-bf5e-032d0e74a3cf/BGWXYWUitt.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
    </div>
    <div class="container">
        <h1>Dog Training Service</h1>
        <p>Help your dog learn new skills and behaviors with our professional training sessions.</p>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="DogTraining.php" method="POST">
            <h3 style="grid-column: span 2;">Book Dog Training</h3>

            <div>
                <label for="pet_name">Pet Name:</label>
                <input type="text" id="pet_name" name="pet_name" required>
            </div>

            <div>
                <label for="pet_type">Pet Type:</label>
                <select id="pet_type" name="pet_type" required>
                    <option value="Dog">Dog</option>
                </select>
            </div>

            <div>
                <label for="breed">Breed:</label>
                <input type="text" id="breed" name="breed" required>
            </div>

            <div>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age">
            </div>

            <div style="grid-column: span 2;">
                <label for="special_instructions">Special Instructions:</label>
                <textarea id="special_instructions" name="special_instructions"></textarea>
            </div>

            <div>
                <label for="client_name">Client Name:</label>
                <input type="text" id="client_name" name="client_name" required>
            </div>

            <div>
                <label for="contact_email">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email">
            </div>

            <div>
                <label for="contact_phone">Contact Phone:</label>
                <input type="tel" id="contact_phone" name="contact_phone">
            </div>

            <div style="grid-column: span 2;">
                <label for="address">Address:</label>
                <textarea id="address" name="address"></textarea>
            </div>

            <div>
                <label for="service_name">Service Name:</label>
                <select id="service_name" name="service_name" required>
                    <option value="">Select Service</option>
                    <option value="Basic Obedience">Basic Obedience</option>
                    <option value="Advanced Obedience">Advanced Obedience</option>
                    <option value="Behavioral Training">Behavioral Training</option>
                    <option value="Agility Training">Agility Training</option>
                </select>
            </div>

            <div>
                <label for="service_description">Service Description:</label>
                <textarea id="service_description" name="service_description"></textarea>
            </div>

            <div>
                <label for="trainer_name">Trainer Name:</label>
                <select id="trainer_name" name="trainer_name" required>
                    <option value="">Select Trainer</option>
                    <option value="John Doe">John Doe</option>
                    <option value="Jane Smith">Jane Smith</option>
                    <option value="Emily Brown">Emily Brown</option>
                </select>
            </div>

            <div>
                <label for="appointment_date">Appointment Date and Time:</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>
            </div>

            <input type="submit" value="Book Service">
        </form>
    </div>
</body>
</html>
