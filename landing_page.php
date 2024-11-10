<?php
// Include core functions and start session
include 'core.php';

// Include database configuration
include 'config.php';

// Check if the user is logged in
#isLogin(); // This will redirect to login.php if the user is not logged in

// Fetch the latest report status for the logged-in user
$userID = $_SESSION['user_id'];
$sql = "SELECT r.description, r.submissionDate, s.statusName
        FROM report r
        JOIN Status s ON r.statusID = s.statusID
        WHERE r.userID = ?
        ORDER BY r.submissionDate DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($description, $submissionDate, $statusName);

// Check if there's a report to display
$hasReport = $stmt->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Dashboard</title>
</head>
<body>
    <h1>Welcome to your dashboard, 
    <?php 
        // Check if 'name' exists in the session before using it
        echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') : "User"; 
    ?>!
    </h1>
    <p>You're logged in successfully as a regular user.</p>
    <p>This is your personal space where you can manage requests, check updates, and view your status.</p>

    <!-- Option to submit a new report -->
    <a href="submit-report.php">Submit a New Report</a>

    <!-- Display the status of the latest report if available -->
    <?php if ($hasReport): ?>
        <h2>Last Submitted Report</h2>
        <?php while ($stmt->fetch()): ?>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Submission Date:</strong> <?php echo htmlspecialchars($submissionDate, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($statusName, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reports submitted yet.</p>
    <?php endif; ?>

    <!-- Link to log out -->
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>

