<?php
session_start();


// Include database connection
include('includes/db.php'); // Make sure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Trim whitespace from the email input

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-error'>Invalid email format.</div>";
        exit;
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT user_id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a reset token
        $token = bin2hex(random_bytes(32)); // Generate a secure random token
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Set token expiry to 1 hour

        // Update the database with the token and expiry time
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Send password reset email
        $resetLink = "http://localhost/pet/reset-password.php?token=" . urlencode($token);

        // Email content (you can customize this message)
        $subject = "Password Reset Request";
        $message = "Hello, \n\nTo reset your password, click the link below:\n\n" . $resetLink;
        $headers = "From: no-reply@pawfind.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<div class='alert alert-success'>A password reset link has been sent to your email.</div>";
        } else {
            echo "<div class='alert alert-error'>Failed to send the reset email. Please try again later.</div>";
        }
    } else {
        echo "<div class='alert alert-error'>No user found with that email address.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .forgot-password-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        h2 {
            margin-bottom: 1rem;
            font-size: 1.8rem;
            color: #333;
        }

        p {
            margin-bottom: 1.5rem;
            color: #666;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        input[type="email"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus {
            border-color: #6a11cb;
            outline: none;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }

        .alert {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <p>Please enter your email address to receive a password reset link.</p>

        <!-- Form to capture email -->
        <form action="forgotpassword.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>