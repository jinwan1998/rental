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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="agent.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Agent'; ?>!</h1>
        <nav>
            <ul>
                <li><a href="../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>My Property Listings</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $agentId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                if ($agentId !== null) {
                    $listingsQuery = "SELECT * FROM PropertyListings WHERE agent_id = $agentId";
                    $listingsResult = $conn->query($listingsQuery);

                    if ($listingsResult && $listingsResult->num_rows > 0) {
                        while ($row = $listingsResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['description']}</td>";
                            echo "<td>{$row['property_type']}</td>";
                            echo "<td>$" . number_format($row['price'], 2) . "</td>";
                            echo "<td>{$row['location']}</td>";
                            echo "<td>{$row['status']}</td>";
                            echo "<td>{$row['created_at']}</td>";
                            echo "<td>
                                    <a href='edit_listing.php?listing_id={$row['listing_id']}'>Edit</a> | 
                                    <a href='delete_listing.php?listing_id={$row['listing_id']}' onclick='return confirm(\"Are you sure you want to delete this listing?\");'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No listings found.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Agent not logged in.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Reviews about Me</h2>
        <table>
            <thead>
                <tr>
                    <th>Buyer Name</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $reviewsQuery = "SELECT r.rating, r.comments, u.username AS buyer_name
                                 FROM Reviews r
                                 INNER JOIN Users u ON r.user_id = u.user_id
                                 WHERE r.agent_id = $agentId";
                $reviewsResult = $conn->query($reviewsQuery);

                if ($reviewsResult && $reviewsResult->num_rows > 0) {
                    while ($reviewRow = $reviewsResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$reviewRow['buyer_name']}</td>";
                        echo "<td>{$reviewRow['rating']}</td>";
                        echo "<td>{$reviewRow['comments']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No reviews found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <p><a href="create_listing.php">Create New Listing</a></p>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
