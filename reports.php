<?php
// reports.php

// Include the config file to establish the DB connection
include 'config.php';

// Include the core file for login check functionality
include 'core.php';

// Call isLogin() to check login status
#isLogin();

// Fetch reports from the database
$query = "SELECT * FROM report ORDER BY reportID";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Reports - CampusFixIt</title>
    <link rel="stylesheet" href="reports.css">
</head>
<body>
    <header>
        <h1>Recent Maintenance Reports</h1>
    </header>
    <main>
        <div class="report-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="report-card">
                    <span class="report-id">#FIX-<?php echo $row['report_id']; ?></span>
                    <h3><?php echo $row['description']; ?></h3>
                    <div class="report-details">
                        <div class="detail-item">
                            <span>Location: <?php echo $row['location']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span>Reported: <?php echo $row['date_reported']; ?></span>
                        </div>
                        <div class="detail-item">
                            <a href="progress.php?id=<?php echo $row['report_id']; ?>" class="status-badge">Track Progress</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 CampusFixIt</p>
    </footer>
</body>
</html>

