<?php
// submitreport_action.php

// Include the config file to establish the DB connection
include 'config.php';

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the file was uploaded
    if (isset($_FILES['reportImage']) && $_FILES['reportImage']['error'] == 0) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['reportImage']['name']);

        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['reportImage']['tmp_name'], $uploadFile)) {
            // Get the form data
            $userName = $_POST['userName'];  // User's name
            $userEmail = $_POST['userEmail'];  // User's email
            $maintenanceTypeID = $_POST['maintenanceType'];  // Selected maintenance type
            $description = $_POST['description'];  // Description of the issue
            $imagePath = $uploadFile;  // Path to the uploaded image

            // Check if the user already exists in the database using email
            $checkUserQuery = "SELECT userID FROM User WHERE userEmail = '$userEmail'";
            $result = $conn->query($checkUserQuery);

            // If the user exists, fetch the userID
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userID = $row['userID'];
            } else {
                // If the user doesn't exist, insert the user into the database
                $insertUserQuery = "INSERT INTO User (userName, userEmail) VALUES ('$userName', '$userEmail')";
                if ($conn->query($insertUserQuery) === TRUE) {
                    // After inserting the user, get their userID
                    $userID = $conn->insert_id;
                } else {
                    echo "Error inserting new user: " . $conn->error;
                    exit();
                }
            }

            // Set the status to 'Pending' (statusID = 1)
            $statusID = 1;

            // Insert the maintenance report into the database
            $query = "INSERT INTO report (userID, maintenanceTypeID, statusID, description, image_path, submissionDate) 
                      VALUES ('$userID', '$maintenanceTypeID', '$statusID', '$description', '$imagePath', NOW())";

            // Execute the query and check for success
            if ($conn->query($query) === TRUE) {
                // Redirect to a page confirming the report submission
                header('Location: submitted_report.php');
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or file error occurred.";
    }
}
?>
