<?php

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    echo "User ID not specified in the URL";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password']; 
    $new_role = $_POST['role'];

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


    $sql = "UPDATE Users SET username='$new_username', email='$new_email', password='$new_password', role='$new_role' WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo "User details updated successfully";

        header("Location: admin.php?action=users");
        exit;
    } else {
        echo "Error updating user details: " . $conn->error;
    }

    // Close connection
    $conn->close();
}



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

$user = null;

// Retrieve user details
$sql = "SELECT * FROM Users WHERE user_id=$user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
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
    <title>Edit User - Real Estate System</title>
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
        <h1>Edit User: <?php echo htmlspecialchars($user['username']); ?></h1>
    </header>

    <main>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $user_id; ?>">
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td><label for="username">Username:</label></td>
                    <td><input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="password">New Password:</label></td>
                    <td><input type="text" id="password" name="password"></td> <!-- Input type changed to text -->
                </tr>
                <tr>
                    <td><label for="role">Role:</label></td>
                    <td>
                        <select id="role" name="role">
                            <option value="System Administrator" <?php if ($user['role'] === 'System Administrator') echo 'selected'; ?>>System Administrator</option>
                            <option value="Real Estate Agent" <?php if ($user['role'] === 'Real Estate Agent') echo 'selected'; ?>>Real Estate Agent</option>
                            <option value="Buyer" <?php if ($user['role'] === 'Buyer') echo 'selected'; ?>>Buyer</option>
                            <option value="Seller" <?php if ($user['role'] === 'Seller') echo 'selected'; ?>>Seller</option>
                        </select>
                    </td>
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
