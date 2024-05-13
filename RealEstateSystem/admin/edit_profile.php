<?php
// Check if user ID is specified in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    echo "User ID not specified in the URL";
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data and update user profile details
    $new_first_name = $_POST['first_name'];
    $new_last_name = $_POST['last_name'];
    $new_address = $_POST['address'];
    $new_bio = $_POST['bio'];

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

    // Update user profile details in the database
    $sql = "UPDATE UserProfiles SET first_name='$new_first_name', last_name='$new_last_name', address='$new_address', bio='$new_bio' WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo "User profile updated successfully";
        // Redirect back to user profiles page
        header("Location: admin.php?action=profiles");
        exit;
    } else {
        echo "Error updating user profile: " . $conn->error;
    }

    // Close connection
    $conn->close();
}

// Retrieve user profile details for the specified user ID
// Database connection details (you can move this outside of the POST condition)
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

$user_profile = null;

// Retrieve user profile details
$sql = "SELECT * FROM UserProfiles WHERE user_id=$user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_profile = $result->fetch_assoc();
} else {
    echo "User profile not found";
    exit;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <style>
        /* Table styles */
        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit User Profile: <?php echo htmlspecialchars($user_profile['first_name'] . ' ' . $user_profile['last_name']); ?></h1>
    </header>

    <main>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $user_id; ?>">
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td><label for="first_name">First Name:</label></td>
                    <td><input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_profile['first_name']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="last_name">Last Name:</label></td>
                    <td><input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_profile['last_name']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="address">Address:</label></td>
                    <td><input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_profile['address']); ?>"></td>
                </tr>
                <tr>
                    <td><label for="bio">Bio:</label></td>
                    <td><textarea id="bio" name="bio"><?php echo htmlspecialchars($user_profile['bio']); ?></textarea></td>
                </tr>
            </table>
            <input type="submit" value="Update">
        </form>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>
