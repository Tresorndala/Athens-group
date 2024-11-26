<?php
include 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = htmlspecialchars(trim($_POST['userName']));
    $userEmail = filter_var(trim($_POST['userEmail']), FILTER_SANITIZE_EMAIL);
    $maintenanceTypeID = intval($_POST['maintenanceType']);
    $location = htmlspecialchars(trim($_POST['location']));
    $description = htmlspecialchars(trim($_POST['description']));
    $reportID = uniqid('report_');

    if (empty($userName) || empty($userEmail) || empty($maintenanceTypeID) || empty($location) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    $stmt = $conn->prepare('SELECT userID FROM User WHERE userEmail = ?');
    $stmt->bind_param('s', $userEmail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userID);
        $stmt->fetch();
    } else {
        $stmt = $conn->prepare('INSERT INTO User (userName, userEmail) VALUES (?, ?)');
        $stmt->bind_param('ss', $userName, $userEmail);
        if ($stmt->execute()) {
            $userID = $stmt->insert_id;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting user.']);
            exit;
        }
    }

    $statusID = 1;
    $stmt = $conn->prepare('INSERT INTO report (userID, maintenanceTypeID, statusID, description, location, submissionDate, reportID) VALUES (?, ?, ?, ?, ?, NOW(), ?)');
    $stmt->bind_param('iiisss', $userID, $maintenanceTypeID, $statusID, $description, $location, $reportID);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'reportID' => $reportID,
            'message' => 'Report submitted successfully.',
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error submitting report.']);
    }
}
?>
