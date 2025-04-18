<?php
session_start();

include('includes/db.php'); // Include the database connection
include('includes/header.php');

// Fetch all success stories from the database
$query = "SELECT ss.story_id, ss.pet_id, ss.adopter_id, ss.story, ss.image, ss.created_at, 
                 p.name AS pet_name, p.breed, p.category, 
                 u.username AS adopter_name
          FROM success_stories ss
          JOIN pets p ON ss.pet_id = p.pet_id
          JOIN users u ON ss.adopter_id = u.user_id
          ORDER BY ss.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Stories</title>
    <link rel="stylesheet" href="assets/css/success_story_view.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1 class="page-title">Success Stories</h1>

        <!-- Display Success Stories -->
        <div class="story-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($story = $result->fetch_assoc()): ?>
                    <div class="story-card">
                        <!-- Display the pet image -->
                        <img src="<?php echo htmlspecialchars($story['image']); ?>" alt="Pet Image" class="story-image">

                        <div class="story-details">
                            <h2><?php echo htmlspecialchars($story['pet_name']); ?></h2>
                            <p><strong>Breed:</strong> <?php echo htmlspecialchars($story['breed']); ?></p>
                            <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($story['category'])); ?></p>
                            <p><strong>Adopter:</strong> <?php echo htmlspecialchars($story['adopter_name']); ?></p>
                            <p><strong>Story:</strong> <?php echo htmlspecialchars($story['story']); ?></p>
                            <p><small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($story['created_at'])); ?></small></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-stories">No success stories found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>