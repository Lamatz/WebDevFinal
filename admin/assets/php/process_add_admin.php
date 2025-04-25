<?php
// assets/php/process_add_admin.php

// ALWAYS start session at the very top
session_start();

// --- Database Connection ---
require_once './../../../includes/db_connect.php'; // Adjust path as needed

// --- Security: Check if Admin is Logged In ---
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Set an error message for the form page
    $_SESSION['add_admin_error'] = "Unauthorized action.";
    // Redirect to the add admin form (or login)
    header('Location: ./../../admin-login-form.php'); // Adjust path
    exit();
}

// --- Check if Request Method is POST ---
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ./../../admin-login-form.php'); // Adjust path
    exit();
}

// --- Initialize Feedback Variables ---
$_SESSION['add_admin_error'] = null;
$_SESSION['add_admin_success'] = null;
$_SESSION['add_admin_error_details'] = [];
$_SESSION['add_admin_form_data'] = []; // To repopulate form on error

// --- Retrieve and Sanitize Input Data ---
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? ''; // Don't trim password initially
$confirm_password = $_POST['confirm_password'] ?? '';

// Store submitted data in session in case of error
$_SESSION['add_admin_form_data'] = $_POST;


// --- Server-Side Validation ---
$errors = [];

if (empty($first_name)) { $errors['first_name'] = "First Name is required."; }
if (empty($last_name)) { $errors['last_name'] = "Last Name is required."; }
if (empty($username)) { $errors['username'] = "Username is required."; } 
if (empty($email)) {
    $errors['email'] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email_format'] = "Invalid email format.";
}
if (empty($password)) { $errors['password'] = "Password is required."; }
// PROBABLY NOT PART OF SCOPE
// elseif (strlen($password) < 8) {
    // $errors['password_length'] = "Password must be at least 8 characters long.";
// }


if (empty($confirm_password)) {
    $errors['confirm_password'] = "Please confirm the password.";
} elseif ($password !== $confirm_password) {
    $errors['password_mismatch'] = "Passwords do not match.";
}

// --- Database Checks (Only if basic validation passes) ---
if (empty($errors)) {
    // Check if username already exists in 'admins' table
    $sql_check_user = "SELECT admin_id FROM admins WHERE username = ? LIMIT 1";
    if ($stmt_check_user = mysqli_prepare($conn, $sql_check_user)) {
        mysqli_stmt_bind_param($stmt_check_user, "s", $username);
        mysqli_stmt_execute($stmt_check_user);
        mysqli_stmt_store_result($stmt_check_user); // Important for num_rows
        if (mysqli_stmt_num_rows($stmt_check_user) > 0) {
            $errors['username_exists'] = "Username is already taken.";
        }
        mysqli_stmt_close($stmt_check_user);
    } else {
        $errors['db_check_user'] = "Database error checking username.";
        error_log("Admin Add DB Error (Prepare Check Username): " . mysqli_error($conn));
    }

    // Check if email already exists in 'admins' table
    $sql_check_email = "SELECT admin_id FROM admins WHERE email = ? LIMIT 1";
    if ($stmt_check_email = mysqli_prepare($conn, $sql_check_email)) {
        mysqli_stmt_bind_param($stmt_check_email, "s", $email);
        mysqli_stmt_execute($stmt_check_email);
         mysqli_stmt_store_result($stmt_check_email); // Important for num_rows
        if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
            $errors['email_exists'] = "Email address is already registered.";
        }
        mysqli_stmt_close($stmt_check_email);
    } else {
        $errors['db_check_email'] = "Database error checking email.";
         error_log("Admin Add DB Error (Prepare Check Email): " . mysqli_error($conn));
    }
}


// --- Process Insertion (Only if NO errors occurred) ---
if (empty($errors)) {
    // Hash the password securely
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if ($password_hash === false) {
        // Hashing failed (rare)
        $_SESSION['add_admin_error'] = "Failed to process password securely.";
        error_log("Admin Add Error: password_hash() failed.");
        mysqli_close($conn);
        header('Location: /webdevfinal/admin/admin-add-admin.php'); // Redirect back
        exit();
    }

    // Prepare INSERT statement for 'admins' table
    // Assumes columns: first_name, last_name, username, email, password_hash, created_at
    $sql_insert = "INSERT INTO admins (first_name, last_name, username, email, password, created_at)
                   VALUES (?, ?, ?, ?, ?, NOW())";

    if ($stmt_insert = mysqli_prepare($conn, $sql_insert)) {
        // Bind parameters (s = string)
        mysqli_stmt_bind_param($stmt_insert, "sssss",
            $first_name,
            $last_name,
            $username,
            $email,
            $password_hash // Use the hashed password
        );

        // Execute the insert statement
        if (mysqli_stmt_execute($stmt_insert)) {
            if (mysqli_stmt_affected_rows($stmt_insert) > 0) {
                // Success! Clear form data from session and set success message
                unset($_SESSION['add_admin_form_data']);
                $_SESSION['add_admin_success'] = "Admin account for '{$username}' created successfully!";
            } else {
                // Insert executed but no rows affected (shouldn't happen here unless concurrent issue)
                 $_SESSION['add_admin_error'] = "Admin creation failed unexpectedly.";
                 error_log("Admin Add Warning: Insert query executed but no rows affected.");
            }
        } else {
            // Execution failed
             $_SESSION['add_admin_error'] = "Database error creating admin account.";
             error_log("Admin Add DB Error (Execute Insert): " . mysqli_stmt_error($stmt_insert));
             // Check for duplicate entry specifically (MySQL error 1062)
              if(mysqli_errno($conn) == 1062){
                 $_SESSION['add_admin_error'] = "Database error: Username or Email already exists (Constraint).";
              }
        }
        mysqli_stmt_close($stmt_insert);

    } else {
        // Preparation failed
         $_SESSION['add_admin_error'] = "Database error preparing to create admin.";
         error_log("Admin Add DB Error (Prepare Insert): " . mysqli_error($conn));
    }

} else {
    // Validation errors occurred
    $_SESSION['add_admin_error'] = "Please correct the errors below.";
    $_SESSION['add_admin_error_details'] = array_values($errors); // Store specific errors
    // Keep $_SESSION['add_admin_form_data'] so the form can be repopulated
}


// --- Close connection and Redirect ---
mysqli_close($conn);
header('Location: ./../../admin-add-admin.php'); // Redirect back to the form page
exit();

?>