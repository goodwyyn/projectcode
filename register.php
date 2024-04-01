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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['patient_signup']) || isset($_POST['doctor_signup']) || isset($_POST['admin_signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if password and confirm password match
    if ($password !== $confirmPassword) {
      echo "<script>alert('Password and Confirm Password do not match.'); window.location='registration_page.html';</script>";
      exit();
    }

    // Handle different types of signups
    if (isset($_POST['patient_signup'])) {
      $phone = $_POST['phone'];
      $sql = "INSERT INTO Patient (Name, Email, PhoneNumber, Password, ConfirmPassword)
              VALUES ('$name', '$email', '$phone', '$password', '$confirmPassword')";
      $redirect_page = 'home.html';
    } elseif (isset($_POST['doctor_signup'])) {
      $idNumber = $_POST['idNumber'];
      $RegNumber = $_POST['RegNumber'];
      $sql = "INSERT INTO Doctor (Name, Email, IdNumber, RegNumber, Password, ConfirmPassword)
              VALUES ('$name', '$email', '$idNumber', '$RegNumber', '$password', '$confirmPassword')";
      $redirect_page = 'home.html';
    } elseif (isset($_POST['admin_signup'])) {
      $hrNumber = $_POST['hrNumber'];
      $sql = "INSERT INTO Admin (Name, Email, HRNumber, Password, ConfirmPassword)
              VALUES ('$name', '$email', '$hrNumber', '$password', '$confirmPassword')";
      $redirect_page = 'admin.php';
    }

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
      // Redirect to the appropriate page with a success message
      echo "<script>alert('$name registered successfully'); window.location='$redirect_page';</script>";
      exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}

$conn->close();
?>
