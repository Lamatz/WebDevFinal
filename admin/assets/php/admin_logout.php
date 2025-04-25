<?php
// logout.php - Ends the user session and redirects

// ALWAYS start the session first
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. Destroy the session cookie (optional, but good practice)
// If you are using session cookies (default behavior), delete the cookie.
// Note: This will destroy the session, not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, // Set expiry in the past
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session data on the server
session_destroy();

// 4. Redirect to the login page
// Added a parameter to optionally show a success message on login page
header('Location: ./../../admin-login-form.php?logout=success'); // Adjust path if needed
exit(); // Important: Stop script execution after redirect

?>