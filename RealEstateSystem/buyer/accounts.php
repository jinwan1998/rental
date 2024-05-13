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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch user information
$user_query = "SELECT * FROM Users WHERE user_id = $user_id";
$user_result = $conn->query($user_query);

// Query to fetch user's transactions
$transaction_query = "SELECT * FROM Transactions WHERE user_id = $user_id ORDER BY transaction_date DESC";
$transaction_result = $conn->query($transaction_query);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="buyer.css"> 

</head>
<body>
    <h2>My Account Information</h2>
    <a href="buyer.php">Home</a>

    <?php
    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        echo "<p><strong>Username:</strong> {$user['username']}</p>";
        echo "<p><strong>Email:</strong> {$user['email']}</p>";
        echo "<p><strong>Phone:</strong> {$user['phone']}</p>";
    } else {
        echo "<p>No user information found.</p>";
    }
    ?>

    <h2>Transaction History</h2>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($transaction_result->num_rows > 0) {
                while ($row = $transaction_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['transaction_id']}</td>";
                    echo "<td>{$row['amount']}</td>";
                    echo "<td>{$row['transaction_date']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No transactions found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
