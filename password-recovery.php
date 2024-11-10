<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFixIt - Password Recovery</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <h1>Password Recovery</h1>
        <p>Please enter your email to receive a password reset link.</p>
        <form action="/password-recovery" method="POST">
            <div class="input-space">
                <input type="email" placeholder="Enter your email" name="email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <button type="submit" class="login-button">Send Recovery Link</button>
        </form>
        <div class="register">
            <p>Remembered your password? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
