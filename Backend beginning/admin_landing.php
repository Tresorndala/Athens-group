<?php
session_start();
include "Functions_users_reports.php"; // Include the functions file
include "core.php"; // Include the login check function (isLogin)
isLogin(); // Check if the user is logged in

// Handle form submissions for adding, updating, and deleting
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // For user operations
        if (isset($_POST['addUser'])) {
            $userName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
            $userEmail = filter_input(INPUT_POST, 'userEmail', FILTER_VALIDATE_EMAIL);
            $userPassword = filter_input(INPUT_POST, 'userPassword', FILTER_SANITIZE_STRING);
            $userRole = filter_input(INPUT_POST, 'userRole', FILTER_SANITIZE_STRING);

            if ($userName && $userEmail && $userPassword && $userRole) {
                addUser($userName, $userEmail, $userPassword, $userRole);
                $_SESSION['message'] = "User added successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                throw new Exception("Invalid user data provided.");
            }
        } elseif (isset($_POST['deleteUser'])) {
            $userID = filter_input(INPUT_POST, 'deleteUser', FILTER_VALIDATE_INT);
            deleteUser($userID);
            $_SESSION['message'] = "User deleted successfully.";
            $_SESSION['message_type'] = "success";
        } elseif (isset($_POST['updateUserRole'])) {
            $userID = filter_input(INPUT_POST, 'updateUserRole', FILTER_VALIDATE_INT);
            $newRole = filter_input(INPUT_POST, 'newRole', FILTER_SANITIZE_STRING);
            updateUserRole($userID, $newRole);
            $_SESSION['message'] = "User role updated successfully.";
            $_SESSION['message_type'] = "success";
        }

        // For report operations
        if (isset($_POST['addReport'])) {
            $userID = filter_input(INPUT_POST, 'userID', FILTER_VALIDATE_INT);
            $maintenanceTypeID = filter_input(INPUT_POST, 'maintenanceTypeID', FILTER_VALIDATE_INT);
            $statusID = filter_input(INPUT_POST, 'statusID', FILTER_VALIDATE_INT);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

            if ($userID && $maintenanceTypeID && $statusID && $description && $location) {
                addReport($userID, $maintenanceTypeID, $statusID, $description, $location);
                $_SESSION['message'] = "Report added successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                throw new Exception("Invalid report data provided.");
            }
        } elseif (isset($_POST['deleteReport'])) {
            $reportID = filter_input(INPUT_POST, 'deleteReport', FILTER_SANITIZE_STRING);
            deleteReport($reportID);
            $_SESSION['message'] = "Report deleted successfully.";
            $_SESSION['message_type'] = "success";
        } elseif (isset($_POST['updateReportStatus'])) {
            $reportID = filter_input(INPUT_POST, 'updateReportStatus', FILTER_SANITIZE_STRING);
            $newStatusID = filter_input(INPUT_POST, 'newStatusID', FILTER_VALIDATE_INT);
            updateReportStatus($reportID, $newStatusID);
            $_SESSION['message'] = "Report status updated successfully.";
            $_SESSION['message_type'] = "success";
        }

        header("Location: admin_landing.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: admin_landing.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/cb76afc7c2.js" crossorigin="anonymous"></script>
    <script src="disable-submit.js"></script> <!-- Prevent multiple submissions -->
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Admin Dashboard</h1>

        <!-- Feedback Section -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-{$_SESSION['message_type']}'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <!-- Manage Users Section -->
        <div class="mb-5">
            <h2>Manage Users</h2>
            <form method="POST" class="mb-3">
                <input type="text" name="userName" class="form-control mb-2" placeholder="Name" required>
                <input type="email" name="userEmail" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="userPassword" class="form-control mb-2" placeholder="Password" required>
                <select name="userRole" class="form-control mb-2" required>
                    <option value="Regular">Regular</option>
                    <option value="Admin">Admin</option>
                </select>
                <button type="submit" name="addUser" class="btn btn-primary">Add User</button>
            </form>
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = getAllUsers();
                    foreach ($users as $user) {
                        echo "<tr>
                                <td>{$user['userID']}</td>
                                <td>{$user['userName']}</td>
                                <td>{$user['userEmail']}</td>
                                <td>{$user['userRole']}</td>
                                <td>
                                    <form action='' method='POST' style='display:inline;'>
                                        <button type='submit' name='deleteUser' value='{$user['userID']}' class='btn btn-danger btn-sm'>Delete</button>
                                    </form>
                                    <form action='' method='POST' style='display:inline;'>
                                        <select name='newRole' class='form-control-sm'>
                                            <option value='Admin' " . ($user['userRole'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                                            <option value='Regular' " . ($user['userRole'] == 'Regular' ? 'selected' : '') . ">Regular</option>
                                        </select>
                                        <button type='submit' name='updateUserRole' value='{$user['userID']}' class='btn btn-warning btn-sm'>Update Role</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Generate Analytics Section -->
        <div class="mb-5">
            <h2>Generate Analytics</h2>
            <form method="POST" action="generate_analytics.php">
        <button type="submit" name="generateAnalytics" class="btn btn-success">Generate Analytics</button>
        </form>
         </div>


        <!-- Manage Reports Section -->
        <div class="mb-5">
            <h2>Manage Reports</h2>
            <form method="POST" class="mb-3">
                <select name="userID" class="form-control mb-2" required>
                    <?php
                    $users = getAllUsers();
                    foreach ($users as $user) {
                        echo "<option value='{$user['userID']}'>{$user['userName']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="description" class="form-control mb-2" placeholder="Description" required>
                <input type="text" name="location" class="form-control mb-2" placeholder="Location" required>
                <select name="maintenanceTypeID" class="form-control mb-2" required>
                    <option value="1">Electrical</option>
                    <option value="2">Plumbing</option>
                    <option value="3">HVAC</option>
                    <option value="4">General Repairs</option>
                    <option value="5">Cleaning</option>
                </select>
                <select name="statusID" class="form-control mb-2" required>
                    <option value="1">Pending</option>
                    <option value="2">In Progress</option>
                    <option value="3">Completed</option>
                    <option value="4">Cancelled</option>
                </select>
                <button type="submit" name="addReport" class="btn btn-primary">Add Report</button>
            </form>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reports = getAllReports();
                    foreach ($reports as $report) {
                        $statusID = isset($report['statusID']) ? $report['statusID'] : null;
                        echo "<tr>
                                <td>{$report['reportID']}</td>
                                <td>{$report['description']}</td>
                                <td>{$report['location']}</td>
                                <td>{$report['submissionDate']}</td>
                                <td>{$statusID}</td>
                                <td>
                                    <form action='' method='POST' style='display:inline;'>
                                        <button type='submit' name='deleteReport' value='{$report['reportID']}' class='btn btn-danger btn-sm'>Delete</button>
                                    </form>
                                    <form action='' method='POST' style='display:inline;'>
                                        <select name='newStatusID' class='form-control-sm'>
                                            <option value='1' " . ($statusID == 1 ? 'selected' : '') . ">Pending</option>
                                            <option value='2' " . ($statusID == 2 ? 'selected' : '') . ">In Progress</option>
                                            <option value='3' " . ($statusID == 3 ? 'selected' : '') . ">Completed</option>
                                            <option value='4' " . ($statusID == 4 ? 'selected' : '') . ">Cancelled</option>
                                        </select>
                                        <button type='submit' name='updateReportStatus' value='{$report['reportID']}' class='btn btn-warning btn-sm'>Update Status</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
