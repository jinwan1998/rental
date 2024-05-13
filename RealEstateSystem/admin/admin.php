<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <style>
        /* Additional CSS styles can be added here */
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
    </style>
</head>
<body>
    <header>
        <h1>Welcome, System Administrator!</h1>
        <nav>
            <ul>
                <li><a href="admin.php?action=users">User Accounts</a></li>
                <li><a href="admin.php?action=profiles">User Profiles</a></li>
                <li><a href="Analytics.php?action=analytics">Analytics</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "RES";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $action = isset($_GET['action']) ? $_GET['action'] : 'users';

        if ($action === 'users') {
            echo "<h2>User Accounts</h2>";

            echo "<form method='GET'>";
            echo "<input type='hidden' name='action' value='users'>";
            echo "<label for='search'>Search by Username: </label>";
            echo "<input type='text' id='search' name='search' placeholder='Enter username'>";
            echo "<button type='submit'>Search</button>";
            echo "</form>";

            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Username</th>";
            echo "<th>Email</th>";
            echo "<th>Status</th>";
            echo "<th>Password</th>"; 
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $sql = "SELECT user_id, username, email, is_active, password FROM Users";

            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $conn->real_escape_string($_GET['search']);
                $sql .= " WHERE username LIKE '%$search%'";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>".($row['is_active'] ? 'Active' : 'Suspended')."</td>";
                    echo "<td>";
                    echo "<input type='password' value='{$row['password']}' id='password_{$row['user_id']}' disabled>"; // Password field with ID for dynamic toggle
                    echo "<input type='checkbox' onchange='togglePassword({$row['user_id']})'> Show Password"; // Checkbox for toggle
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='edit_user.php?id={$row['user_id']}'>Edit</a> | ";
                    if ($row['is_active']) {
                        echo "<a href='suspend_unsuspend_user.php?id={$row['user_id']}&action=suspend'>Suspend</a>";
                    } else {
                        echo "<a href='suspend_unsuspend_user.php?id={$row['user_id']}&action=unsuspend'>Unsuspend</a>";
                    }
                    echo " | <a href='delete_user.php?id={$row['user_id']}' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } elseif ($action === 'profiles') {

            echo "<h2>User Profiles</h2>";

            echo "<form method='GET'>";
            echo "<input type='hidden' name='action' value='profiles'>";
            echo "<label for='search'>Search by First Name or Last Name: </label>";
            echo "<input type='text' id='search' name='search' placeholder='Enter name'>";
            echo "<button type='submit'>Search</button>";
            echo "</form>";

            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Username</th>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Address</th>";
            echo "<th>Profile Picture</th>";
            echo "<th>Bio</th>";
            echo "<th>Status</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $sql = "SELECT u.username, up.user_id, up.first_name, up.last_name, up.address, up.profile_picture, up.bio, u.is_active
            FROM UserProfiles up
            INNER JOIN Users u ON up.user_id = u.user_id";

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql .= " WHERE u.username LIKE '%$search%'
                  OR up.first_name LIKE '%$search%'
                  OR up.last_name LIKE '%$search%'
                  OR up.address LIKE '%$search%'
                  OR up.profile_picture LIKE '%$search%'
                  OR up.bio LIKE '%$search%'
                  OR u.is_active LIKE '%$search%'";
    }

    $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['username']}</td>"; 
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['last_name']}</td>";
                    echo "<td>{$row['address']}</td>";
                    echo "<td>{$row['profile_picture']}</td>";
                    echo "<td>{$row['bio']}</td>";
                    echo "<td>".($row['is_active'] ? 'Active' : 'Suspended')."</td>";
                    echo "<td>
                            <a href='edit_profile.php?id={$row['user_id']}'>Edit</a>  
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No user profiles found</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }

        // Close connection
        $conn->close();
        ?>

        <script>
            function togglePassword(userId) {
                const passwordField = document.getElementById(`password_${userId}`);
                const checkbox = document.querySelector(`input[type='checkbox'][onchange='togglePassword(${userId})']`);

                if (checkbox.checked) {
                    passwordField.type = 'text'; 
                } else {
                    passwordField.type = 'password'; 
                }
            }
        </script>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>
