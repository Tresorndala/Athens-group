<?php
// progress.php

// Include the config file to establish the DB connection
include 'config.php';

// Fetch report ID from the query string
$report_id = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch report details from the database
$query = "SELECT * FROM reports WHERE report_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $report = $result->fetch_assoc();
} else {
    echo "Report not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Report Progress - CampusFixIt</title>
    <link rel="stylesheet" href="progress.css">
</head>
<body>
    <header>
        <h1>Track Report Progress</h1>
    </header>
    <main>
        <h3>Report ID: #FIX-<?php echo $report['report_id']; ?></h3>
        <p><strong>Description:</strong> <?php echo $report['description']; ?></p>
        <p><strong>Status:</strong> <?php echo $report['status']; ?></p>
        <p><strong>Location:</strong> <?php echo $report['location']; ?></p>
        <p><strong>Reported:</strong> <?php echo $report['date_reported']; ?></p>
    </main>

    <footer>
        <p>&copy; 2024 CampusFixIt</p>
    </footer>
</body>
</html>
