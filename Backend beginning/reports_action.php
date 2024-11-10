<?php
// Include the configuration file to connect to the database
include 'config.php';

// Handle report submission (maintenance request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['reportImage'])) {
    // Retrieve and sanitize form data
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $userEmail = trim($_POST['userEmail']); // Assuming you pass the userEmail to identify the user
    $image = $_FILES['reportImage'];

    // Validate input
    if (empty($location) || empty($description) || empty($userEmail)) {
        die('Location, description, and user email are required.');
    }

    // Check if the user exists in the User table
    $stmt = $conn->prepare("SELECT userID FROM User WHERE userEmail = ?");
    $stmt->bind_param('s', $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die('User not found.');
    }
    $userID = $user['userID'];
    $stmt->close();

    // Handle image upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a valid format
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        die('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
    }

    // Move the uploaded file to the server
    if (!move_uploaded_file($image["tmp_name"], $targetFile)) {
        die('Sorry, there was an error uploading your file.');
    }

    // Insert the report details into the MaintenanceRequest table
    $stmt = $conn->prepare("INSERT INTO report (userID, maintenanceTypeID, statusID, description) VALUES (?, ?, ?, ?)");
    $maintenanceTypeID = 1; // Assuming '1' is a valid maintenance type (e.g., Electrical)
    $statusID = 1; // '1' means Pending
    $stmt->bind_param('iiis', $userID, $maintenanceTypeID, $statusID, $description);
    $stmt->execute();
    $stmt->close();

    echo '<script>alert("Maintenance request submitted successfully!");</script>';
}

// Handle report progress tracking (GET request with report ID)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['reportId'])) {
    $reportId = $_GET['reportId'];

    // Retrieve the report progress from the database
    $stmt = $conn->prepare("SELECT r.requestID, r.description, r.submissionDate, s.statusName 
                            FROM report r 
                            JOIN Status s ON r.statusID = s.statusID
                            WHERE r.requestID = ?");
    $stmt->bind_param('i', $reportId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Report ID: #" . $row['requestID'] . "<br>";
        echo "Description: " . $row['description'] . "<br>";
        echo "Submitted On: " . $row['submissionDate'] . "<br>";
        echo "Status: " . $row['statusName'];
    } else {
        echo "Report not found.";
    }

    $stmt->close();
}

// Retrieve and display all reports (for the "Recent Reports" page)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['reportId'])) {
    $stmt = $conn->prepare("SELECT r.requestID, r.description, u.userName, s.statusName 
                            FROM report r 
                            JOIN User u ON r.userID = u.userID
                            JOIN Status s ON r.statusID = s.statusID
                            ORDER BY r.requestID DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<div class='report-card'>
                <span class='report-id'>#FIX-" . $row['requestID'] . "</span>
                <h3>" . $row['description'] . "</h3>
                <div class='report-details'>
                    <div class='detail-item'>
                        <span>Reported by: " . $row['userName'] . "</span>
                    </div>
                    <div class='detail-item'>
                        <span>Status: " . $row['statusName'] . "</span>
                    </div>
                </div>
              </div>";
    }

    $stmt->close();
}

$conn->close();
?>
