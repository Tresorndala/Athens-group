<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Report - CampusFixIt</title>
    <link rel="stylesheet" href="submit-report.css">
</head>
<body>
    <header>
        <h1>Submit a Maintenance Report</h1>
    </header>

    <main>
        <p>Please fill out the form below to report a maintenance issue on campus.</p>
        <form action="submitreport_action.php" method="POST" id="reportForm">
            <!-- User Name Input -->
            <label for="userName">Your Name:</label>
            <input type="text" id="userName" name="userName" placeholder="Enter your name" required>

            <!-- User Email Input -->
            <label for="userEmail">Your Email:</label>
            <input type="email" id="userEmail" name="userEmail" placeholder="Enter your email" required>

            <!-- Maintenance Type Select -->
            <label for="maintenanceType">Select Maintenance Type:</label>
            <select id="maintenanceType" name="maintenanceType" required>
                <option value="1">Electrical</option>
                <option value="2">Plumbing</option>
                <option value="3">HVAC</option>
                <option value="4">General Repairs</option>
                <option value="5">Cleaning</option>
            </select>

            <!-- Location Input -->
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter the location of the issue" required>

            <!-- Description Textarea -->
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" placeholder="Describe the issue" required></textarea>

            <button type="submit">Submit Report</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 CampusFixIt</p>
    </footer>
</body>
</html>
