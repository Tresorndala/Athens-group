<?php
// Include required files
include 'config.php';
require_once 'user_signup_email.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for messages
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($contact) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: register.html');
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
        header('Location: register.html');
        exit;
    }

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: register.html');
        exit;
    }

    try {
        // Check if email already exists
        $stmt = $conn->prepare('SELECT userEmail FROM User WHERE userEmail = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = 'User already registered.';
            header('Location: register.html');
            exit;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Set default user role
        $user_role = 'Regular';

        // Initialize email manager
        $emailManager = new SignupEmailManager('your-system-email@example.com');
        
        // Send welcome email and get user ID
        $emailResult = $emailManager->sendWelcomeEmail($email, $full_name);

        if ($emailResult['success']) {
            // If email was sent successfully, proceed with user registration
            $query = 'INSERT INTO User (userName, userEmail, userContact, userPassword, userRole, unique_id) 
                     VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssi', $full_name, $email, $contact, $hashed_password, $user_role, $emailResult['userId']);

            if ($stmt->execute()) {
                // Log successful registration
                error_log("New user registration: {$email} with ID: {$emailResult['userId']}");
                
                $_SESSION['success'] = 'Registration successful! Please check your email.';
                header('Location: Login.php');
                exit;
            } else {
                throw new Exception('Database insertion failed');
            }
        } else {
            // If email sending failed but we still want to register the user
            $query = 'INSERT INTO User (userName, userEmail, userContact, userPassword, userRole) 
                     VALUES (?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssss', $full_name, $email, $contact, $hashed_password, $user_role);

            if ($stmt->execute()) {
                $_SESSION['warning'] = 'Account created but welcome email could not be sent.';
                error_log("Email sending failed for {$email}: " . $emailResult['error']);
                header('Location: Login.php');
                exit;
            } else {
                throw new Exception('Database insertion failed');
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Registration failed. Please try again.';
        error_log("Registration error: " . $e->getMessage());
        header('Location: register.html');
        exit;
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}
//Yenma Is doing the email thing
