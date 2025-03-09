<?php
$servername = "localhost";
$username = "root"; # change to your own local username
$password = ""; # change to your own password
$dbname = "pawadopt"; # create the database and make appropriate tables from 'DB_Queries.txt' 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn) {
//     echo "DB Connected Successfully";
// }

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
