<?php
// Start session to manage user login state
include('includes/profileheader.php');


// Include the database connection file
include('includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ls.html");
    exit();
}

// Get the logged-in user's ID from session
$user_id = $_SESSION['user_id'];

// Query to fetch user details
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Query to fetch the user's adopt history (if adopter)
// $query_adopt_history = "
//     SELECT ah.pet_id, ah.adopt_date, ah.status, p.image AS pet_image, p.name AS pet_name
//     FROM adopt_history ah
//     JOIN pets p ON ah.pet_id = p.pet_id
//     WHERE ah.adopter_id = ?";
// $stmt_adopt_history = $conn->prepare($query_adopt_history);
// $stmt_adopt_history->bind_param("i", $user_id);
// $stmt_adopt_history->execute();
// $result_adopt_history = $stmt_adopt_history->get_result();

// Query to fetch the user's pet requests and join with pets table for pet image
$query_requests = "
    SELECT r.pet_id, r.status, r.adopt_reason, p.image AS pet_image
    FROM requests r
    JOIN pets p ON r.pet_id = p.pet_id
    WHERE r.adopter_id = ?";
$stmt_requests = $conn->prepare($query_requests);
$stmt_requests->bind_param("i", $user_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();

// Query to fetch donation history for the user (if donor)
$query_donations = "
    SELECT p.pet_id, p.name, p.category, p.status, p.image
    FROM pets p
    WHERE p.donor_id = ?";
$stmt_donations = $conn->prepare($query_donations);
$stmt_donations->bind_param("i", $user_id);
$stmt_donations->execute();
$result_donations = $stmt_donations->get_result();

// Query to fetch success stories for the user
$query_success_stories = "SELECT * FROM success_stories WHERE adopter_id = ?";
$stmt_success_stories = $conn->prepare($query_success_stories);
$stmt_success_stories->bind_param("i", $user_id);
$stmt_success_stories->execute();
$result_success_stories = $stmt_success_stories->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - PetAdopt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('assets/images/pet-background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #2c3e50;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        h1, h3 {
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .user-details {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .user-details ul {
            list-style: none;
            padding: 0;
        }

        .user-details ul li {
            padding: 10px 0;
            font-size: 18px;
            color: #555;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 20px;
            flex: 1 1 calc(33.333% - 40px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            margin-top: 0;
            color: #3498db;
        }

        .card p {
            font-size: 16px;
            color: #555;
        }

        .card img {
            border-radius: 8px;
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn i {
            margin-right: 8px;
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

        @media (max-width: 768px) {
            .card {
                flex: 1 1 100%;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo getGreeting(); ?>, <?php echo htmlspecialchars($user['username']); ?>!</h1>

        <div class="user-details">
            <h3>User Details:</h3>
            <ul>
                <li>Email: <?php echo htmlspecialchars($user['email']); ?></li>
                <li>Role: <?php echo ucfirst($user['role']); ?></li>
                <li>Account Created: <?php echo $user['created_at']; ?></li>
            </ul>
        </div>

       

        <!-- Pet Requests -->
        <h3>Your Pet Requests:</h3>
        <div class="card-container">
            <?php while ($request = $result_requests->fetch_assoc()): ?>
            <div class="card">
                <h3>Pet ID: <?php echo htmlspecialchars($request['pet_id']); ?></h3>
                <p>Status: <?php echo ucfirst($request['status']); ?></p>
                <p>Reason: <?php echo htmlspecialchars($request['adopt_reason']); ?></p>

                <!-- Display requested pet image -->
                <img src="<?php echo !empty($request['pet_image']) ? htmlspecialchars($request['pet_image']) : 'uploads/default-pet.jpg'; ?>" alt="Requested Pet Image">
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Donation History -->
        <h3>Your Donation History:</h3>
        <div class="card-container">
            <?php if ($result_donations->num_rows > 0): ?>
                <?php while ($donation = $result_donations->fetch_assoc()): ?>
                    <div class="card">
                        <h3>Pet ID: <?php echo htmlspecialchars($donation['pet_id']); ?></h3>
                        <p>Name: <?php echo htmlspecialchars($donation['name']); ?></p>
                        <p>Category: <?php echo htmlspecialchars($donation['category']); ?></p>
                        <p>Status: <?php echo ucfirst($donation['status']); ?></p>
                      

                        <!-- Display donation pet image -->
                        <img src="<?php echo !empty($donation['image']) ? htmlspecialchars($donation['image']) : 'uploads/default-pet.jpg'; ?>" alt="Donation Pet Image">
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You haven't donated any pets yet.</p>
            <?php endif; ?>
        </div>


        <!-- Success Stories -->
        <h3>Your Success Stories:</h3>
        <div class="card-container">
            <?php while ($story = $result_success_stories->fetch_assoc()): ?>
                <div class="card">
                    <h3>Pet ID: <?php echo htmlspecialchars($story['pet_id']); ?></h3>
                    <p><?php echo htmlspecialchars($story['story']); ?></p>
                    <img src="<?php echo htmlspecialchars($story['image']); ?>" alt="Story Image">
                </div>
            <?php endwhile; ?>
        </div>

        <a href="index.php" class="btn"><i class="fas fa-home"></i> Back to Home</a>
    </div>

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

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
