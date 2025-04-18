<?php
session_start();
include('includes/header.php'); // Include common header file
include('includes/db.php'); // Include database connection

// Include PHPMailer class files (ensure PHPMailer is installed via Composer or manually)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust path if using Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Trim whitespace from the email input

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT user_id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User found, proceed with password reset token generation
        $token = bin2hex(random_bytes(32)); // Generate a secure random token (32 bytes)
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expiry time (1 hour from now)

        // Update database with the reset token and expiry time
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $token, $expiry, $email);
        $updateStmt->execute();

        // Send password reset email using PHPMailer
        $resetLink = "http://localhost/pet/reset-password.php?token=" . urlencode($token); // URL encode the token

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'pawfind8@gmail.com'; // Use your Gmail address
            $mail->Password = 'iruo dqvk whxv cwfx';   // App Password (not regular Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('pawfind8@gmail.com', 'PAWFIND');
            $mail->addAddress($email); // Add recipient email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello, <br><br>To reset your password, click the link below:<br><br><a href='" . $resetLink . "'>Reset Password</a><br><br>This link will expire in 1 hour.";

            // Send email
            $mail->send();
            echo 'A password reset link has been sent to your email.';
        } catch (Exception $e) {
            echo "Failed to send the reset email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // If email is not found in the database
        echo "No user found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .message {
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }

        .error {
            margin-top: 20px;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Forgot Password</h1>
       

        <div class="footer">
            <p>Remembered your password? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>
</html>
