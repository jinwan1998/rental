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

// Handle user authentication (login) if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
            case 'Buyer':
                header("Location: buyer.php");
                exit();
            case 'Seller':
                header("Location: seller.php");
                exit();
            default:
                header("Location: login.php?error=1");
                exit();
        }
    } else {
        header("Location: login.php?error=1");
        exit();
    }
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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['remove_listing_id'])) {
    $listing_id = $_GET['remove_listing_id'];
    $buyer_id = $_SESSION['user_id'];

    $remove_query = "DELETE FROM SavedListings WHERE buyer_id = $buyer_id AND listing_id = $listing_id";
    if ($conn->query($remove_query) === TRUE) {
        header("Location: buyer.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard - Real Estate System</title>
    <link rel="stylesheet" href="buyer.css"> 
</head>
<body>
    <header>
        <h1>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Buyer'; ?>!</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="mortgage_calculator.php">Mortgage Calculator</a></li>
                <li><a href="agent_ratings.php">Agent Ratings & Reviews</a></li>
                <li><a href="accounts.php">Account</a></li>
                <li><a href="../admin/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Property Listings Section -->
        <section>
            <h2>New Property Listings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Property Type</th>
                        <th>Price</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve and display new property listings
                    $listingsQuery = "SELECT * FROM PropertyListings WHERE status = 'Active'";
                    $listingsResult = $conn->query($listingsQuery);

                    if ($listingsResult->num_rows > 0) {
                        while ($row = $listingsResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['description']}</td>";
                            echo "<td>{$row['property_type']}</td>";
                            echo "<td>$" . number_format($row['price'], 2) . "</td>";
                            echo "<td>{$row['location']}</td>";
                            echo "<td><a href='buyer.php?listing_id={$row['listing_id']}'>Save</a></td>"; // Add save functionality
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No new listings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Saved Listings Section -->
        <section>
            <h2>Saved Listings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Property Type</th>
                        <th>Price</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve and display saved listings for the current user
                    if (isset($_SESSION['user_id'])) {
                        $buyerId = $_SESSION['user_id'];
                        $savedQuery = "SELECT p.* FROM PropertyListings p
                                       JOIN SavedListings s ON p.listing_id = s.listing_id
                                       WHERE s.buyer_id = $buyerId";
                        $savedResult = $conn->query($savedQuery);

                        if ($savedResult->num_rows > 0) {
                            while ($row = $savedResult->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row['title']}</td>";
                                echo "<td>{$row['description']}</td>";
                                echo "<td>{$row['property_type']}</td>";
                                echo "<td>$" . number_format($row['price'], 2) . "</td>";
                                echo "<td>{$row['location']}</td>";
                                echo "<td><a href='buyer.php?remove_listing_id={$row['listing_id']}'>Remove</a></td>"; // Add remove functionality
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No saved listings found.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>


        
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
