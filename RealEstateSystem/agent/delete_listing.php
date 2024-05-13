<?php
session_start(); // Start the PHP session

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "res";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['listing_id'])) {
    $listingId = $_GET['listing_id'];

    $sql = "DELETE FROM PropertyListings WHERE listing_id = $listingId";

    if ($conn->query($sql) === TRUE) {
        header("Location: agent.php");
        exit();
    } else {
        echo "Error deleting listing: " . $conn->error;
    }
} else {
    echo "Listing ID not provided.";
    exit();
}

$conn->close();
?>
