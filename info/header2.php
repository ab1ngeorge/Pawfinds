<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, generate HTML for "Account" dropdown
    $accountLink = '<li><a href="../backend/logout.php">Logout</a></li>';
    $signInLink = '';
} else {
    // User is not logged in, generate HTML for "Sign Up/Log In" link
    $accountLink = '';
    $signInLink = '<li><a href="../signup.php">Sign In</a></li>';
}
?>

<header>
    <head>
        <link rel="stylesheet" href="../styles.css">
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="icon" type="icon/x-icon" href="../resources/PawFinds-4.png">
    </head>
    <div class="header-container">
        <a href="../home.php">
            <div class="logo-section">
                <img id="paw" src="../resources/PawFinds-3.png" alt="logo">
                <img id="paw2" src="../resources/PawFinds-5.png" alt="logo">
            </div>
        </a>
        <nav>
            <ul>
                <!-- Add icons to navigation links -->
                <li><a href="../home.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="../browse-pets.php"><i class="fas fa-paw"></i> Browse Pets</a></li>
                <li><a href="../adopt.php"><i class="fas fa-heart"></i> Adopt</a></li>
                <li><a href="../success-stories.php"><i class="fas fa-book"></i> Success Stories</a></li>
                <li><a href="../pet-care.php"><i class="fas fa-dog"></i> Pet Care</a></li>
                <li><a href="../volunteer.php"><i class="fas fa-handshake"></i> Get Involved</a></li>
                <li><a href="../donate.php"><i class="fas fa-donate"></i> Donate</a></li>
                <?php echo $accountLink; ?>
                <?php echo $signInLink; ?>
            </ul>
        </nav>
    </div>
</header>
