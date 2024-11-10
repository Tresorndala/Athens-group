<?php
// Include database connection
include 'config.php';

// Start a session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Form validation
    if (empty($email) || empty($password)) {
        echo "Both fields are required!";
        exit();
    }

    // Check if the user exists
    $sql = "SELECT * FROM User WHERE userEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['userContact'])) {
            // Password matches
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['userName'] = $user['userName'];
            echo "Login successful! Welcome, " . $user['userName'] . ".";
        } else {
            // Incorrect password
            echo "Invalid password!";
        }
    } else {
        // User does not exist
        echo "No account found with that email!";
    }
}
?>
