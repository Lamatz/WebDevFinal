<?php
// edit_profile_process.php - Handles profile update submissions

// ALWAYS start session first
session_start();

// Include database connection
require_once './../../includes/db_connect.php'; // Adjust path as needed




// --- Authentication Check ---
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Not logged in, redirect to login
    $_SESSION['error'] = "You must be logged in to update your profile.";
    header('Location: /webdevfinal/login-form.php'); // Adjust path
    exit();
}



// --- Initialize ---
$user_id = $_SESSION['user_id'];
$_SESSION['errors'] = []; // Clear previous errors specifically for this request
$_SESSION['success'] = null; // Clear previous success message
// Don't unset form_data here, we need it if validation fails




// --- Check if the form was submitted via POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Not a POST request, redirect or show error
    $_SESSION['error'] = "Invalid request method.";
    // Redirect back to the edit page or profile page
    header('Location: /webdevfinal/user-profile.php'); // Adjust path
    exit();
}


// // --- DEBUG: Dump received data ---
// echo '<pre>POST Data: '; var_dump($_POST); echo '</pre>';
// echo '<pre>FILES Data: '; var_dump($_FILES); echo '</pre>';
// exit; // Use exit here during debugging to stop further execution
// // --- END DEBUG ---

// --- Define constants for file upload ---
// IMPORTANT: Adjust these paths based on your server structure
define('UPLOAD_DIR_PATH', $_SERVER['DOCUMENT_ROOT'] . '/webdevfinal/assets/uploads/'); // Server file system path
define('UPLOAD_DIR_URL', '/webdevfinal/assets/uploads/');        // URL path for DB/HTML src
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2 MB
$allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$default_profile_pic_url = '/webdevfinal/assets/uploads/default_pfp.jpg'; // Ensure this matches default



// // --- Retrieve and Sanitize Input ---
// // Store submitted data in session temporarily in case of error for repopulation
// $_SESSION['form_data'] = $_POST;


// --- Retrieve and Sanitize Input ---
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
// $username = trim($_POST['username'] ?? '');
$email      = trim($_POST['email'] ?? '');
$birthday   = trim($_POST['birthday'] ?? ''); // Keep YYYY-MM-DD format
$sex        = trim($_POST['sex'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// --- Server-Side Validation ---
$errors = []; // Array to hold validation errors

// Basic field checks
if (empty($first_name)) { $errors['first_name'] = "First name is required."; }
if (empty($last_name))  { $errors['last_name'] = "Last name is required."; }
if (empty($email))      { $errors['email'] = "Email is required."; }
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = "Invalid email format."; }
if (empty($birthday)) { $errors['birthday'] = "Birthday is required"; }
// if (empty($username)) { $errors['username'] = "Username is required"; }
if (empty($sex)) { $errors['sex'] = "Sex/Gender is required"; }


// Password change validation
$update_password = false; // Flag to check if password needs hashing/updating
if (!empty($new_password)) {
    $update_password = true;
    if ($new_password !== $confirm_password) {
        $errors['password'] = "New passwords do not match.";
    }
    // Add password complexity rules if desired (e.g., length)
    // elseif (strlen($new_password) < 8) {
    //     $errors['password'] = "Password must be at least 8 characters long.";
    // }
    // You might require a current password field here for security
    // Fetch current hash, verify with password_verify(), add error if no match
}

// Check Email Uniqueness (only if email is valid and potentially changed)
if (empty($errors['email'])) {
    $sql_email_check = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
    if ($stmt_email = mysqli_prepare($conn, $sql_email_check)) {
        mysqli_stmt_bind_param($stmt_email, "si", $email, $user_id);
        mysqli_stmt_execute($stmt_email);
        mysqli_stmt_store_result($stmt_email); // Important for num_rows

        if (mysqli_stmt_num_rows($stmt_email) > 0) {
            $errors['email'] = "This email address is already in use by another account.";
        }
        mysqli_stmt_close($stmt_email);
    } else {
        $errors['database'] = "Error checking email uniqueness."; // Generic error
        error_log("Failed to prepare email check statement: " . mysqli_error($conn));
    }
}

// // Check Username Uniqueness (only if username is valid and potentially changed)
// if (empty($errors['username'])) {
//     $sql_username_check = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
//     if ($stmt_username = mysqli_prepare($conn, $sql_username_check)) {
//         mysqli_stmt_bind_param($stmt_username, "si", $username, $user_id);
//         mysqli_stmt_execute($stmt_username);
//         mysqli_stmt_store_result($stmt_username); // Important for num_rows

//         if (mysqli_stmt_num_rows($stmt_username) > 0) {
//             $errors['username'] = "This username is already in use by another account.";
//         }
//         mysqli_stmt_close($stmt_username);
//     } else {
//         $errors['database'] = "Error checking username uniqueness."; // Generic error
//         error_log("Failed to prepare username check statement: " . mysqli_error($conn));
//     }
// }

// --- Handle Profile Photo Upload ---
$new_photo_path = null; // Variable to hold the new URL path for DB update
// **FIXED:** Check the correct key 'profile_photo' from the HTML form name attribute
$uploaded_file_info = $_FILES['profile_photo'] ?? null;

if ($uploaded_file_info && $uploaded_file_info['error'] === UPLOAD_ERR_OK) {
    $file_tmp_path = $uploaded_file_info['tmp_name'];
    $file_size = $uploaded_file_info['size'];
    $file_name = $uploaded_file_info['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Validate MIME type more reliably
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_mime_type = finfo_file($finfo, $file_tmp_path);
    finfo_close($finfo);

    





    if ($file_size > MAX_FILE_SIZE) {
        $errors['profile_photo'] = "File is too large (Max: " . (MAX_FILE_SIZE / 1024 / 1024) . " MB).";
    } elseif (!in_array($file_mime_type, $allowed_mime_types) || !in_array($file_ext, $allowed_extensions)) {
        $errors['profile_photo'] = "Invalid file type (Allowed: JPG, PNG, GIF).";
    } else {
        // Check if upload directory exists and is writable
        if (!is_dir(UPLOAD_DIR_PATH)) {
             error_log("Upload directory does not exist: " . UPLOAD_DIR_PATH);
             $errors['database'] = "Server configuration error [Code: DIR_NEX]."; // More specific internal code
        } elseif (!is_writable(UPLOAD_DIR_PATH)) {
             error_log("Upload directory is not writable: " . UPLOAD_DIR_PATH);
             $errors['database'] = "Server configuration error [Code: DIR_PERM].";
        } else {
            // File is valid, generate unique name and move
            $unique_filename = "user_" . $user_id . "_" . bin2hex(random_bytes(8)) . "." . $file_ext; // More unique name
            $destination_path_fs = UPLOAD_DIR_PATH . $unique_filename; // File system path
            $destination_path_url = UPLOAD_DIR_URL . $unique_filename; // URL path for DB

            if (move_uploaded_file($file_tmp_path, $destination_path_fs)) {
                // File moved successfully, store the URL path for DB update
                $new_photo_path = $destination_path_url;

                // Optional: Delete old photo - fetch from DB *just before* update or use session
                $old_photo_url = $_SESSION['profile_photo'] ?? null; // Get current path from session

                if ($old_photo_url && $old_photo_url !== $default_profile_pic_url) {
                    // Construct the file system path from the URL using DOCUMENT_ROOT
                    $old_photo_fs_path = str_replace(UPLOAD_DIR_URL, UPLOAD_DIR_PATH, $old_photo_url);
                    // More robust way using DOCUMENT_ROOT if needed:
                    // $relativePath = str_replace('/webdevfinal/', '', $old_photo_url); // Remove base URL part
                    // $old_photo_fs_path = $_SERVER['DOCUMENT_ROOT'] . '/webdevfinal/' . $relativePath;

                    if (file_exists($old_photo_fs_path)) {
                        if (!unlink($old_photo_fs_path)) {
                            error_log("Failed to delete old profile photo for user $user_id: $old_photo_fs_path");
                        }
                    } else {
                         error_log("Old profile photo not found for deletion for user $user_id: $old_photo_fs_path");
                    }
                }
            } else {
                $errors['profile_photo'] = "Failed to save uploaded file.";
                error_log("Failed to move uploaded file for user $user_id: " . $file_tmp_path . " to " . $destination_path_fs . " - Check permissions and path.");
            }
        }
    }
} elseif ($uploaded_file_info && $uploaded_file_info['error'] !== UPLOAD_ERR_NO_FILE) {

    if($uploaded_file_info['error'] == 1){
        $errors['profile_photo'] = "File is too large (Max: " . (MAX_FILE_SIZE / 1024 / 1024) . " MB).";

    } else {
        $errors['profile_photo'] = "Error uploading file. Code: " . $uploaded_file_info['error'];
        error_log("File upload error for user $user_id: " . $uploaded_file_info['error']);
    }

    // $errors['profile_photo'] = "Error uploading file. Code: " . $uploaded_file_info['error'];
    // error_log("File upload error for user $user_id: " . $uploaded_file_info['error']);
}


// --- Check if Validation Failed ---
if (!empty($errors)) {
    // Store errors and submitted data (optional) in session
    $_SESSION['errors'] = $errors;
    // Store non-sensitive submitted data to repopulate form
    $_SESSION['form_data'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'birthday' => $birthday,
        'sex' => $sex,
        // Do NOT store passwords in session
    ];

    // Redirect back to the edit form
    header('Location: /webdevfinal/user-profile.php'); // Or your edit page path
    exit();
}

// --- Validation Passed - Proceed with Database Update ---

// Build the SQL query dynamically based on what needs updating
$sql_update = "UPDATE users SET first_name = ?, last_name = ?, email = ?, birthday = ?, sex = ?, updated_at = NOW()";
$params = [$first_name, $last_name, $email, $birthday, $sex];
$types = "sssss"; // types for the base parameters

// Add password update if needed
$hashed_password = null;
if ($update_password) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql_update .= ", password = ?";
    $params[] = $hashed_password;
    $types .= "s";
}

// Add profile photo update if needed
if ($new_photo_path !== null) {
    $sql_update .= ", profile_photo_path = ?";
    $params[] = $new_photo_path;
    $types .= "s";
}

// Add the WHERE clause
$sql_update .= " WHERE user_id = ?";
$params[] = $user_id;
$types .= "i";

// Prepare and execute the statement
if ($stmt = mysqli_prepare($conn, $sql_update)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params); // Use argument unpacking (...)

    if (mysqli_stmt_execute($stmt)) {
        // --- Success ---
        // Update session variables with new data
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name; // If you use last name in session
        $_SESSION['email'] = $email; // If you use email in session
        if ($new_photo_path !== null) {
            $_SESSION['profile_photo'] = $new_photo_path; // Update session photo path
        }
        // You might want to update other session vars if you store birthday/sex there

        $_SESSION['success'] = "Profile updated successfully!";
        

        // Redirect to profile view page
        header('Location: /webdevfinal/user-profile.php'); // Adjust path
        exit();

    } else {
        // --- Database Execution Error ---
        $_SESSION['error'] = "Database update failed. Please try again later.";
        error_log("Profile update failed for user $user_id: " . mysqli_stmt_error($stmt));
        // Redirect back to edit form
        header('Location: /webdevfinal/user-profile.php'); // Adjust path
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    // --- SQL Prepare Error ---
    $_SESSION['error'] = "An error occurred preparing the update. Please try again later.";
    error_log("Failed to prepare profile update statement: " . mysqli_error($conn));
    // Redirect back to edit form
    header('Location: /webdevfinal/user-profile.php'); // Adjust path
    exit();
}

// Close connection (usually happens automatically, but good practice)
mysqli_close($conn);

?>