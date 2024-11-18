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
        <nav class="navbar">
            <h1 class="logo">CampusFixIt</h1>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="submit-report.html">Submit Report</a></li>
                <li><a href="progress.html">Track Report</a></li>
                <li><a href="login.html">Login</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <h2>Track Report Progress</h2>
        <p>Enter your report ID below to check the current status of your report.</p>
        <input type="text" id="reportId" placeholder="Enter Report ID">
        <button onclick="trackReport()">Check Status</button>
        <p id="reportStatus" style="margin-top: 20px;"></p>
    </main>
    
    <footer>
        <p>&copy; 2024 CampusFixIt</p>
    </footer>

    <script>
        function trackReport() {
            const reportId = document.getElementById('reportId').value;
            const reportStatus = document.getElementById('reportStatus');

            if (reportId === "123") {
                reportStatus.innerText = "Status: In Progress";
            } else if (reportId === "456") {
                reportStatus.innerText = "Status: Resolved";
            } else if (reportId === "789") {
                reportStatus.innerText = "Status: Pending";
            } else {
                reportStatus.innerText = "Report not found. Please check the ID and try again.";
            }
        }
    </script>
</body>
</html>
