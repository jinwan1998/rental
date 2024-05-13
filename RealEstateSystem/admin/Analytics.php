<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input[type=text] {
            padding: 8px;
        }
        .search-container button {
            padding: 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Analytics - User Interactions & Transactions</h1>
        <nav>
            <ul>
                <li><a href="admin.php?action=users">User Accounts</a></li>
                <li><a href="admin.php?action=profiles">User Profiles</a></li>
                <li><a href="analytics.php">Analytics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="search-container">
            <form method="GET">
                <input type="hidden" name="action" value="analytics">
                <label for="search">Search by Username or Listing Title: </label>
                <input type="text" id="search" name="search" placeholder="Enter keyword">
                <button type="submit">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Interaction Type</th>
                    <th>Listing</th>
                    <th>Interaction Timestamp</th>
                </tr>
            </thead>
            <tbody>
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

                // Retrieve all user interactions from the database
                $sql = "SELECT u.username, pi.interaction_type, pl.title, pi.interaction_timestamp
                        FROM PropertyInteractions pi
                        INNER JOIN Users u ON pi.user_id = u.user_id
                        INNER JOIN PropertyListings pl ON pi.listing_id = pl.listing_id";

                // Handle search functionality
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = $conn->real_escape_string($_GET['search']);
                    $sql .= " WHERE u.username LIKE '%$search%' OR pl.title LIKE '%$search%'";
                }

                $sql .= " ORDER BY pi.interaction_timestamp DESC";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['interaction_type']}</td>";
                        echo "<td>{$row['title']}</td>";
                        echo "<td>{$row['interaction_timestamp']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No interactions found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>
