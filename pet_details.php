<?php
include('includes/header.php');
include('includes/db.php');

$pet_id = $_GET['pet_id'];

// Fetch pet details including pet_behaviour and health record
$query = "SELECT * FROM pets WHERE pet_id = '$pet_id'";
$result = $conn->query($query);
$pet = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pet['name']); ?>'s Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .pet-details {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .pet-details h1 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .pet-details img {
            display: block;
            max-width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .pet-details p {
            font-size: 18px;
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .pet-details p strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .btn-adopt {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }

        .btn-adopt:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-container {
            text-align: center;
            margin-top: 30px;
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
            .pet-details {
                padding: 20px;
                margin: 20px;
            }

            .pet-details h1 {
                font-size: 28px;
            }

            .pet-details p {
                font-size: 16px;
            }

            .pet-details img {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="pet-details">
        <h1><?php echo htmlspecialchars($pet['name']); ?>'s Details</h1>
        <img src="<?php echo (!empty($pet['image']) ? htmlspecialchars($pet['image']) : 'assets/images/default_pet_image.jpg'); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" />
        
        <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($pet['category']); ?></p>
        <p><strong>Size:</strong> <?php echo htmlspecialchars($pet['size']); ?></p>
        <p><strong>Color:</strong> <?php echo htmlspecialchars($pet['color']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($pet['status'])); ?></p>
        <p><strong>Vaccinated:</strong> <?php echo ($pet['vaccinated'] ? 'Yes' : 'No'); ?></p>
        <p><strong>Spayed/Neutered:</strong> <?php echo ($pet['spayed_neutered'] ? 'Yes' : 'No'); ?></p>
        <p><strong>Health Condition:</strong> <?php echo nl2br(htmlspecialchars($pet['health_condition'])); ?></p>
        <p><strong>Special Instructions:</strong> <?php echo nl2br(htmlspecialchars($pet['special_instructions'])); ?></p>
        
        <!-- Displaying Pet Behaviour -->
        <p><strong>Pet Behaviour:</strong> <?php echo nl2br(htmlspecialchars($pet['pet_behaviour'])); ?></p>

        <!-- Health Record (PDF) -->
        <?php if (!empty($pet['health_record'])): ?>
            <p><strong>Health Record:</strong> <a href="<?php echo htmlspecialchars($pet['health_record']); ?>" target="_blank">Download Health Record (PDF)</a></p>
        <?php else: ?>
            <p><strong>Health Record:</strong> Not available</p>
        <?php endif; ?>

        <div class="btn-container">
            <a href="adopt_request.php?pet_id=<?php echo $pet['pet_id']; ?>" class="btn-adopt">Adopt This Pet <i class="fas fa-paw"></i></a>
        </div>
    </div>

    <?php
    include('includes/footer.php');
    ?>
</body>
</html>
