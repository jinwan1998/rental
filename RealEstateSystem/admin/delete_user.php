<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RES";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_GET['id'];

$sql = "DELETE FROM Users WHERE user_id=$user_id";

if ($conn->query($sql) === TRUE) {
    echo "User deleted successfully";
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();
?>
