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
// Include necessary files (Assuming you have a file to handle DB connection)
 // For checking login or other functionality

// Check if user is logged in (example); // Make sure the user is logged in before performing any actions

// Function to get all reports
function getAllReports() {
    global $conn;

    $sql = "SELECT 
                r.reportID, 
                u.userName, 
                u.userEmail, 
                m.typeName AS maintenanceType, 
                s.statusName AS statusName, 
                r.description, 
                r.location, 
                r.submissionDate, 
                r.completionDate
            FROM report r
            LEFT JOIN User u ON r.userID = u.userID
            LEFT JOIN MaintenanceType m ON r.maintenanceTypeID = m.maintenanceTypeID
            LEFT JOIN Status s ON r.statusID = s.statusID";

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

    $reportID = uniqid('report_');  // Generate unique ID for the report
    $stmt = $conn->prepare("INSERT INTO report 
                            (reportID, userID, maintenanceTypeID, statusID, description, location, submissionDate) 
                            VALUES (?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("siiiss", $reportID, $userID, $maintenanceTypeID, $statusID, $description, $location);
    $stmt->execute();
    $stmt->close();

    return $reportID;
}

// Function to delete a report
function deleteReport($reportID) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM report WHERE reportID = ?");
    $stmt->bind_param("s", $reportID);
    $stmt->execute();
    $stmt->close();
}

// Function to update report status
function updateReportStatus($reportID, $newStatusID) {
    global $conn;

    // Update the status in the report table
    $stmt = $conn->prepare("UPDATE report SET statusID = ? WHERE reportID = ?");
    $stmt->bind_param("is", $newStatusID, $reportID);
    $stmt->execute();
    $stmt->close();

    // If status changes to "Completed" (ID = 3), send an email notification
    if ($newStatusID == 3) {
        $stmt = $conn->prepare("SELECT u.userEmail, u.userName FROM report r JOIN User u ON r.userID = u.userID WHERE r.reportID = ?");
        $stmt->bind_param("s", $reportID);
        $stmt->execute();
        $stmt->bind_result($userEmail, $userName);
        $stmt->fetch();
        $stmt->close();

        if (!empty($userEmail)) {
            sendCompletionEmail($userEmail, $userName, $reportID);
        }
    }
}

// Function to get the status name by ID
function getStatusById($statusID) {
    global $conn;
    $stmt = $conn->prepare("SELECT statusName FROM Status WHERE statusID = ?");
    $stmt->bind_param("i", $statusID);
    $stmt->execute();
    $stmt->bind_result($statusName);
    $status = null;
    if ($stmt->fetch()) {
        $status = $statusName;
    }
    $stmt->close();
    return $status;
}

// Function to send email when the report status is completed
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

    // Send the email (make sure PHP mail settings are configured, or use a library like PHPMailer)
    mail($userEmail, $subject, $message, "Content-type:text/html;charset=UTF-8");
}

// Example usage (uncomment to test functions):

// Add a new report
// $newReportID = addReport(1, 2, 1, "Broken AC", "Room 101");
// echo "New report added: " . $newReportID;

// Delete a report by reportID
// deleteReport('report_67408a278e4d9');

// Update the status of a report
// updateReportStatus('report_67408a278e4d9', 3);

// Get all reports
// $allReports = getAllReports();
// print_r($allReports);
// Function to get total reports
function getTotalReports($conn) {
    $query = "SELECT COUNT(*) AS total FROM report"; // Count all reports
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0; // Return 0 if query fails
}

// Function to get completed reports
function getCompletedReports($conn) {
    $query = "SELECT COUNT(*) AS total FROM report WHERE statusID = 3"; // Assuming 3 is the ID for completed status
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0; // Return 0 if query fails
}

// Function to get pending reports
function getPendingReports($conn) {
    $query = "SELECT COUNT(*) AS total FROM report WHERE statusID = 1"; // Assuming 1 is the ID for pending status
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0; // Return 0 if query fails
}

// Function to get in-progress reports
function getInProgressReports($conn) {
    $query = "SELECT COUNT(*) AS total FROM report WHERE statusID = 2"; // Assuming 2 is the ID for in-progress status
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0; // Return 0 if query fails
}
?>


