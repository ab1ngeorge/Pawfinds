<?php
session_start();
include('includes/db.php'); // Include your DB connection

// Check if the token is passed in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $stmt = $conn->prepare("SELECT user_id, reset_token_expiry FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    // If the token is found in the database
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $reset_token_expiry);
        $stmt->fetch();

        // Check if the token has expired
        if (strtotime($reset_token_expiry) > time()) {
            // Token is valid, show the password reset form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // Validate passwords
                if ($new_password == $confirm_password) {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    // Update the password in the database
                    $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?");
                    $update_stmt->bind_param("si", $hashed_password, $user_id);
                    if ($update_stmt->execute()) {
                        echo "Password has been successfully updated. <a href='ls.html'>Click here to login.</a>";
                    } else {
                        echo "Failed to update the password. Please try again later.";
                    }
                } else {
                    echo "Passwords do not match. Please try again.";
                }
            }
        } else {
            // Token has expired
            echo "The reset link has expired. Please request a new one.";
        }
    } else {
        // Token not found
        echo "Invalid or expired token. Please request a new password reset link.";
    }
} else {
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <form action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br><br>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
