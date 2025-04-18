<?php
session_start();
include('includes/db.php');  // Include the database connection
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

// Get the selected pet's ID from the URL
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];
    $query = "SELECT * FROM pets WHERE pet_id = '$pet_id' AND status = 'available'"; // Ensure the pet is available
    $pet_result = $conn->query($query);
    
    if ($pet_result->num_rows == 0) {
        echo "<div class='error-message'>Sorry, this pet is not available for adoption.</div>";
        exit();
    }

    $pet = $pet_result->fetch_assoc();  // Fetch the pet details
} else {
    echo "<div class='error-message'>No pet selected.</div>";
    exit();
}

// Handle the adoption request submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adopter_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $living_situation = $_POST['living_situation'];
    $pets_at_home = $_POST['pets_at_home'];
    $adopt_reason = $_POST['adopt_reason'];
    $pet_type = $_POST['pet_type'];
    $pet_age_range = $_POST['pet_age_range'];
    $pet_breed = $_POST['pet_breed'];
    $pet_gender = $_POST['pet_gender'];
    $pet_temperament = $_POST['pet_temperament'];
    $financial_ready = $_POST['financial_ready'];

    // Insert adoption request into the database
    $insert_request_query = "INSERT INTO requests (adopter_id, pet_id, full_name, phone, email, dob, address, living_situation, pets_at_home, adopt_reason, pet_type, pet_age_range, pet_breed, pet_gender, pet_temperament, financial_ready, status, created_at) 
                             VALUES ('$adopter_id', '$pet_id', '$full_name', '$phone', '$email', '$dob', '$address', '$living_situation', '$pets_at_home', '$adopt_reason', '$pet_type', '$pet_age_range', '$pet_breed', '$pet_gender', '$pet_temperament', '$financial_ready', 'pending', NOW())";

    if ($conn->query($insert_request_query) === TRUE) {
        echo "<div class='success-message'>Your adoption request has been submitted successfully!</div>";
    } else {
        echo "<div class='error-message'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt <?php echo $pet['name']; ?> - Pet Adoption</title>
    <link rel="stylesheet" href="assets\css\adopt_request.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1 class="page-title">Adopt <?php echo $pet['name']; ?></h1>

        <!-- Display Pet Details -->
        <div class="pet-details">
            <img src="<?php echo $pet['image']; ?>" alt="Pet Image" class="pet-image">
            <div class="pet-info">
                <h3><?php echo $pet['name']; ?></h3>
                <p><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
                <p><strong>Age:</strong> <?php echo $pet['age']; ?> years</p>
                <p><strong>Category:</strong> <?php echo ucfirst($pet['category']); ?></p>
                <p><strong>Special Instructions:</strong> <?php echo $pet['special_instructions']; ?></p>
            </div>
        </div>

        <!-- Adoption Request Form -->
        <h2 class="form-title">Submit Adoption Request</h2>
        <form method="POST" action="adopt_request.php?pet_id=<?php echo $pet_id; ?>" class="adoption-form">
            <div class="form-section">
                <h3>1. Personal Information</h3>
                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" id="full_name" required>

                <label for="phone">Phone Number:</label>
                <input type="text" name="phone" id="phone" required>

                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required>

                <label for="dob">Date of Birth (Age):</label>
                <input type="date" name="dob" id="dob" required>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>
            </div>

            <div class="form-section">
                <h3>2. Living Situation</h3>
                <label for="living_situation">Do you own or rent your home?</label>
                <select name="living_situation" id="living_situation">
                    <option value="own">Own</option>
                    <option value="rent">Rent</option>
                    <option value="other">Other</option>
                </select>

                <label for="pets_at_home">Do you have any pets at home?</label>
                <input type="text" name="pets_at_home" id="pets_at_home">
            </div>

            <div class="form-section">
                <h3>3. Pet Preferences</h3>
                <label for="pet_type">What type of pet are you interested in adopting?</label>
                <select name="pet_type" id="pet_type">
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="other">Other</option>
                </select>

                <label for="pet_age_range">Preferred age range of the pet:</label>
                <select name="pet_age_range" id="pet_age_range">
                    <option value="puppy">Puppy</option>
                    <option value="kitten">Kitten</option>
                    <option value="adult">Adult</option>
                    <option value="senior">Senior</option>
                </select>

                <label for="pet_breed">Breed preference (if any):</label>
                <input type="text" name="pet_breed" id="pet_breed">

                <label for="pet_gender">Gender preference:</label>
                <select name="pet_gender" id="pet_gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="no_preference">No Preference</option>
                </select>

                <label for="pet_temperament">Is there a specific temperament you are looking for?</label>
                <input type="text" name="pet_temperament" id="pet_temperament">
            </div>

            <div class="form-section">
                <h3>4. Financial Readiness</h3>
                <label for="financial_ready">Are you financially prepared to take on the cost of pet care?</label>
                <select name="financial_ready" id="financial_ready">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="form-section">
                <h3>5. Adoption Reason</h3>
                <label for="adopt_reason">Why do you want to adopt this pet?</label>
                <textarea name="adopt_reason" id="adopt_reason" required></textarea>
            </div>

            <button type="submit" class="submit-button">Submit Request</button>
        </form>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>