<?php

// Temporary Error Displayer
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// register.php - Backend Processing

// ALWAYS start session at the top to handle feedback messages
session_start();

// Include necessary files
require_once './../../includes/db_connect.php'; // Database connection

// --- BACKEND PROCESSING ---




// --- CONFIGURATION ---
// Define the target directory for uploads (relative to the project root is often useful)
// IMPORTANT: This directory MUST exist and be WRITABLE by the web server (e.g., www-data)
// Example: /var/www/html/webdevfinal/uploads/profile_pics/
define('UPLOAD_DIR_SERVER', '/var/www/html/webdevfinal/assets/uploads/'); // Server path for moving file
define('UPLOAD_DIR_WEB', '/webdevfinal/assets/uploads/'); // Web path to store in DB
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // Max file size in bytes (e.g., 2MB)
$allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];



// Initialize feedback arrays/variables for the session
$_SESSION['errors'] = [];
$_SESSION['form_data'] = []; // To pre-fill form on error
$_SESSION['success_message'] = '';

// Ensure this script is accessed via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Retrieve and Sanitize Input Data
    // Store in session to pre-fill form on error
    $_SESSION['form_data']['first_name'] = trim($_POST['first_name'] ?? '');
    $_SESSION['form_data']['last_name'] = trim($_POST['last_name'] ?? '');
    $_SESSION['form_data']['username'] = trim($_POST['username'] ?? '');
    $_SESSION['form_data']['email'] = trim($_POST['email'] ?? '');
    $_SESSION['form_data']['birthday'] = trim($_POST['birthday'] ?? '');
    $_SESSION['form_data']['Gender'] = trim($_POST['Gender'] ?? ''); // Note the capital 'G' matching HTML
    // IF you want to hash password
    $password = $_POST['password'] ?? ''; // Don't trim passwords initially
    $iAgree = isset($_POST['iAgree']); // Check if the checkbox was sent
    // $confirm_password = $_POST['confirm_password'] ?? '';



    // --- Handle File Upload ---
    $profile_photo_path = null; // Default to null (or a default image web path)
    $upload_error = $_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE; // Get upload error status


    if ($upload_error === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $original_name = basename($_FILES['image']['name']); // Sanitize original name
        $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        // More robust MIME type check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);

        // --- File Validation ---
        if (!in_array($mime_type, $allowed_mime_types)) {
            $_SESSION['errors']['image'] = 'Invalid file type. Only JPG, PNG, GIF allowed.';
        } elseif ($file_size > MAX_FILE_SIZE) {
            $_SESSION['errors']['image'] = 'File is too large. Maximum size is ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB.';
        } else {
            // --- Generate Unique Filename & Move File ---
            // Create a unique filename to prevent overwrites
            $new_filename = uniqid('user_', true) . '.' . $file_extension;
            $target_file_server_path = UPLOAD_DIR_SERVER . $new_filename;
            $target_file_web_path = UPLOAD_DIR_WEB . $new_filename; // Path to store in DB

            // Ensure upload directory exists and is writable (basic check)
            if (!is_dir(UPLOAD_DIR_SERVER) || !is_writable(UPLOAD_DIR_SERVER)) {
                 error_log("Upload directory error: " . UPLOAD_DIR_SERVER . " does not exist or is not writable.");
                 $_SESSION['errors']['database'] = 'Server configuration error preventing file upload.'; // User-friendly message
                 // Stop processing if upload dir is bad
                 header("Location: ./../../registration-form.php");
                 exit();
            }

            if (move_uploaded_file($tmp_name, $target_file_server_path)) {
                // File successfully uploaded and moved
                $profile_photo_path = $target_file_web_path; // Store the web path
            } else {
                error_log("Failed to move uploaded file: " . $original_name . " to " . $target_file_server_path);
                $_SESSION['errors']['image'] = 'Failed to save uploaded file.';
            }
        }

    } elseif ($upload_error !== UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors (size limit exceeded, etc.)
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $_SESSION['errors']['image'] = 'File is too large.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $_SESSION['errors']['image'] = 'File was only partially uploaded.';
                break;
            default:
                $_SESSION['errors']['image'] = 'An error occurred during file upload.';
                break;
        }
    } else {
         // UPLOAD_ERR_NO_FILE means the required field was empty or something else went wrong
         $_SESSION['errors']['image'] = 'Profile picture is required.';
    }
    // --- End File Upload Handling ---



    // 2. Server-Side Validation
    if (empty($_SESSION['form_data']['first_name'])) {
        $_SESSION['errors']['first_name'] = "First Name is required.";
    }
    if (empty($_SESSION['form_data']['last_name'])) {
        $_SESSION['errors']['last_name'] = "Last Name is required.";
    }
     if (empty($_SESSION['form_data']['username'])) {
        $_SESSION['errors']['username'] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $_SESSION['form_data']['username'])) {
         $_SESSION['errors']['username'] = "Username can only contain letters, numbers, and underscores.";
    }
    if (empty($_SESSION['form_data']['email'])) {
        $_SESSION['errors']['email'] = "Email is required.";
    } elseif (!filter_var($_SESSION['form_data']['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = "Invalid email format.";
    }
    if (empty($_SESSION['form_data']['birthday'])) {
        $_SESSION['errors']['birthday'] = "birthday is required.";
    }
    if (empty($_SESSION['form_data']['Gender'])) {
        $_SESSION['errors']['Gender'] = "Gender is required.";
    }
    if (empty($password)) {
        $_SESSION['errors']['password'] = "Password is required.";
    } 
    if (!$iAgree) { $_SESSION['errors']['iAgree'] = "You must agree to the terms and conditions."; }
    // Add password complexity validation if needed
    
    // PASSWORD VALIDATION uncomment if Necessary
    // elseif (strlen($password) < 8) {
    //      $_SESSION['errors']['password'] = "Password must be at least 8 characters long.";
    // }
    // if (empty($confirm_password)) {
    //     $_SESSION['errors']['confirm_password'] = "Please confirm your password.";
    // } elseif ($password !== $confirm_password) {
    //     $_SESSION['errors']['confirm_password'] = "Passwords do not match.";
    // }


    // 3. Check if Username or Email already exists (Using MySQLi)
    // Only proceed if basic validation passed
    if (empty($_SESSION['errors'])) {
        // Use '?' as placeholders for MySQLi prepared statements
        $sql_check = "SELECT username, email FROM users WHERE username = ? OR email = ? LIMIT 1";
        $stmt_check = $conn->prepare($sql_check);

        if ($stmt_check) {
            // Bind parameters: 's' denotes string type
            // Pass variables by reference
            $stmt_check->bind_param("ss", $_SESSION['form_data']['username'], $_SESSION['form_data']['email']);

            // Execute the statement
            if ($stmt_check->execute()) {
                // Get the result set
                $result_check = $stmt_check->get_result();
                $existing_user = $result_check->fetch_assoc(); // Fetch as associative array

                if ($existing_user) {
                    if ($existing_user['username'] === $_SESSION['form_data']['username']) {
                        $_SESSION['errors']['username'] = "Username already taken.";
                    }
                    if ($existing_user['email'] === $_SESSION['form_data']['email']) {
                         $_SESSION['errors']['email'] = "Email address already registered.";
                    }
                }
            } else {
                // Execution failed
                error_log("MySQLi Execute Error (Check User): " . $stmt_check->error);
                $_SESSION['errors']['database'] = "Error checking user details. Please try again.";
            }
            // Close the statement
            $stmt_check->close();
        } else {
            // Preparation failed
            error_log("MySQLi Prepare Error (Check User): " . $conn->error);
            $_SESSION['errors']['database'] = "Database error preparing check. Please try again.";
        }
    }


    // 4. If Validation Passes & User is Unique -> Insert into Database (Using MySQLi)
    if (empty($_SESSION['errors'])) {
        // HASH the password securely! (Same as before)
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // Check if hashing failed (unlikely but good practice)
        if ($password_hash === false) {
            error_log("Password hashing failed for user: " . $_SESSION['form_data']['username']);
            $_SESSION['errors']['password'] = "Could not process password. Please try again.";
            // Redirect back immediately if hashing fails
            header("Location: ./../../registration-form.php");
            exit();
        }

        // Use '?' placeholders
        $sql_insert = "INSERT INTO users (first_name, last_name, username, email, password, birthday, sex, profile_photo_path, created_at)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"; // Added profile_photo_path
        $stmt_insert = $conn->prepare($sql_insert);

        if ($stmt_insert) {
            // Bind parameters: 's' for string type for all five values
            $stmt_insert->bind_param("ssssssss", // 8 strings
                $_SESSION['form_data']['first_name'],
                $_SESSION['form_data']['last_name'],
                $_SESSION['form_data']['username'],
                $_SESSION['form_data']['email'],
                // $_SESSION['form_data']['password'],
                $password_hash, // Use the hashed password
                $_SESSION['form_data']['birthday'],
                $_SESSION['form_data']['Gender'],
                $profile_photo_path
            );

            // Execute the statement
            if ($stmt_insert->execute()) {
                // Check if insert was successful (affected rows > 0)
                if ($stmt_insert->affected_rows > 0) {
                    $_SESSION['success_message'] = "Registration successful! You can now log in.";
                    unset($_SESSION['form_data']); // Clear form data on success
                } else {
                    // Insert seemed to execute but didn't affect rows (unlikely here, but good check)
                     error_log("MySQLi Insert Warning: Execute succeeded but no rows affected.");
                    $_SESSION['errors']['database'] = "Registration completed, but status unclear. Please try logging in or contact support.";
                }
            } else {
                // Execution failed
                 error_log("MySQLi Execute Error (Insert User): " . $stmt_insert->error);
                // Check for duplicate entry error specifically (MySQL error code 1062)
                if ($conn->errno === 1062) {
                     // This is a fallback, the previous check should ideally catch duplicates
                     $_SESSION['errors']['database'] = "Username or Email already exists (database constraint).";
                      // You might want to re-check which field caused it here if needed
                } else {
                    $_SESSION['errors']['database'] = "Registration failed due to a database error. Please try again.";
                }
            }
            // Close the statement
            $stmt_insert->close();
        } else {
             // Preparation failed
            error_log("MySQLi Prepare Error (Insert User): " . $conn->error);
            $_SESSION['errors']['database'] = "Database error preparing registration. Please try again.";
        }
    }

    // 5. Redirect back to the form page (Same as before)
    header("Location: ./../../registration-form.php");
    exit(); // ALWAYS exit after a header redirect

} else {
    // If accessed directly or via GET, just redirect to the form
    header("Location: ./../../registration-form.php");
    exit();
}

// Close the database connection (optional here, as script ends, but good practice elsewhere)
// $conn->close(); // Not strictly needed as PHP closes it on script end, but explicit is fine.
?>
