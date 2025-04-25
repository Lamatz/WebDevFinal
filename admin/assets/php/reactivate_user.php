<?php
// reactivate_user.php

// ALWAYS start session at the very top
session_start();

// --- Database Connection ---
// Adjust the path as necessary to point to your db_connect.php
require_once './../../../includes/db_connect.php';

// --- Security: Check if Admin is Logged In ---
// Redirect to login if not authenticated as an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    $_SESSION['error'] = "Unauthorized access. Please log in as admin.";
    // Adjust the path to your admin login form
    header('Location: ./../../admin-login-form.php');
    exit();
}

// --- Input Validation ---
// Check if 'id' is provided via GET and is a positive integer
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
    $_SESSION['user_list_error'] = "Invalid user ID provided for deactivation.";
    // Redirect back to the user list page (adjust path if needed)
    header('Location: ./../../admin-dashboard.php'); // Assuming your list page is users.php
    exit();
}
$user_id_to_reactivate = (int)$_GET['id'];

// --- Prepare Database Update ---
// Set the is_active column to 1 (active) for the specified user_id in the 'users' table
$sql = "UPDATE users SET is_active = 1 WHERE user_id = ?"; // The only change is "is_active = 1"

if ($stmt = mysqli_prepare($conn, $sql)) {

    // Bind the user_id parameter ("i" for integer)
    mysqli_stmt_bind_param($stmt, "i", $user_id_to_reactivate);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Check if any row was actually updated
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Update success message for reactivation
            $_SESSION['user_list_success'] = "User account (ID: {$user_id_to_reactivate}) reactivated successfully.";
        } else {
            // User ID might not exist or was already active
            // Update info message for reactivation
            $_SESSION['user_list_info'] = "User account (ID: {$user_id_to_reactivate}) was already active or not found.";
        }
    } else {
        // Execution error
        // Update error log message
        error_log("ADMIN Error executing user reactivation (UserID: $user_id_to_reactivate): " . mysqli_stmt_error($stmt));
        $_SESSION['user_list_error'] = "Database error during reactivation. Please try again.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);

} else {
    // Preparation error
    // Update error log message
    error_log("ADMIN Error preparing user reactivation query: " . mysqli_error($conn));
    $_SESSION['user_list_error'] = "Database error preparing for reactivation. Please try again.";
}

// --- Close Connection ---
mysqli_close($conn);

// --- Redirect Back ---
// Redirect back to the user list page. Ensure the filename is correct.
header('Location: ./../../admin-dashboard.php'); // Adjust path if needed
exit();

?>