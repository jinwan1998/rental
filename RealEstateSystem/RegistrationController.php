<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RES";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $profile_picture = $_POST['profile_picture'];
    $bio = $_POST['bio'];
    
    $sql = "INSERT INTO Users (username, password, role, email, phone) VALUES ('$username', '$password', '$role', '$email', '$phone')";
    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id; 
        
        $sql_profile = "INSERT INTO UserProfiles (user_id, first_name, last_name, address, profile_picture, bio) VALUES ('$user_id', '$first_name', '$last_name', '$address', '$profile_picture', '$bio')";
        if ($conn->query($sql_profile) === TRUE) {
            header("Location: login.php");
            exit;
        } else {
            echo "Error: " . $sql_profile . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
