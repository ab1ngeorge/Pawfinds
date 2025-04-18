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
    $dog_name = $_POST['dog_name'];
    $walk_date = $_POST['walk_date'];
    $client_name = $_POST['client_name'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $address = $_POST['address'];
    $walk_duration = $_POST['walk_duration'];
    $walk_instructions = $_POST['walk_instructions'];

    // Prepare SQL query to insert the new dog walking service booking into the database
    $stmt = $conn->prepare("INSERT INTO dog_walking_service (
        dog_name, walk_date, client_name, contact_email, contact_phone, address, walk_duration, walk_instructions
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if preparation was successful
    if ($stmt === false) {
        die('Error preparing the SQL query: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssssss", $dog_name, $walk_date, $client_name, $contact_email, $contact_phone, 
                      $address, $walk_duration, $walk_instructions);

    // Execute the statement
    if ($stmt->execute()) {
        $success_message = "Dog walking service booked successfully!  Payment will be collected after the service is completed.";
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
    <title>Dog Walking Service</title>
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
<body>
    <!-- Animation Container -->
    <div class="animation-container">
    <dotlottie-player 
    src="https://lottie.host/0efbbc20-e17e-4fd3-94fb-a15a32493738/wAifcxeMBy.lottie" 
    background="transparent" 
    speed="1" 
    style="width: 300px; height: 300px" 
    loop 
    autoplay>
</dotlottie-player>
    </div>

    <div class="container">
        <h1>Dog Walking Service</h1>
        <p>Keep your dog healthy and happy with a professional dog walking service.</p>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="DogWalking.php" method="POST">
            <h3 style="grid-column: span 2;">Book Dog Walking</h3>

            <div>
                <label for="dog_name">Dog Name:</label>
                <input type="text" id="dog_name" name="dog_name" required>
            </div>

            <div>
                <label for="walk_date">Walk Date and Time:</label>
                <input type="datetime-local" id="walk_date" name="walk_date" required>
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
                <label for="walk_duration">Walk Duration (minutes):</label>
                <input type="number" id="walk_duration" name="walk_duration" required>
            </div>

            <div style="grid-column: span 2;">
                <label for="walk_instructions">Walk Instructions:</label>
                <textarea id="walk_instructions" name="walk_instructions"></textarea>
            </div>

            <input type="submit" value="Book Walk">
        </form>
    </div>
</body>
</html>
