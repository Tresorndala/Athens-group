<?php
// Start the session at the very top before any HTML output
session_start();

// Include the configuration file to connect to the database
include 'config.php';

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email or password fields are empty
    if (empty($email) || empty($password)) {
        die('Please fill in all required fields.'); // Stop execution if any field is empty
    }

    // Prepare a statement to retrieve user data from the database based on email
    $query = 'SELECT userID, userpassword, userName FROM User WHERE userEmail = ?';
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $email); // Bind the email parameter to the query
        $stmt->execute(); // Execute the query
        $results = $stmt->get_result(); // Get the result of the query

        // Check if a matching user is found
        if ($results->num_rows > 0) {
            // Fetch the user data from the result set
            $row = $results->fetch_assoc();
            $user_id = $row['userID'];
            $username = $row['userName'];
            $user_password = $row['userpassword'];

            // Verify the password entered by the user matches the hashed password in the database
            if (password_verify($password, $user_password)) {
                // Store user information in session variables for later access
                $_SESSION['userID'] = $user_id;
                $_SESSION['userName'] = $username;

                // Redirect the user to the landing page for regular users (no need to check for roles)
                header('Location: landing_page.php');
                exit(); // End script after redirect
            } else {
                // Incorrect password
                echo '<script>alert("Incorrect password. Please try again.");</script>';
            }
        } else {
            // User not found
            echo '<script>alert("User not registered. Please sign up first.");</script>';
        }

        // Close the statement after execution
        $stmt->close();
    } else {
        // Error preparing the statement
        echo '<script>alert("Database query error.");</script>';
    }
}

// Close the connection (optional, depending on your configuration)
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFixIt - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

