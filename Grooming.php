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
    // Get user input from the form
    $pet_name = $_POST['pet_name'];
    $pet_type = $_POST['pet_type'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $special_instructions = $_POST['special_instructions'];
    $client_name = $_POST['client_name'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $address = $_POST['address'];
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];
    $groomer_name = $_POST['groomer_name'];
    $appointment_date = $_POST['appointment_date'];

    // Prepare SQL query to insert the new service booking into the database
    $stmt = $conn->prepare("INSERT INTO pet_grooming_service (
        pet_name, pet_type, breed, age, special_instructions, client_name, contact_email, contact_phone,
        address, service_name, service_description, groomer_name, appointment_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if preparation was successful
    if ($stmt === false) {
        die('Error preparing the SQL query: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssisssssssss", $pet_name, $pet_type, $breed, $age, $special_instructions, $client_name, 
                      $contact_email, $contact_phone, $address, $service_name, $service_description, 
                      $groomer_name, $appointment_date);

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Grooming service booked successfully! Payment will be collected after the service is completed.";
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
    <title>Pet Grooming Service</title>
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
        <dotlottie-player src="https://lottie.host/122a56d7-e858-4d75-b88e-7e8f38224737/yVPzfCP74G.lottie" background="transparent" speed="1" loop autoplay></dotlottie-player>
    </div>

    <div class="container">
        <h1>Pet Grooming Service</h1>
        <p>Keep your pet looking and feeling their best with professional grooming services tailored to your cat or dog.</p>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="Grooming.php" method="POST">
            <h3 style="grid-column: span 2;">Book Pet Grooming</h3>

            <div>
                <label for="pet_name">Pet Name:</label>
                <input type="text" id="pet_name" name="pet_name" required>
            </div>

            <div>
                <label for="pet_type">Pet Type:</label>
                <select id="pet_type" name="pet_type" required>
                    <option value="">Select Pet Type</option>
                    <option value="Cat">Cat</option>
                    <option value="Dog">Dog</option>
                    <option value="Other">Other</option>
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
                    <option value="Bath">Bath</option>
                    <option value="Haircut">Haircut</option>
                    <option value="Nail Trimming">Nail Trimming</option>
                    <option value="Teeth Cleaning">Teeth Cleaning</option>
                    <option value="Full Grooming">Full Grooming</option>
                </select>
            </div>

            <div>
                <label for="service_description">Service Description:</label>
                <textarea id="service_description" name="service_description"></textarea>
            </div>

            <div>
                <label for="groomer_name">Groomer Name:</label>
                <select id="groomer_name" name="groomer_name" required>
                    <option value="">Select Groomer</option>
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
