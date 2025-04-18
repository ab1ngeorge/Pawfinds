<?php
session_start();

// Check if session variables are set to confirm the booking
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html"); // Redirect to login page if the user is not logged in
    exit();
}

if (!isset($_SESSION['consultation_id'])) {
    die("Error: Consultation ID not found. Please try again.");
}

include('includes/db.php');

// Fetch consultation details based on the consultation_id stored in the session
$consultation_id = $_SESSION['consultation_id'];

$stmt = $conn->prepare("SELECT pet_name, owner_name, contact_email, contact_phone, issue_type, consultation_date FROM consultations WHERE consultation_id = ?");
$stmt->bind_param("i", $consultation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $consultation = $result->fetch_assoc();
} else {
    die("Error: Consultation not found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #28a745;
        }
        p {
            text-align: center;
            color: #555;
        }
        .details {
            margin-top: 30px;
        }
        .details h3 {
            color: #333;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details table, .details th, .details td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .details th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .back-button {
            text-align: center;
            margin-top: 30px;
        }
        .back-button a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .back-button a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Consultation Booked Successfully!</h1>
    <p>Thank you for booking a consultation for your pet! Here are the details:</p>

    <div class="details">
        <h3>Consultation Details:</h3>
        <table>
            <tr>
                <th>Pet Name</th>
                <td><?php echo htmlspecialchars($consultation['pet_name']); ?></td>
            </tr>
            <tr>
                <th>Owner's Name</th>
                <td><?php echo htmlspecialchars($consultation['owner_name']); ?></td>
            </tr>
            <tr>
                <th>Contact Email</th>
                <td><?php echo htmlspecialchars($consultation['contact_email']); ?></td>
            </tr>
            <tr>
                <th>Contact Phone</th>
                <td><?php echo htmlspecialchars($consultation['contact_phone']); ?></td>
            </tr>
            <tr>
                <th>Issue Type</th>
                <td><?php echo htmlspecialchars($consultation['issue_type']); ?></td>
            </tr>
            <tr>
                <th>Consultation Date</th>
                <td><?php echo htmlspecialchars($consultation['consultation_date']); ?></td>
            </tr>
        </table>
    </div>

    <div class="back-button">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
