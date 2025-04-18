<?php
// Include database connection
include('includes/db.php');
session_start();

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Make sure to require PHPMailer's autoloader
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input and trim any extra spaces
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Input validation
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "All fields are required!";
        header("Location: register.php");
        exit();
    } else {
        // Check if the email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Please provide a valid email address!";
            header("Location: register.php");
            exit();
        }

        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error_message'] = "Email is already registered!";
            header("Location: register.php");
            exit();
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Send a welcome email to the new user
                try {
                    $mail = new PHPMailer(true);

                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'pawfind8@gmail.com'; // Use your Gmail address
                    $mail->Password = 'iruo dqvk whxv cwfx'; // App Password (not regular Gmail password)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('pawfind8@gmail.com', 'PAWFIND');
                    $mail->addAddress($email); // Add recipient email address

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to PAWFIND!';
                    $mail->Body    = "<h1>Welcome, $username!</h1><p>Thank you for registering with PAWFIND. We are excited to have you on board!</p>";

                    // Send the email
                    $mail->send();
                } catch (Exception $e) {
                    $_SESSION['error_message'] = "Error sending email: {$mail->ErrorInfo}";
                    header("Location: ls.html");
                    exit();
                }

                // Set success message in session
                $_SESSION['success_message'] = "Registration successful! You can now log in.";
                
                // Redirect to the login page
                header("Location: ls.html");
                exit();
            } else {
                $_SESSION['error_message'] = "Error: Could not register. Please try again later.";
                header("Location: ls.html");
                exit();
            }
        }
    }
}
?>
