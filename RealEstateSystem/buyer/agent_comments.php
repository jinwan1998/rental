<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "res";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['agent_id'])) {
    $agent_id = $_GET['agent_id'];

    $sql = "SELECT u.username, r.rating, r.comments
            FROM Reviews r
            INNER JOIN Users u ON r.user_id = u.user_id
            WHERE r.agent_id = $agent_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Agent Comments</h2>";
        echo "<table>";
        echo "<thead><tr><th>User</th><th>Rating</th><th>Comment</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['rating']}</td>";
            echo "<td>{$row['comments']}</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No comments found for this agent.";
    }
} else {
    echo "Agent ID not specified.";
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Comments</title>
    <link rel="stylesheet" href="buyer.css"> 
    <a href="agent_ratings.php">Back to Agent Ratings</a>

   
</head>