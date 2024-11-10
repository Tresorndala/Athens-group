<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in by verifying the session ID
if (!isset($_SESSION['userID'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: login.php');
    exit(); // Stop further script execution after redirect
}

// Check if the user role is 'admin' and allow access to the page
if ($_SESSION['userRole'] != 'admin') {
    // If the user role is not 'admin', redirect to the login page
    header('Location: login.php');
    exit(); // Stop further script execution if the role is not 'admin'
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to the Admin Dashboard, <?php echo htmlspecialchars($_SESSION['userEmail']); ?>!</h1>
    <p>You're logged in successfully as an admin.</p>
    <p>This is your admin space where you can manage user requests, change statuses, and more.</p>
    
    <!-- Links for admin management (e.g., change statuses) -->
    <ul>
        <li><a href="manage_requests.php">Manage Requests</a></li>
        <li><a href="change_status.php">Change User Statuses</a></li>
        <!-- Add other admin-related management links here -->
    </ul>

    <!-- Link to log out -->
    <a href="logout.php">Logout</a>
</body>
</html>
