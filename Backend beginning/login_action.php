<?php
session_start(); // Start the session at the very top before any output

include 'config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die('Please fill in all required fields.');
    }

    $query = 'SELECT userID, userpassword, userName, userRole FROM User WHERE userEmail = ?';
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results->num_rows > 0) {
            $row = $results->fetch_assoc();
            $user_id = $row['userID'];
            $username = $row['userName'];
            $user_password = $row['userpassword'];
            $user_role = $row['userRole'];

            if (password_verify($password, $user_password)) {
                $_SESSION['id'] = $user_id;
                $_SESSION['userrole'] = $user_role ?: 'Regular';
                $_SESSION['username'] = $username;

                // Redirect based on role
                if ($_SESSION['userrole'] == 'Admin') {
                    header('Location: admin_landing.php');
                    exit();
                } else {
                    header('Location: landing_page.php');
                    exit();
                }
            } else {
                echo '<script>alert("Incorrect password, please try again.");</script>';
            }
        } else {
            echo '<script>alert("No user found with that email address.");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Database query error.");</script>';
    }
}

$conn->close(); // Close the connection only after the script ends
?>
