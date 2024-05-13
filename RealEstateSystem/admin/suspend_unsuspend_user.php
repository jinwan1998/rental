<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RES";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$user_id = $_GET['id'];
$action = $_GET['action'];


$new_status = ($action === 'suspend') ? 0 : 1;


$sql = "UPDATE Users SET is_active=$new_status WHERE user_id=$user_id";

if ($conn->query($sql) === TRUE) {
    echo "User " . ($action === 'suspend' ? 'suspended' : 'unsuspended') . " successfully";
} else {
    echo "Error " . ($action === 'suspend' ? 'suspending' : 'unsuspending') . " user: " . $conn->error;
}

// Close connection
$conn->close();


header("Location: admin.php?action=users");
exit();
?>
