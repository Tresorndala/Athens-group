<?php
// admin_dashboard.php

include 'config.php'; // Database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin: 20px;
            width: 80%;
        }

        h1, h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .task-list {
            margin-bottom: 50px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        button {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        footer {
            margin-top: auto;
            padding: 15px;
            text-align: center;
            background-color: #333;
            color: white;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="container">
        <h2>Manage Users</h2>
        <form id="user-form">
            <input type="text" id="userInput" placeholder="Add User">
            <button type="submit" id="userInputBtn">Add User</button>
        </form>
        <ul id="userList" class="task-list"></ul>
    </div>

    <div class="container">
        <h2>Manage Reports</h2>
        <form id="report-form">
            <input type="text" id="reportInput" placeholder="Add Report">
            <button type="submit" id="reportInputBtn">Add Report</button>
        </form>
        <ul id="reportList" class="task-list"></ul>
    </div>

    <footer>
        <p>&copy; 2024 Admin Dashboard</p>
    </footer>

    <script src="todo_functions.js"></script>
    <script>
        // User Management Section
        const userInput = document.getElementById('userInput');
        const userInputBtn = document.getElementById('userInputBtn');
        const userList = document.getElementById('userList');
        document.getElementById('user-form').addEventListener('submit', (e) => {
            e.preventDefault();
        });

        userInputBtn.addEventListener('click', () => {
            const userText = userInput.value.trim();
            if (userText === '') {
                alert("Enter a valid user.");
                return;
            }
            create_task(userText, 'user');
            userInput.value = '';
        });

        document.addEventListener("DOMContentLoaded", () => read_task('user'));

        // Report Management Section
        const reportInput = document.getElementById('reportInput');
        const reportInputBtn = document.getElementById('reportInputBtn');
        const reportList = document.getElementById('reportList');
        document.getElementById('report-form').addEventListener('submit', (e) => {
            e.preventDefault();
        });

        reportInputBtn.addEventListener('click', () => {
            const reportText = reportInput.value.trim();
            if (reportText === '') {
                alert("Enter a valid report.");
                return;
            }
            create_task(reportText, 'report');
            reportInput.value = '';
        });

        document.addEventListener("DOMContentLoaded", () => read_task('report'));
    </script>
</body>
</html>
