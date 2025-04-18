<?php
session_start();
include('includes/header.php');  // Include the header file (e.g., navigation bar)

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: ls.html");
    exit();
}

// Database connection
require_once 'includes/db.php';  // Include the database connection script

// Fetch adoption history details from the database
$sql = "SELECT ah.adopt_history_id, ah.adopter_id, ah.pet_id, ah.adopt_date, 
            p.name AS pet_name, p.image AS pet_image, p.breed AS pet_breed, p.age AS pet_age, 
            u1.username AS adopter_name, u1.email AS adopter_email, 
            u2.username AS donor_name, u2.email AS donor_email
        FROM adopt_history ah
        INNER JOIN pets p ON ah.pet_id = p.pet_id
        INNER JOIN users u1 ON ah.adopter_id = u1.user_id
        INNER JOIN users u2 ON p.donor_id = u2.user_id
        ORDER BY ah.adopt_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }

        img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.1);
        }

        .no-data {
            text-align: center;
            color: #777;
            font-style: italic;
            padding: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            th, td {
                padding: 10px;
            }

            img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Adoption History</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Adoption Date</th>
                    <th>Pet Name</th>
                    <th>Pet Image</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Adopter Name</th>
                    <th>Adopter Email</th>
                    <th>Donor Name</th>
                    <th>Donor Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . date('F j, Y', strtotime($row['adopt_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pet_name']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row['pet_image']) . "' alt='Pet Image'></td>";
                        echo "<td>" . htmlspecialchars($row['pet_breed']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pet_age']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adopter_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adopter_email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['donor_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['donor_email']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='no-data'>No adoption history found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>