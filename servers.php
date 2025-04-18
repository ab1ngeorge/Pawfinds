<?php
// Start the session
session_start();

// Database connection (Replace with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pet"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to fetch user data from the database
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify the password (assuming passwords are hashed)
        if (password_verify($password, $user['password'])) {
            // Check if the user is an admin
            if ($user['role'] === 'admin') {
                // Set session variables for the admin user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect to the admin dashboard
                header("Location: admin.php");
                exit(); // Ensure no further script is executed after the redirect
            } else {
                // If not an admin, show an error message
                $_SESSION['error'] = "You do not have admin privileges.";
                header("Location: #");
                exit();
            }
        } else {
            // If the password is incorrect
            $_SESSION['error'] = "Invalid password.";
            header("Location: #");
            exit();
        }
    } else {
        // If the username is not found in the database
        $_SESSION['error'] = "Username not found.";
        header("Location: #");
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php
        // Display any error message set during login failure
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']); // Clear the error after displaying it
        }
        ?>

        <!-- Login form -->
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
