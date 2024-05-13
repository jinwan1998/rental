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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="seller.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Seller'; ?>!</h1>
        <nav>
            <ul>
                <li><a href="seller.php">My Property Listings</a></li>
                <li><a href="../buyer/agent_ratings.php">Rate Agents</a></li>
                <li><a href="logout.php">Logout</a></li>
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
                    <th>Views</th>
                    <th>Shortlisted</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve seller's user_id from session
                $sellerId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                if ($sellerId !== null) {
                    $listingsQuery = "SELECT pl.*, COUNT(pi.interaction_id) AS views, COUNT(sl.id) AS shortlisted
                                      FROM PropertyListings pl
                                      LEFT JOIN PropertyInteractions pi ON pl.listing_id = pi.listing_id AND pi.interaction_type = 'View'
                                      LEFT JOIN SavedListings sl ON pl.listing_id = sl.listing_id
                                      WHERE pl.agent_id = $sellerId
                                      GROUP BY pl.listing_id";
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
                            echo "<td>{$row['views']}</td>";
                            echo "<td>{$row['shortlisted']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No listings found.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Seller not logged in.</td></tr>";
                }
                ?>
            </tbody>
        </table>







    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>

<?php
$conn->close();
?>
