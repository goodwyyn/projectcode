<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mentalhealth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch login credentials
$email = $_POST['email'];
$password = $_POST['password'];

// Query to check in Patient table
$query = "SELECT * FROM Patient WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    // Patient found, redirect to home.html
    header("Location: home.html");
    exit();
}

// Query to check in Doctor table
$query = "SELECT * FROM Doctor WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    // Doctor found, redirect to home.html
    header("Location: home.html");
    exit();
}

// Query to check in Admin table
$query = "SELECT * FROM Admin WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    // Admin found, redirect to admin.html
    header("Location: admin.php");
    exit();
}

// If no user found, redirect back to login page
header("Location: login.html");
exit();

$conn->close();
?>
