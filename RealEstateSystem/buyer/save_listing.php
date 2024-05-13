<?php
session_start();

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


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['listing_id'])) {
    $listing_id = $_GET['listing_id'];
    $buyer_id = $_SESSION['user_id'];

    $save_query = "INSERT INTO SavedListings (buyer_id, listing_id) VALUES ($buyer_id, $listing_id)";
    if ($conn->query($save_query) === TRUE) {
        header("Location: buyer.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
