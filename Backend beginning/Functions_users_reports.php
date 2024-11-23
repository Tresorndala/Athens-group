<?php
include 'config.php';  // Include the database connection
#include "mail_config.php";  // Include the mail configuration (if using PHPMailer)

// Get all users
function getAllUsers() {
    global $conn;
    $result = $conn->query("SELECT * FROM users");
    $users = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    return $users;
}

// Add a new user
function addUser($userName, $userEmail, $userPassword, $userRole) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (userName, userEmail, userPassword, userRole) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $userName, $userEmail, $userPassword, $userRole);

    if (!$stmt->execute()) {
        throw new Exception("Error adding user: " . $stmt->error);
    }

    $stmt->close();
}

// Delete a user
function deleteUser($userID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE userID = ?");
    $stmt->bind_param("i", $userID);

    if (!$stmt->execute()) {
        throw new Exception("Error deleting user: " . $stmt->error);
    }

    $stmt->close();
}

// Update user role
function updateUserRole($userID, $newRole) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET userRole = ? WHERE userID = ?");
    $stmt->bind_param("si", $newRole, $userID);

    if (!$stmt->execute()) {
        throw new Exception("Error updating user role: " . $stmt->error);
    }

    $stmt->close();
}

// Add a new report
function addReport($userID, $maintenanceTypeID, $statusID, $description, $location) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO report (userID, maintenanceTypeID, statusID, description, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $userID, $maintenanceTypeID, $statusID, $description, $location);

    if (!$stmt->execute()) {
        throw new Exception("Error adding report: " . $stmt->error);
    }

    $stmt->close();
}

// Delete a report
function deleteReport($reportID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM report WHERE reportID = ?");
    $stmt->bind_param("s", $reportID);

    if (!$stmt->execute()) {
        throw new Exception("Error deleting report: " . $stmt->error);
    }

    $stmt->close();
}

// Update report status and handle email notification if status changes to "Completed"
function updateReportStatus($reportID, $newStatusID) {
    global $conn;
    $stmt = $conn->prepare("UPDATE report SET statusID = ? WHERE reportID = ?");
    $stmt->bind_param("is", $newStatusID, $reportID);

    if (!$stmt->execute()) {
        throw new Exception("Error updating report status: " . $stmt->error);
    }

    $stmt->close();

    // If status changes to "Completed" (ID = 3), send an email notification
    if ($newStatusID == 3) {
        $stmt = $conn->prepare("SELECT u.userEmail, u.userName FROM report r JOIN user u ON r.userID = u.userID WHERE r.reportID = ?");
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

// Get all reports
function getAllReports() {
    global $conn;
    $result = $conn->query("SELECT * FROM report");
    $reports = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
    }

    return $reports;
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

// Function to get the status name by ID
function getStatusById($statusID) {
    global $conn;
    $stmt = $conn->prepare("SELECT statusName FROM status WHERE statusID = ?");
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
