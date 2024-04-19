<?php
// Start the session
session_start();
// Unset all of the session variables
$_SESSION = array();
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        // Get the session cookie parameters
        $params = session_get_cookie_params();  
        // Set the session cookie with an expiration time in the past to delete it
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
// Destroy the session
session_destroy();
// Redirect to the login page
header("Location: index.html");
exit;
?>