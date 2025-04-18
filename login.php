<?php 
include('includes/db.php');
session_start();

$error = ''; // Variable to store error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Input validation
    if (empty($email) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password hash
            if (password_verify($password, $user['password'])) {
                // Store user info in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the dashboard or another page
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Wrong username or password!";
            }
        } else {
            $error = "Wrong username or password!";
        }
    }

    // If there's an error, redirect to ls.html with an error message
    if (!empty($error)) {
        echo "<script>
                alert('$error');
                window.location.href = 'ls.html';
              </script>";
        exit();
    }
}
?>
