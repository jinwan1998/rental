<?php
session_start(); 

if (isset($_SESSION['username'])) {
    header("Location: dashboard.php"); 
    exit();
}

$error_message = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 1) {
        $error_message = 'Invalid username or password. Please try again.';
    } elseif ($_GET['error'] == 'suspended') {
        $error_message = 'Your account is suspended. Please contact the administrator.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate System</title>
    <link rel="stylesheet" type="text/css" href="Login.css">
</head>
<body>
    <header>
        <h1>Login to Real Estate System</h1>
    </header>

    <main>
        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

        <form action="LoginController.php" method="post">
            <div class="login-form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                
                <input type="submit" value="Login">
            </div>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Real Estate System
    </footer>
</body>
</html>
