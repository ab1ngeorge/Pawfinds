<?php
session_start();
include('includes/header.php');
include('includes/db.php'); // Include the database connection

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch the pet_id for the adopted pet by the logged-in user
$pet_id = null;
$query = "SELECT pet_id FROM adopt_history WHERE adopter_id = ? AND status = 'approved' LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($pet_id);
$stmt->fetch();
$stmt->close();

if (!$pet_id) {
    $error = "You have not adopted any pets yet.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pet_id) {
    // Validate and sanitize inputs
    $story = isset($_POST['story']) ? trim($_POST['story']) : '';
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/success_stories/'; // Directory to store uploaded images
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
        }

        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . uniqid() . '_' . $file_name; // Unique file name to avoid conflicts

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image = $file_path; // Save the file path to the database
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please upload an image.";
    }

    // Insert data into the database
    if (!empty($story) && !empty($image)) {
        $query = "INSERT INTO success_stories (pet_id, adopter_id, story, image, created_at) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiss", $pet_id, $user_id, $story, $image);

        if ($stmt->execute()) {
            $success = "Success story submitted successfully!";
        } else {
            $error = "Failed to submit the story. Please try again.";
        }
    } else {
        $error = "Please fill out all fields and upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Your Success Story</title>
    <link rel="stylesheet" href="assets\css\storystyle.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1 class="page-title">Share Your Success Story</h1>

        <!-- Display success or error messages -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Success Story Form -->
        <?php if ($pet_id): ?>
            <form method="POST" action="success_story.php" enctype="multipart/form-data" class="story-form">
                <div class="form-group">
                    <label for="story">Your Story:</label>
                    <textarea id="story" name="story" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Upload Pet Picture:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>

                <button type="submit" class="btn-submit">Submit Story</button>
            </form>
        <?php else: ?>
            <p>You have not adopted any pets yet. Please adopt a pet to share your success story.</p>
        <?php endif; ?>
    </div>
</body>
</html>