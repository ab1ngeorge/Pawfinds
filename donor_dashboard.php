<?php
session_start();
include('includes/db.php');  // Include the database connection
include('includes/header.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission to upload pet details
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $category = $_POST['category'];
    $special_instructions = $_POST['special_instructions'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $vaccinated = isset($_POST['vaccinated']) ? 1 : 0; // Checkbox for vaccinated
    $spayed_neutered = isset($_POST['spayed_neutered']) ? 1 : 0; // Checkbox for spayed/neutered
    $microchip_id = $_POST['microchip_id'] ? $_POST['microchip_id'] : null;
    $health_condition = $_POST['health_condition'] ? $_POST['health_condition'] : null;
    $location = $_POST['location'] ? $_POST['location'] : null;
    $personality = $_POST['personality'] ? $_POST['personality'] : null;
    $pet_behaviour = $_POST['pet_behaviour'] ? $_POST['pet_behaviour'] : null; // New pet behaviour field

    // Check for image upload errors
    if ($_FILES['image']['error'] != 0) {
        echo "<div class='alert alert-danger'>Error: " . $_FILES['image']['error'] . "</div>";
    } else {
        // Get the image name and target directory
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/"; // Ensure the 'uploads' directory exists
        $target_file = $target_dir . basename($image);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_image_types = array("jpg", "png", "jpeg", "gif");

        if (in_array($imageFileType, $allowed_image_types)) {
            // Move the uploaded image file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                echo "<div class='alert alert-success'>The image file " . basename($image) . " has been uploaded.</div>";
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your image file.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Only JPG, JPEG, PNG, and GIF files are allowed for the image.</div>";
        }
    }

    // Check for health record (PDF) upload
    if ($_FILES['health_record']['error'] != 0) {
        echo "<div class='alert alert-danger'>Error: " . $_FILES['health_record']['error'] . "</div>";
    } else {
        // Get the health record name and target directory
        $health_record = $_FILES['health_record']['name'];
        $health_record_dir = "uploads/health_records/"; // Ensure the 'health_records' directory exists
        $health_record_file = $health_record_dir . basename($health_record);

        // Check if the file is a PDF
        $healthRecordFileType = strtolower(pathinfo($health_record_file, PATHINFO_EXTENSION));
        if ($healthRecordFileType == "pdf") {
            // Move the uploaded PDF file to the target directory
            if (move_uploaded_file($_FILES['health_record']['tmp_name'], $health_record_file)) {
                echo "<div class='alert alert-success'>The health record PDF has been uploaded.</div>";
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your health record PDF.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Only PDF files are allowed for the health record.</div>";
        }
    }

    // Insert pet data into the database, including image path and health record path
    $donor_id = $_SESSION['user_id'];  // Get the donor's ID from the session
    $image_path = $target_dir . $image; // Save the relative image path
    $health_record_path = $health_record_dir . $health_record; // Save the relative health record path

    // SQL query to insert pet data including the health record PDF path
    $query = "INSERT INTO pets (name, breed, age, category, special_instructions, image, status, donor_id, size, color, vaccinated, spayed_neutered, microchip_id, health_condition, location, personality, pet_behaviour, health_record) 
              VALUES ('$name', '$breed', '$age', '$category', '$special_instructions', '$image_path', 'available', '$donor_id', '$size', '$color', '$vaccinated', '$spayed_neutered', '$microchip_id', '$health_condition', '$location', '$personality', '$pet_behaviour', '$health_record_path')";

    if ($conn->query($query) === TRUE) {
        echo "<div class='alert alert-success'>Pet has been successfully added!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - PetAdopt</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Custom styles omitted for brevity */
        
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('assets/images/pet-bg.jpg'); /* Add a pet-themed background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #2c3e50;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
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

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .alert {
            margin-top: 20px;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
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
            .dashboard-container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            .form-control {
                padding: 10px;
            }
        }
    
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2><?php echo getGreeting(); ?>, Upload Pet for Adoption</h2>
        <form method="POST" action="donor_dashboard.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Pet Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="breed" class="form-label">Breed:</label>
                <input type="text" id="breed" name="breed" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age:</label>
                <input type="number" id="age" name="age" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category:</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="bird">Bird</option>
                    <option value="reptile">Reptile</option>
                    <option value="small animal">Small Animal</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="special_instructions" class="form-label">Special Instructions:</label>
                <textarea id="special_instructions" name="special_instructions" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Size:</label>
                <input type="text" id="size" name="size" class="form-control">
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color:</label>
                <input type="text" id="color" name="color" class="form-control">
            </div>
            <div class="mb-3">
                <label for="vaccinated" class="form-label">Vaccinated:</label>
                <input type="checkbox" id="vaccinated" name="vaccinated" class="form-check-input">
            </div>
            <div class="mb-3">
                <label for="spayed_neutered" class="form-label">Spayed/Neutered:</label>
                <input type="checkbox" id="spayed_neutered" name="spayed_neutered" class="form-check-input">
            </div>
          
            <div class="mb-3">
                <label for="microchip_id" class="form-label">Microchip ID:</label>
                <input type="text" id="microchip_id" name="microchip_id" class="form-control">
            </div>
            <div class="mb-3">
                <label for="health_condition" class="form-label">Health Condition:</label>
                <textarea id="health_condition" name="health_condition" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location:</label>
                <input type="text" id="location" name="location" class="form-control">
            </div>
            <div class="mb-3">
                <label for="personality" class="form-label">Personality:</label>
                <textarea id="personality" name="personality" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
    <label for="pet_behaviour" class="form-label">Pet Behaviour:</label>
    <textarea id="pet_behaviour" name="pet_behaviour" class="form-control" rows="4"></textarea>
</div>

<div class="mb-3">
    <label for="health_record" class="form-label">Health Record (PDF):</label>
    <input type="file" name="health_record" id="health_record" class="form-control" accept=".pdf">
</div>


            <div class="mb-3">
                <label for="image" class="form-label">Pet Image:</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Pet</button>
            </div>
        </form>
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
