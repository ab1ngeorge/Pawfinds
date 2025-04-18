<?php
session_start();
include('includes/db.php');  // Include the database connection
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ls.html");
    exit();
}

// Get search and category filter values
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Create a base query to fetch available pets, excluding pets that are approved
$query = "SELECT * FROM pets WHERE status = 'available' AND pet_id NOT IN 
          (SELECT pet_id FROM requests WHERE status = 'approved')";

// Prepare the SQL statement
$stmt = $conn->prepare($query);

// Add search condition if search keyword is provided
if (!empty($search)) {
    $searchTerm = "%$search%";
    $query .= " AND (name LIKE ? OR breed LIKE ? OR age LIKE ? OR special_instructions LIKE ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}

// Add category condition if a category is selected
if (!empty($category)) {
    $query .= " AND category = ?";
    $stmt = $conn->prepare($query);
    if (!empty($search)) {
        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $category);
    } else {
        $stmt->bind_param("s", $category);
    }
}

// Execute the query
$stmt->execute();
$pets_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pets for Adoption</title>
    <link rel="stylesheet" href="assets/css/adoptstyle.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1 class="page-title">Available Pets for Adoption</h1>

        <!-- Pet Category Filter and Search Bar -->
        <form method="GET" action="adopt.php" class="filter-form">
            <div class="form-group">
                <label for="category">Filter by Category:</label>
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    <option value="dog" <?php if (isset($_GET['category']) && $_GET['category'] == 'dog') echo 'selected'; ?>>Dog</option>
                    <option value="cat" <?php if (isset($_GET['category']) && $_GET['category'] == 'cat') echo 'selected'; ?>>Cat</option>
                    <option value="bird" <?php if (isset($_GET['category']) && $_GET['category'] == 'bird') echo 'selected'; ?>>Bird</option>
                    <option value="reptile" <?php if (isset($_GET['category']) && $_GET['category'] == 'reptile') echo 'selected'; ?>>Reptile</option>
                    <option value="small_animal" <?php if (isset($_GET['category']) && $_GET['category'] == 'small_animal') echo 'selected'; ?>>Small Animal</option>
                    <option value="other" <?php if (isset($_GET['category']) && $_GET['category'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" id="search" name="search" value="<?php if (isset($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>" placeholder="Search by name, breed, age...">
            </div>
            <button type="submit" class="btn-filter">Filter</button>
        </form>

        <!-- Display Available Pets -->
        <div class="pet-list">
            <?php if ($pets_result->num_rows > 0): ?>
                <?php while ($pet = $pets_result->fetch_assoc()): ?>
                    <div class="pet-card">
                        <!-- Display the pet image with fallback -->
                        <img src="<?php echo !empty($pet['image']) && file_exists($pet['image']) ? htmlspecialchars($pet['image']) : 'assets/images/default-pet.jpg'; ?>" alt="Pet Image" class="pet-image">
                        <div class="pet-details">
                            <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                            <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                            <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>
                            <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($pet['category'])); ?></p>
                            <p><strong>Special Instructions:</strong> <?php echo htmlspecialchars($pet['special_instructions']); ?></p>
                            <!-- "Adopt Me" Button to Go to the Adoption Request Page -->
                            <a href="adopt_request.php?pet_id=<?php echo $pet['pet_id']; ?>" class="btn-adopt">Adopt Me</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-pets">No pets available for adoption at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>