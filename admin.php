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

// Get list of registered users
$query_registered_users = "SELECT Name, Email, 'Patient' AS Role FROM Patient 
                           UNION 
                           SELECT Name, Email, 'Doctor' AS Role FROM Doctor 
                           UNION 
                           SELECT Name, Email, 'Admin' AS Role FROM Admin";
$result_registered_users = $conn->query($query_registered_users);

// Get list of logged-in users
$query_logged_in_users = "SELECT * FROM LoggedInUsers";
$result_logged_in_users = $conn->query($query_logged_in_users);

// Fetch activities
// Assuming you have activity logs stored in a table named ActivityLogs
$query_activities = "SELECT * FROM ActivityLogs";
$result_activities = $conn->query($query_activities);

// Function to reset password
function resetPassword($email, $conn) {
    // Generate a random password
    $newPassword = generateRandomPassword();
    
    // Update user's password in the database
    $sql = "UPDATE Users SET Password = '$newPassword' WHERE Email = '$email'";
    if ($conn->query($sql) === TRUE) {
        return $newPassword;
    } else {
        return false;
    }
}

// Function to generate a random password
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Function to delete user
function deleteUser($email, $conn) {
    // Delete user from the database
    $sql = "DELETE FROM Users WHERE Email = '$email'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css"/>

    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #666;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        li > span {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }
        button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .reset-password {
            background-color: #5cb85c;
            color: #fff;
        }
        .delete-user {
            background-color: #d9534f;
            color: #fff;
        }
    </style>
</head>
<body>
<button class="logout-button" onclick="location.href='index.html';">Logout</button>
    <h1>Welcome Admin.</h1>
    <div class="container">
        <h2>Registered Users:</h2>
        <button onclick="showDetails('registered_users')">View Details</button>
        <div id="registered_users" style="display: none;">
            <ul>
                <?php
                while ($row = $result_registered_users->fetch_assoc()) {
                    echo "<li>";
                    echo "<span>Name:</span> " . $row['Name'] . "<br>";
                    echo "<span>Email:</span> " . $row['Email'] . "<br>";
                    echo "<span>Role:</span> " . $row['Role'] . "<br>";
                    echo "<button class='reset-password' onclick=\"resetPassword('" . $row['Email'] . "')\">Reset Password</button>";
                    echo "<button class='delete-user' onclick=\"deleteUser('" . $row['Email'] . "')\">Delete</button>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>

        <h2>Logged-in Users:</h2>
        <button onclick="showDetails('logged_in_users')">View Details</button>
        <div id="logged_in_users" style="display: none;">
            <ul>
                <?php
                while ($row = $result_logged_in_users->fetch_assoc()) {
                    echo "<li>" . $row['username'] . "</li>";
                }
                ?>
            </ul>
        </div>

        <h2>User Activities:</h2>
        <button onclick="showDetails('user_activities')">View Details</button>
        <div id="user_activities" style="display: none;">
            <ul>
                <?php
                while ($row = $result_activities->fetch_assoc()) {
                    echo "<li>" . $row['activity'] . "</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <script>
        function showDetails(sectionId) {
            var section = document.getElementById(sectionId);
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }

        function resetPassword(email) {
            var confirmed = prompt("Are you sure you want to reset the password for this user? Enter 'CONFIRM' to proceed:");
            if (confirmed === "CONFIRM") {
                // Call PHP script to reset password
                window.location.href = "reset_password.php?email=" + email;
            } else {
                alert("Password reset canceled.");
            }
        }

        function deleteUser(email) {
            var confirmed = confirm("Are you sure you want to delete this user?");
            if (confirmed) {
                // Call PHP script to delete user
                window.location.href = "delete_user.php?email=" + email;
            }
        }
    </script>
</body>
</html>
