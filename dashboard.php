<?php
session_start();
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
include('includes/db.php');
$query = "SELECT * FROM users WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PetAdopt</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Link to the main CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Dashboard Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('assets/images/pet-background.jpg'); /* Add a pet-themed background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .dashboard {
            max-width: 1000px;
            margin: 40px auto;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .dashboard h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 36px;
            font-weight: 700;
        }

        .dashboard p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .user-info {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
        }

        .user-info img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-right: 20px;
            border: 4px solid #3498db;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .user-info h2 {
            font-size: 28px;
            color: #2c3e50;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 16px;
            font-weight: 600;
            margin: 10px;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn i {
            margin-right: 8px;
        }

        /* Cards for Stats or Featured Pets */
        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 30%;
            margin: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            color: #777;
        }

        /* Animations */
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
            .dashboard {
                padding: 20px;
            }

            .dashboard h1 {
                font-size: 28px;
            }

            .dashboard p {
                font-size: 16px;
            }

            .user-info img {
                width: 80px;
                height: 80px;
            }

            .user-info h2 {
                font-size: 20px;
            }

            .card {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1><?php echo getGreeting(); ?>, <?php echo $user['username']; ?>!</h1>
        <div class="user-info">
            <img src="assets/images/default-avatar.jpg" alt="User Avatar">
            <h2><?php echo $user['username']; ?></h2>
        </div>
        <p>Thank you for being a part of PetAdopt. Here, you can find your perfect furry friend and give them a loving home.</p>
        <a href="index.php" class="btn"><i class="fas fa-paw"></i> Browse Pets</a>
        <a href="profile.php" class="btn"><i class="fa fa-book"></i> Activity</a>
        <a href="donation_history.php" class="btn"><i class="fa fa-history"></i> Donation History</a>
        <a href="adoption_history.php" class="btn"><i class="fa fa-history"></i> Adoption History</a>
        <a href="manage_requests.php" class="btn"><i class="fa fa-history"></i> Manage Request</a>

        <!-- Cards for Stats or Featured Pets -->
        <div class="card-container">
            <div class="card">
                <h3>Adopted Pets</h3>
                <p>You have adopted 3 pets so far. Keep up the great work!</p>
            </div>
            <div class="card">
                <h3>Featured Pet</h3>
                <p>Meet Max, a playful Labrador looking for a home!</p>
            </div>
            <div class="card">
                <h3>Upcoming Events</h3>
                <p>Join our adoption drive this weekend. Don't miss it!</p>
            </div>
        </div>
    </div>

    <?php
    include('includes/footer.php');

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
</body>
</html>