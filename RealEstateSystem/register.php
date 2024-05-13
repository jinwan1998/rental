<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="Register.css">
</head>
<body>
    <header>
        <h1>Register for Real Estate System</h1>
    </header>

    <main>
        <form action="RegistrationController.php" method="post" class="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="System Administrator">System Administrator</option>
                <option value="Real Estate Agent">Real Estate Agent</option>
                <option value="Buyer">Buyer</option>
                <option value="Seller">Seller</option>
            </select><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br><br>
            
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name"><br><br>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name"><br><br>
            
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea><br><br>
            
            <label for="profile_picture">Profile Picture URL:</label>
            <input type="text" id="profile_picture" name="profile_picture"><br><br>
            
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio"></textarea><br><br>
            
            <input type="submit" value="Register">
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>
