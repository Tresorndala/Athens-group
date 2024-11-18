<?php
include "config.php";  // Include the database connection
#include "mail_config.php";  // Include the mail configuration (if using PHPMailer)

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to get all users
function getAllUsers() {
    global $conn;
    $sql = "SELECT userID, userName, userEmail, userRole FROM User";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Function to add a user
function addUser($userName, $userEmail, $userPassword, $userRole) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO User (userName, userEmail, userpassword, userRole) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $userName, $userEmail, $userPassword, $userRole);
    $stmt->execute();
    $stmt->close();
}

// Function to delete a user
function deleteUser($userId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM User WHERE userID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
}

// Function to update a user's role
function updateUserRole($userId, $newRole) {
    global $conn;
    $stmt = $conn->prepare("UPDATE User SET userRole = ? WHERE userID = ?");
    $stmt->bind_param("si", $newRole, $userId);
    $stmt->execute();
    $stmt->close();
}

// Function to get all reports
function getAllReports() {
    global $conn;
    $sql = "SELECT reportID, userID, maintenanceTypeID, statusID, description, location, submissionDate, completionDate FROM report";
    $result = $conn->query($sql);
    $reports = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
    }
    return $reports;
}

// Function to add a report
function addReport($userID, $maintenanceTypeID, $statusID, $description, $location) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO report (userID, maintenanceTypeID, statusID, description, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $userID, $maintenanceTypeID, $statusID, $description, $location);
    $stmt->execute();
    $stmt->close();
}

// Function to delete a report
function deleteReport($reportID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM report WHERE reportID = ?");
    $stmt->bind_param("i", $reportID);
    $stmt->execute();
    $stmt->close();
}

// Function to update report status
function updateReportStatus($reportID, $newStatusID) {
    global $conn;
    $stmt = $conn->prepare("UPDATE report SET statusID = ? WHERE reportID = ?");
    $stmt->bind_param("ii", $newStatusID, $reportID);
    $stmt->execute();

    // If status is updated to "Completed", send an email to the user
    if ($newStatusID == 3) {  // Status ID 3 represents "Completed"
        // Get the user ID and email associated with the report
        $stmt = $conn->prepare("SELECT User.userEmail, User.userName FROM report JOIN User ON report.userID = User.userID WHERE report.reportID = ?");
        $stmt->bind_param("i", $reportID);
        $stmt->execute();
        $stmt->bind_result($userEmail, $userName);
        $stmt->fetch();
        $stmt->close();

        // Send email notification
        sendCompletionEmail($userEmail, $userName, $reportID);
    }

    $stmt->close();
}

// Function to get the status name by ID
function getStatusById($statusID) {
    global $conn;
    $stmt = $conn->prepare("SELECT statusName FROM Status WHERE statusID = ?");
    $stmt->bind_param("i", $statusID);
    $stmt->execute();
    $stmt->bind_result($statusName);
    $status = [];
    if ($stmt->fetch()) {
        $status['statusName'] = $statusName;
    }
    $stmt->close();
    return $status;
}

// Function to send an email when the report status is completed
function sendCompletionEmail($userEmail, $userName, $reportID) {
    $subject = "Maintenance Report Completed";
    $message = "
        <html>
        <head>
            <title>Maintenance Report Completed</title>
        </head>
        <body>
            <p>Dear {$userName},</p>
            <p>Your maintenance report (ID: {$reportID}) has been marked as <strong>Completed</strong>.</p>
            <p>Thank you for using our service!</p>
        </body>
        </html>
    ";

    // Use PHPMailer or PHP mail() function to send the email
    mail($userEmail, $subject, $message, "Content-type:text/html;charset=UTF-8");
}
?>

