<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFixIt - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-logo">
            <a href="index.html">CampusFixIt</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="progress.html">Track Progress</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Login</h1>
        <form id="loginForm" action="/login" method="POST" novalidate>
            <div class="input-space">
                <input type="email" id="email" placeholder="Email" name="email" required>
                <i class='bx bxs-user'></i>
                <div class="error-message" id="emailError"></div>
            </div>
            <div class="input-space">
                <input type="password" id="password" placeholder="Password" name="password" required>
                <i class='bx bxs-lock-alt'></i>
                <div class="error-message" id="passwordError"></div>
                <div class="password-requirements" id="passwordRequirements">
                    <div class="requirement" id="lengthReq">At least 8 characters long</div>
                    <div class="requirement" id="upperReq">Contains uppercase letter</div>
                    <div class="requirement" id="lowerReq">Contains lowercase letter</div>
                    <div class="requirement" id="numberReq">Contains number</div>
                    <div class="requirement" id="specialReq">Contains special character</div>
                </div>
            </div>
            <div class="remember-forgot">
                <label for="remember-me">
                    <input type="checkbox" id="remember-me"> Remember me
                </label>
                <a href="password-recovery.html">Forgot Password?</a>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <div class="register">
            <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>