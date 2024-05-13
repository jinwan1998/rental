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

$sql = "SELECT u.user_id, u.username, u.email, AVG(r.rating) AS avg_rating
        FROM Users u
        LEFT JOIN Reviews r ON u.user_id = r.agent_id
        WHERE u.role = 'Real Estate Agent'
        GROUP BY u.user_id, u.username, u.email";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Ratings & Reviews</title>
    <link rel="stylesheet" href="buyer.css"> 

    
</head>
<body>
    <h2>Agent Ratings & Reviews</h2>
    <a href="buyer.php">Home</a>

    <table>
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Email</th>
                <th>Average Rating</th>
                <th>View Comments</th>
                <th>Write Review</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>" . number_format($row['avg_rating'], 1) . "</td>";
                    echo "<td><a href='agent_comments.php?agent_id={$row['user_id']}'>View Comments</a></td>";
                    echo "<td><a href='write_review.php?agent_id={$row['user_id']}'>Write Review</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No agents found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
