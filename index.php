<?php
session_start();
include('includes/db.php');  // Include database connection
include('includes/header.php');  // Include header with navigation

// Get search and category filter values
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Base query to get available pets
$query = "SELECT * FROM pets WHERE status = 'available' AND pet_id NOT IN 
          (SELECT pet_id FROM requests WHERE status = 'approved')";
$pets_result = $conn->query($query);

// Prepare and execute the search filter if search keyword is provided
if (!empty($search)) {
    $search = "%" . $search . "%";  // Escape and prepare search term for LIKE
    $query .= " AND (name LIKE ? OR breed LIKE ? OR age LIKE ? OR special_instructions LIKE ?)";
}

// Add category filter if category is selected
if (!empty($category)) {
    $query .= " AND category = ?";
}

$stmt = $conn->prepare($query);

// Bind parameters to the query for search filter (if applicable)
if (!empty($search) && !empty($category)) {
    $stmt->bind_param("sssss", $search, $search, $search, $search, $category);
} elseif (!empty($search)) {
    $stmt->bind_param("ssss", $search, $search, $search, $search);
} elseif (!empty($category)) {
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$pets_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetAdopt - Find Your New Best Friend</title>
    <link rel="stylesheet" href="assets/css/homepage_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to PetAdopt!</h1>
            <p>Find your new best friend today. Browse through our adorable pets waiting for a loving home.</p>
        </div>
    </section>

    <!-- Search and Category Filter Form -->
    <section class="filter-section">
        <form method="GET" action="index.php" class="filter-form">
            <div class="form-group">
                <label for="category">Filter by Category:</label>
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    <option value="dog" <?php echo (isset($_GET['category']) && $_GET['category'] == 'dog') ? 'selected' : ''; ?>>Dog</option>
                    <option value="cat" <?php echo (isset($_GET['category']) && $_GET['category'] == 'cat') ? 'selected' : ''; ?>>Cat</option>
                    <option value="bird" <?php echo (isset($_GET['category']) && $_GET['category'] == 'bird') ? 'selected' : ''; ?>>Bird</option>

                    <option value="small_animal" <?php echo (isset($_GET['category']) && $_GET['category'] == 'small_animal') ? 'selected' : ''; ?>>Small Animal</option>
                    <option value="other" <?php echo (isset($_GET['category']) && $_GET['category'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Search by name, breed, age...">
            </div>
            <button type="submit" class="btn-filter">Filter</button>
        </form>
    </section>

   <!-- Display Available Pets -->
<section class="pet-list-section">
    <h2>Available Pets</h2>
    <div class="pet-list">
        <?php if ($pets_result->num_rows > 0): ?>
            <?php while ($pet = $pets_result->fetch_assoc()): ?>
                <div class="pet-item">
                    <img src="<?php echo !empty($pet['image']) ? htmlspecialchars($pet['image']) : 'uploads/default-pet.jpg'; ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" />
                    <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                    <p><?php echo htmlspecialchars($pet['breed']); ?> | Age: <?php echo htmlspecialchars($pet['age']); ?> years</p>
                    <a href="pet_details.php?pet_id=<?php echo htmlspecialchars($pet['pet_id']); ?>" class="btn-view">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-pets">No pets available for adoption at the moment.</p>
        <?php endif; ?>
    </div>
</section>
    <script src="assets\js\animations.js"></script>

    <?php include('includes/footer.php'); ?>
   
</body>
</html>