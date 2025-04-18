<?php
session_start();
include('includes/profileheader.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

$donor_id = $_SESSION['user_id']; // Donor's user ID

// Database connection
require_once 'includes/db.php';  // Include database connection script

// Fetch all requests for pets donated by the current donor
$sql = "SELECT r.request_id, r.adopter_id, r.pet_id, r.status AS request_status, r.created_at, 
                r.full_name, r.phone, r.email, r.dob, r.address, r.living_situation, r.pets_at_home, 
                r.pet_type, r.pet_age_range, r.pet_breed, r.pet_gender, r.pet_temperament, r.financial_ready, r.adopt_reason, 
                p.name AS pet_name, p.breed AS pet_breed, p.age AS pet_age, p.category AS pet_category 
        FROM requests r
        INNER JOIN pets p ON r.pet_id = p.pet_id
        WHERE p.donor_id = ?"; // Filter by the logged-in donor's ID

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $donor_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission for approval or rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Approve the selected request
        $conn->begin_transaction();

        // 1. Approve the selected request
        $approveQuery = "UPDATE requests SET status = 'approved' WHERE request_id = ?";
        $stmtApprove = $conn->prepare($approveQuery);
        $stmtApprove->bind_param('i', $request_id);
        $stmtApprove->execute();

        // 2. Reject all other requests for the same pet
        $rejectQuery = "UPDATE requests SET status = 'rejected' 
                        WHERE pet_id = (SELECT pet_id FROM requests WHERE request_id = ?)
                        AND request_id != ?";
        $stmtReject = $conn->prepare($rejectQuery);
        $stmtReject->bind_param('ii', $request_id, $request_id);
        $stmtReject->execute();

        // 3. Fetch adopter_id and pet_id from the selected request
        $fetchAdopterPetQuery = "SELECT adopter_id, pet_id FROM requests WHERE request_id = ?";
        $stmtFetchAdopterPet = $conn->prepare($fetchAdopterPetQuery);
        $stmtFetchAdopterPet->bind_param('i', $request_id);
        $stmtFetchAdopterPet->execute();
        $resultAdopterPet = $stmtFetchAdopterPet->get_result();
        $adopterPet = $resultAdopterPet->fetch_assoc();

        if ($adopterPet) {
            $adopter_id = $adopterPet['adopter_id'];
            $pet_id = $adopterPet['pet_id'];

            // 4. Insert into adopt_history table when request is approved
            $insertHistoryQuery = "INSERT INTO adopt_history (adopter_id, pet_id, adopt_date, status) 
                                   VALUES (?, ?, NOW(), 'approved')";
            $stmtHistory = $conn->prepare($insertHistoryQuery);
            $stmtHistory->bind_param('ii', $adopter_id, $pet_id);
            $stmtHistory->execute();
        }

        // Commit the transaction
        $conn->commit();
    } elseif ($action === 'reject') {
        // Reject the selected request
        $rejectQuery = "UPDATE requests SET status = 'rejected' WHERE request_id = ?";
        $stmtReject = $conn->prepare($rejectQuery);
        $stmtReject->bind_param('i', $request_id);
        $stmtReject->execute();
    }

    // Reload the page after the action
    header("Location: manage_requests.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Adoption Requests - PetAdopt</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions form {
            margin: 0;
        }

        .actions input[type="submit"] {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .actions input[type="submit"][value="approve"] {
            background-color: #28a745;
            color: white;
        }

        .actions input[type="submit"][value="reject"] {
            background-color: #dc3545;
            color: white;
        }

        .actions input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .no-requests {
            text-align: center;
            color: #666;
            margin-top: 20px;
        }

        .adopter-info {
            max-width: 300px;
            word-wrap: break-word;
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
            table {
                display: block;
                overflow-x: auto;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <h1><?php echo getGreeting(); ?>, Manage Adoption Requests</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Pet Breed</th>
                    <th>Pet Age</th>
                    <th>Pet Category</th>
                    <th>Adopter Name</th>
                    <th>Adopter Info</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pet_breed']); ?></td>
                        <td><?php echo htmlspecialchars($row['pet_age']); ?></td>
                        <td><?php echo htmlspecialchars($row['pet_category']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td class="adopter-info">
                            <strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?><br>
                            <strong>DOB:</strong> <?php echo htmlspecialchars($row['dob']); ?><br>
                            <strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?><br>
                            <strong>Living Situation:</strong> <?php echo htmlspecialchars($row['living_situation']); ?><br>
                            <strong>Pets at Home:</strong> <?php echo htmlspecialchars($row['pets_at_home']); ?><br>
                            <strong>Pet Type:</strong> <?php echo htmlspecialchars($row['pet_type']); ?><br>
                            <strong>Pet Age Range:</strong> <?php echo htmlspecialchars($row['pet_age_range']); ?><br>
                            <strong>Pet Breed:</strong> <?php echo htmlspecialchars($row['pet_breed']); ?><br>
                            <strong>Pet Gender:</strong> <?php echo htmlspecialchars($row['pet_gender']); ?><br>
                            <strong>Temperament:</strong> <?php echo htmlspecialchars($row['pet_temperament']); ?><br>
                            <strong>Financially Ready:</strong> <?php echo htmlspecialchars($row['financial_ready']); ?><br>
                            <strong>Adopt Reason:</strong> <?php echo htmlspecialchars($row['adopt_reason']); ?><br>
                        </td>
                        <td><?php echo htmlspecialchars($row['request_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td class="actions">
                            <?php if ($row['request_status'] === 'pending'): ?>
                                <form method="POST" action="manage_requests.php" onsubmit="return confirm('Are you sure you want to approve this request?');">
                                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                    <input type="submit" name="action" value="approve">
                                </form>
                                <form method="POST" action="manage_requests.php" onsubmit="return confirm('Are you sure you want to reject this request?');">
                                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                    <input type="submit" name="action" value="reject">
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-requests">No requests found for pets donated by you.</p>
    <?php endif; ?>

     <!-- Go Home Button -->
     <div class="text-center" style="margin-top: 30px;">
        <a href="profile.php" class="btn btn-primary">Go Home</a>
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

// Close the database connection
$conn->close();
?>