<?php
// admin_process_change_password.php

// ALWAYS start session at the very top
session_start();

// --- Database Connection ---
// Adjust the path as necessary to point to your db_connect.php
require_once './../../../includes/db_connect.php'; // Assumes db_connect is two levels up

// --- Security: Check if Admin is Logged In ---
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Use distinct session keys for feedback to avoid conflicts with other forms
    $_SESSION['cp_error'] = "Unauthorized access. Please log in as admin.";
    // Redirect back to the change password form page
    header('Location: ./../../admin-login-form.php');
    exit();
}

// MAYBE THIS IS NOT NEEDED ----------------
// --- Check if Request Method is POST ---
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Not a POST request, redirect back
    header('Location: ./../../admin-login-form.php');
    exit();
}
// --------------------



// --- Initialize Feedback Variables ---
$_SESSION['cp_error'] = null; // Clear previous errors
$_SESSION['cp_success'] = null;
$_SESSION['cp_error_details'] = []; // Array for specific field errors

// --- Retrieve Input Data ---
// DO NOT trim passwords initially, spaces might be intentional (though generally discouraged)
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$admin_id = $_SESSION['admin_id']; // Get ID of the currently logged-in admin

// --- Server-Side Validation ---
$errors = []; // Local array to hold errors for this request

if (empty($current_password)) {
    $errors['current_password'] = "Current Password is required.";
}
if (empty($new_password)) {
    $errors['new_password'] = "New Password is required.";
} else {

    // UNCOMMENT THIS IF REQUIRED
    // // Optional: Add password strength checks (e.g., minimum length)
    // if (strlen($new_password) < 8) { // Example: Minimum 8 characters
    //     $errors['new_password_strength'] = "New Password must be at least 8 characters long.";
    // }
    // // Add more checks here (e.g., require numbers, symbols, uppercase) if desired
}
if (empty($confirm_password)) {
    $errors['confirm_password'] = "Please re-enter the new password.";
} elseif ($new_password !== $confirm_password) {
    $errors['password_mismatch'] = "The new passwords do not match.";
}

// If there are basic validation errors, stop processing
if (!empty($errors)) {
    $_SESSION['cp_error'] = "Password change failed. Please correct the errors.";
    $_SESSION['cp_error_details'] = array_values($errors); // Store specific error messages
    mysqli_close($conn); // Close connection before redirecting
    header('Location: ./../..//admin-changePass.php'); // Redirect back
    exit();
}

// --- Validation Passed So Far - Proceed with Database Checks ---



// 1. Fetch the current password hash from the database
$sql_fetch = "SELECT password FROM admins WHERE admin_id = ?"; // Assumes column is 'password_hash'
$current_hash = null;

if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $admin_id);
    if (mysqli_stmt_execute($stmt_fetch)) {
        $result_fetch = mysqli_stmt_get_result($stmt_fetch);
        if ($admin_data = mysqli_fetch_assoc($result_fetch)) {
            $current_hash = $admin_data['password'];
        } else {
            // This shouldn't happen if the admin_id in session is valid
            $errors['fetch_user'] = "Could not retrieve current admin data.";
            error_log("Admin Change PW Error: Failed to fetch admin data for ID: $admin_id");
        }
    } else {
        $errors['db_fetch_execute'] = "Database error retrieving current password.";
        error_log("Admin Change PW DB Error (Execute Fetch): " . mysqli_stmt_error($stmt_fetch));
    }
    mysqli_stmt_close($stmt_fetch);
} else {
    $errors['db_fetch_prepare'] = "Database error preparing to retrieve password.";
    error_log("Admin Change PW DB Error (Prepare Fetch): " . mysqli_error($conn));
}

// Stop if we couldn't get the current hash
if (!empty($errors) || $current_hash === null) {
    $_SESSION['cp_error'] = "An error occurred. Could not verify current password.";
    if (!empty($errors)) {
        $_SESSION['cp_error_details'] = array_values($errors);
    }
    mysqli_close($conn);
    header('Location: ./../..//admin-changePass.php'); // Redirect back
    exit();
}

// 2. Verify the submitted current password against the hash
if (!password_verify($current_password, $current_hash)) {
    $errors['current_password_incorrect'] = "Incorrect current password.";
    $_SESSION['cp_error'] = "Password change failed.";
    $_SESSION['cp_error_details'] = array_values($errors);
    mysqli_close($conn);
    header('Location: ./../..//admin-changePass.php'); // Redirect back
    exit();
}

// 3. Check if new password is the same as the current one (Optional but good practice)
if (password_verify($new_password, $current_hash)) {
    $errors['new_is_old'] = "The new password cannot be the same as the current password.";
    $_SESSION['cp_error'] = "Password change failed.";
    $_SESSION['cp_error_details'] = array_values($errors);
    mysqli_close($conn);
    header('Location: ./../..//admin-changePass.php'); // Redirect back
    exit();
}


// --- All Checks Passed - Hash New Password and Update Database ---

// 4. Hash the new password securely
$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
if ($new_password_hash === false) {
    // Hashing failed (very unlikely)
    $errors['hash_failed'] = "Failed to process the new password.";
    error_log("Admin Change PW Error: password_hash() failed for admin ID: $admin_id");
    $_SESSION['cp_error'] = "A server error occurred while processing the new password.";
    $_SESSION['cp_error_details'] = array_values($errors);
    mysqli_close($conn);
    header('Location: ./../..//admin-changePass.php'); // Redirect back
    exit();
}


// 5. Prepare and execute the UPDATE statement
$sql_update = "UPDATE admins SET password = ? WHERE admin_id = ?";

if ($stmt_update = mysqli_prepare($conn, $sql_update)) {
    mysqli_stmt_bind_param($stmt_update, "si", $new_password_hash, $admin_id);

    if (mysqli_stmt_execute($stmt_update)) {
        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
            // Success!
            $_SESSION['cp_success'] = "Password changed successfully!";
        } else {
            // Query executed but no rows affected (e.g., somehow admin_id was invalid at this point)
            $_SESSION['cp_error'] = "Password update failed. Please try again.";
            error_log("Admin Change PW Warning: Update executed but no rows affected for admin ID: $admin_id");
        }
    } else {
        // Update execution error
        $_SESSION['cp_error'] = "Database error updating password.";
        error_log("Admin Change PW DB Error (Execute Update): " . mysqli_stmt_error($stmt_update));
    }
    mysqli_stmt_close($stmt_update);

} else {
    // Update preparation error
    $_SESSION['cp_error'] = "Database error preparing password update.";
    error_log("Admin Change PW DB Error (Prepare Update): " . mysqli_error($conn));
}

// --- Close Connection ---
mysqli_close($conn);

// --- Redirect Back to the Form Page ---
header('Location: ./../..//admin-changePass.php'); // Redirect back
exit();

?>