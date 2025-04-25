<?php
// deactivate_user.php

// ALWAYS start session at the very top
session_start();

// --- Database Connection ---
// Adjust the path as necessary to point to your connection script
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

$user_id_to_deactivate = (int)$_GET['id'];


// --- Prepare Database Update ---
// Set the is_active column to 0 (inactive) for the specified user_id
$sql = "UPDATE users SET is_active = 0 WHERE user_id = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {

    // Bind the user_id parameter
    mysqli_stmt_bind_param($stmt, "i", $user_id_to_deactivate);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Check if any row was actually updated
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['user_list_success'] = "User account (ID: {$user_id_to_deactivate}) deactivated successfully.";
        } else {
            // This might happen if the user ID doesn't exist or was already inactive
            $_SESSION['user_list_info'] = "User account (ID: {$user_id_to_deactivate}) was already inactive or not found.";
        }
    } else {
        // Execution error
        error_log("Error executing user deactivation: " . mysqli_stmt_error($stmt));
        $_SESSION['user_list_error'] = "Database error during deactivation. Please try again.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);

} else {
    // Preparation error
    error_log("Error preparing user deactivation query: " . mysqli_error($conn));
    $_SESSION['user_list_error'] = "Database error preparing for deactivation. Please try again.";
}

// --- Close Connection ---
mysqli_close($conn);

// --- Redirect Back ---
// Redirect back to the user list page regardless of success/error
// The list page should display the session messages.
header('Location: ./../../admin-dashboard.php'); // Adjust path if needed
exit();

?>