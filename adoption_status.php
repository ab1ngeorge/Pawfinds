<?php
session_start();
include('includes/header.php');

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Include the database connection file
include('includes/db.php');

// Fetch adoption requests for the logged-in user
$query = "SELECT `request_id`, `adopter_id`, `pet_id`, `reason`, `status`, `created_at` FROM `requests` WHERE `adopter_id` = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch the request details
$request = mysqli_fetch_assoc($result);

// Check if there is a request for the logged-in user
if (!$request) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Adoption Status - PetAdopt</title>
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
            }

            .container {
                max-width: 800px;
                margin: 50px auto;
                padding: 30px;
                background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
                border-radius: 15px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                animation: fadeIn 1s ease-in-out;
            }

            h2 {
                color: #2c3e50;
                text-align: center;
                margin-bottom: 30px;
                font-weight: 600;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            }

            .message-box {
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                margin-top: 20px;
                animation: slideIn 0.8s ease-in-out;
                background-color: #fff3cd;
                border: 1px solid #ffeeba;
            }

            .message-box p {
                font-size: 18px;
                color: #555;
                margin: 0;
            }

            .message-icon {
                font-size: 48px;
                margin-bottom: 20px;
                color: #ffc107;
            }

            .btn-explore {
                display: inline-block;
                margin-top: 30px;
                padding: 12px 24px;
                background-color: #3498db;
                color: #fff;
                text-decoration: none;
                border-radius: 8px;
                transition: background-color 0.3s ease, transform 0.3s ease;
                font-size: 16px;
                font-weight: 600;
            }

            .btn-explore:hover {
                background-color: #2980b9;
                transform: translateY(-3px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .container {
                    padding: 20px;
                }

                h2 {
                    font-size: 24px;
                }

                .message-box p {
                    font-size: 16px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2><?php echo getGreeting(); ?>, Your Adoption Status</h2>

            <!-- Display message for no adoption request -->
            <div class="message-box">
                <div class="message-icon">
                    <i class="fas fa-search"></i>
                </div>
                <p>No adoption request found for your account.</p>
                <p>Start your journey to find your perfect furry friend today!</p>
            </div>

            <!-- Explore Pets Button -->
            <a href="index.php" class="btn-explore"><i class="fas fa-paw"></i> Explore Pets</a>
        </div>

        <!-- Bootstrap 5 JS (Optional) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}
// Initialize status message
$status_message = '';

// Check if the request contains a valid status and pet_id
if (isset($request['status']) && isset($request['pet_id'])) {
    // Get the status and pet_id from the request
    $status = $request['status'];
    $pet_id = $request['pet_id'];

    // Database connection assumed to be in $conn

    if ($status == 'approved') {
        // Check if pet_id is not empty
        if (!empty($pet_id)) {
            // Step 1: Fetch donor details using pet_id (joins pets and users tables)
            $pet_query = "
                SELECT u.username, u.email 
                FROM pets p
                JOIN users u ON p.donor_id = u.user_id
                WHERE p.pet_id = ?
            ";
            $stmt = $conn->prepare($pet_query);

            // Check if the prepare statement is successful
            if ($stmt) {
                $stmt->bind_param('i', $pet_id);
                $stmt->execute();
                $pet_result = $stmt->get_result();

                // Check if donor details were found for the given pet_id
                if ($pet_result->num_rows > 0) {
                    $donor = $pet_result->fetch_assoc();
                    // Prepare the status message with donor details
                    $status_message = "Approved: We will contact you soon. <br> Donor details: Name: " . htmlspecialchars($donor['username']) . ", Email: " . htmlspecialchars($donor['email']) . ".";
                } else {
                    // If no donor details found for this pet
                    $status_message = "Approved: We will contact you soon. <br> Donor details: Not available.";
                }

                // Close the statement
                $stmt->close();
            } else {
                // Error preparing the query
                $status_message = "An error occurred while preparing the query to fetch donor details.";
            }
        } else {
            $status_message = "No valid pet ID provided.";
        }

    } elseif ($status == 'rejected') {
        // Status is rejected
        $status_message = "Rejected: Sorry, you are not eligible for this pet. Please request another pet or resubmit your application. <br> Or this pet has been adopted by someone more eligible.";

    } else {
        // If the status is neither 'approved' nor 'rejected'
        $status_message = "Under review: Please wait while your application is being reviewed.";
    }
} else {
    // If status or pet_id are not set in the request
    $status_message = "Invalid request. Missing status or pet ID.";
}

// Output the status message
//echo $status_message;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Status - PetAdopt</title>
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
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-box {
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-top: 20px;
            animation: slideIn 0.8s ease-in-out;
        }

        .status-box p {
            font-size: 18px;
            color: #555;
            margin: 0;
        }

        .status-box strong {
            color: #3498db;
        }

        .status-box.approved {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .status-box.rejected {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .status-box.under-review {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
        }

        .status-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #3498db;
        }

        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-home:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            .status-box p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo getGreeting(); ?>, Your Adoption Status</h2>

        <!-- Display status and appropriate message -->
        <div class="status-box 
            <?php
            if ($request['status'] == 'approved') {
                echo 'approved';
            } elseif ($request['status'] == 'rejected') {
                echo 'rejected';
            } else {
                echo 'under-review';
            }
            ?>
        ">
            <div class="status-icon">
                <?php
                if ($request['status'] == 1) {
                    echo '<i class="fas fa-check-circle"></i>';
                } elseif ($request['status'] == 0) {
                    echo '<i class="fas fa-times-circle"></i>';
                } else {
                    echo '<i class="fas fa-hourglass-half"></i>';
                }
                ?>
            </div>
            <p><strong>Status: </strong>
            <?php
            // Display the status based on the value of `status`
            if ($request['status'] == 'approved') {
                echo "Approved";
            } elseif ($request['status'] =='rejected') {
                echo "Rejected";
            } else {
                echo "Under Review";
            }
            ?></p>

            <!-- Display the status message -->
            <p><?php echo $status_message; ?></p>
            
            <?php if ($request['status'] == 'approved'): ?>
            <!-- Download Certificate Button -->
            <div class="mt-4">
                <a href="generate_certificate.php?request_id=<?php echo $request['request_id']; ?>" class="btn btn-success">
                    <i class="fas fa-certificate"></i> Download Adoption Certificate
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Home Button -->
        <a href="index.php" class="btn-home"><i class="fas fa-home"></i> Back to Home</a>
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

include('includes/footer.php');
?>