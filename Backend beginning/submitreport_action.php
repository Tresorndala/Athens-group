<?php
// Include the database configuration file
include 'config.php';

// Set Content-Type header for better response clarity
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate the inputs
    $userName = htmlspecialchars(trim($_POST['userName']));
    $userEmail = filter_var(trim($_POST['userEmail']), FILTER_SANITIZE_EMAIL);
    $maintenanceTypeID = intval($_POST['maintenanceType']);
    $location = htmlspecialchars(trim($_POST['location']));
    $description = htmlspecialchars(trim($_POST['description']));

    // Generate a unique Report ID if not provided
    $reportID = empty($_POST['reportID']) ? uniqid('report_') : $_POST['reportID'];

    // Ensure all fields are filled out
    if (empty($userName) || empty($userEmail) || empty($maintenanceTypeID) || empty($location) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate email format
    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    // Check if the user exists
    $stmt = $conn->prepare('SELECT userID FROM User WHERE userEmail = ?');
    $stmt->bind_param('s', $userEmail);
    $stmt->execute();
    $stmt->store_result();

    // If the user exists, fetch the userID
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userID);
        $stmt->fetch();
    } else {
        // If the user doesn't exist, insert the user into the database
        $stmt = $conn->prepare('INSERT INTO User (userName, userEmail) VALUES (?, ?)');
        $stmt->bind_param('ss', $userName, $userEmail);
        if ($stmt->execute()) {
            // After inserting the user, get their userID
            $userID = $stmt->insert_id;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting user into the database.']);
            exit;
        }
    }

    // Set the status to 'Pending' (statusID = 1)
    $statusID = 1;

    // Insert the maintenance report into the database with the unique reportID
    $stmt = $conn->prepare('INSERT INTO report (userID, maintenanceTypeID, statusID, description, location, submissionDate, reportID) VALUES (?, ?, ?, ?, ?, NOW(), ?)');
    $stmt->bind_param('iiisss', $userID, $maintenanceTypeID, $statusID, $description, $location, $reportID);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Respond with success and the unique Report ID
        echo json_encode([
            'success' => true, 
            'message' => 'Report submitted successfully.', 
            'reportID' => $reportID,
            'instructions' => 'You can track your report using this ID.'
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error submitting report.']);
        exit;
    }
}
?>
