<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFixIt - Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="nav-logo">
            <a href="#">CampusFixIt</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About</a></li>
        </ul>
    </div>

    <!-- Sign Up Form -->
    <div class="container">
        <h1>Sign Up</h1>
        <form action="/signup" method="POST">
            <div class="input-space">
                <input type="text" placeholder="Full Name" name="full_name" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-space">
                <input type="email" placeholder="Email" name="email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-space">
                <input type="password" placeholder="Password" name="password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-space">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="login-button">Sign Up</button>
        </form>
        <div class="register">
            <p>Already have an account? <a href="login.html">Login</a></p>
        </div>
    </div>
    <script src="signup.js"></script>
</body>
</html>