<?php
session_start();
include('includes/profileheader.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

$adopter_id = $_SESSION['user_id']; // Adopter's user ID

// Database connection
require_once 'includes/db.php';  // Include database connection script

// SQL Query to get adopted pets
$sql = "SELECT adopt_history.adopt_history_id, pets.pet_id, pets.name, pets.breed, pets.age, pets.specialty, pets.image, adopt_history.status, adopt_history.adopt_date, pets.category
        FROM adopt_history
        JOIN pets ON adopt_history.pet_id = pets.pet_id
        WHERE adopt_history.adopter_id = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Debugging: Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing the SQL query: " . $conn->error);
}

// Bind the parameter
$stmt->bind_param('i', $adopter_id);

// Execute the statement
$stmt->execute();

// Get the result
$adopted_pets_result = $stmt->get_result();

// Check for errors in query execution
if ($adopted_pets_result === false) {
    die("Error executing the query: " . $stmt->error);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption History - PetAdopt</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('assets/images/pet-background.jpg'); /* Add a pet-themed background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pet-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .pet-item {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .pet-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .pet-item h3 {
            margin-top: 10px;
            font-size: 1.5em;
            color: #2c3e50;
        }

        .pet-item p {
            margin-top: 10px;
            color: #7f8c8d;
            font-size: 1.1em;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .pet-list {
                grid-template-columns: 1fr 1fr;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <h1><?php echo getGreeting(); ?>, Adoption History</h1>

    <div class="pet-list">
        <?php if ($adopted_pets_result->num_rows > 0): ?>
            <?php while ($pet = $adopted_pets_result->fetch_assoc()): ?>
                <div class="pet-item">
                    <!-- Displaying the pet image -->
                    <img src="<?php echo !empty($pet['image']) ? htmlspecialchars($pet['image']) : 'uploads/default-pet.jpg'; ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" />
                    <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                    <p><?php echo htmlspecialchars($pet['breed']); ?> | Age: <?php echo htmlspecialchars($pet['age']); ?> years</p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($pet['category']); ?></p>
                    <!-- <p><strong>Status:</strong> <?php echo htmlspecialchars($pet['status']); ?></p> -->
                    <p><strong>Specialty:</strong> <?php echo htmlspecialchars($pet['specialty']); ?></p>
                    <p><strong>Adopted on:</strong> <?php echo htmlspecialchars($pet['adopt_date']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pets available in your adoption history.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Function to get dynamic greeting based on time of day
function getGreeting() {
    $hour = date('H');
    if ($hour < 12) {
        return "Good Morning";
    } elseif ($hour < 18) {
        return "Good Afternoon";
    } else {
        return "Good Evening";
    }
}
?>
