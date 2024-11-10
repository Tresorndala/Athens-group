<?php
// Include the database configuration file to connect to the database
include 'config.php';

// Enable error reporting to display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect and trim form data, using null coalescing to avoid undefined index errors
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? ''); // Ensure 'contact' is set
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Check if any required fields are empty
    if (empty($full_name) || empty($email) || empty($contact) || empty($password) || empty($confirm_password)) {
        die('Please fill in all required fields.'); // Stop execution if any field is empty
    }

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        die('Passwords do not match.'); // Stop execution if passwords do not match
    }

    // Check if the database connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the 'User' table exists in the database
    $result = $conn->query("SHOW TABLES LIKE 'User'");
    if ($result->num_rows == 0) {
        die('The table "User" does not exist in the database.');
    }

    // Prepare a statement to check if the email is already registered in the database
    $stmt = $conn->prepare('SELECT userEmail FROM User WHERE userEmail = ?');
    $stmt->bind_param('s', $email); // Bind the email parameter to the query
    $stmt->execute(); // Execute the query
    $results = $stmt->get_result(); // Get the result of the query

    // Check if the email already exists in the database
    if ($results->num_rows > 0) {
        echo '<script>alert("User already registered.");</script>';
        echo '<script>window.location.href = "register.html";</script>';
    } else {
        // Hash the password for security before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Set the default user role to 'Regular'
        $user_role = 'Regular'; // Default role for new users

        // Prepare an INSERT statement to add the new user to the database
        $query = 'INSERT INTO User (userName, userEmail, userContact, userPassword, userRole) VALUES (?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $full_name, $email, $contact, $hashed_password, $user_role);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            header('Location: Login.php'); // Redirect to the login page if successful
        } else {
            echo '<script>alert("Registration failed. Please try again.");</script>';
            echo '<script>window.location.href = "register.html";</script>';
        }
    }

    // Close the statement after execution
    $stmt->close();
}

// Close the database connection at the end
$conn->close();
?>

?>
