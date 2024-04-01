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

// Retrieve email parameter from URL
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Check if email is empty or not in a valid format
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Delete user from the appropriate table based on their role
    $sql = "DELETE FROM ";
    if (strpos($email, "@patient.") !== false) {
        $sql .= "Patient";
    } elseif (strpos($email, "@doctor.") !== false) {
        $sql .= "Doctor";
    } elseif (strpos($email, "@admin.") !== false) {
        $sql .= "Admin";
    } else {
        // If the email doesn't contain specific domain identifiers, default to a specific role
        $sql .= "Patient"; // Change this to the default role you want
    }
    $sql .= " WHERE Email = '$email'";

    if ($conn->query($sql) === TRUE) {
        // User deleted successfully, redirect back to admin page
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "Email parameter is missing.";
}

$conn->close();
?>
