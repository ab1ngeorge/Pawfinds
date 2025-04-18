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
    // Sanitize and validate user input
    $pet_name = htmlspecialchars($_POST['pet_name']); // Added pet_name
    $owner_name = htmlspecialchars($_POST['owner_name']);
    $contact_email = filter_var($_POST['contact_email'], FILTER_SANITIZE_EMAIL);
    $contact_phone = htmlspecialchars($_POST['contact_phone']);
    $issue_type = htmlspecialchars($_POST['issue_type']);
    $consultation_date = $_POST['consultation_date']; // Assuming this is already in a valid format

    // Validate email
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $success_message = "Invalid email format!";
    } else {
        // Prepare SQL query to insert the new consultation booking into the database
        $stmt = $conn->prepare("INSERT INTO consultations (
             pet_name, owner_name, contact_email, contact_phone, issue_type, consultation_date
        ) VALUES ( ?, ?, ?, ?, ?, ?)");

        // Check if preparation was successful
        if ($stmt === false) {
            die('Error preparing the SQL query: ' . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ssssss", $pet_name, $owner_name, $contact_email, $contact_phone, $issue_type, $consultation_date);

        // Execute the statement
        if ($stmt->execute()) {
            // Store the consultation_id in the session
            $_SESSION['consultation_id'] = $stmt->insert_id;

            $success_message = "Vet consultation booked successfully!";
            // Redirect to confirmation page
            header("Location: confirmation.php");
            exit(); // Ensure no further code is executed after redirect
        } else {
            $success_message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vet Consultation Service</title>
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
     <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
     </head>
</head>
<body>
    <div class="animation-container">
    <dotlottie-player 
            src="https://lottie.host/79243fd1-d07a-4bea-8976-fed232e7a012/XBeFsP5lCu.lottie" 
            background="transparent" 
            speed="1" 
            style="width: 300px; height: 300px" 
            loop 
            autoplay>
        </dotlottie-player> </div>

    <div class="container">
        <h1>Vet Consultation Service</h1>
        <p>Book a consultation with a vet to ensure your pet's health and well-being.</p>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="VetConsultation.php" method="POST">
            <h3 style="grid-column: span 2;">Book Vet Consultation</h3>

            <div>
                <label for="pet_name">Pet Name:</label> <!-- Added pet_name input -->
                <input type="text" id="pet_name" name="pet_name" required>
            </div>

            <div>
                <label for="owner_name">Owner's Name:</label>
                <input type="text" id="owner_name" name="owner_name" required>
            </div>

            <div>
                <label for="contact_email">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email" required>
            </div>

            <div>
                <label for="contact_phone">Contact Phone:</label>
                <input type="tel" id="contact_phone" name="contact_phone" required>
            </div>

            <div>
                <label for="issue_type">Issue Type:</label>
                <select id="issue_type" name="issue_type" required>
                    <option value="">Select Issue</option>
                    <option value="General Checkup">General Checkup</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="consultation_date">Consultation Date and Time:</label>
                <input type="datetime-local" id="consultation_date" name="consultation_date" required>
            </div>

            <input type="submit" value="Book Consultation">
        </form>
    </div>
</body>
</html>
