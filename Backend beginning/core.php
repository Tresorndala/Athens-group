<?php
// core.php

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define a function to check if the user is logged in
function isLogin() {
    if (!isset($_SESSION["user_id"]) && isset($_SESSION["name"])) {
        // Redirect to the login page if the user is not logged in
        //header("Location:login.php");
        return false;
    } else {
        // Return true if user_id and name are set in the session
        // return isset($_SESSION["user_id"]) && isset($_SESSION["name"]);
        return true;
    }
}

?>
