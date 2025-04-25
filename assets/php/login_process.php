<?php
// login_process.php - Backend Login Logic
// echo "<pre>POST Data:\n";
// var_dump($_POST);
// echo "</pre>";

// ALWAYS start session at the very top
session_start();

// Include necessary files
require_once './../../includes/db_connect.php'; // Provides $conn (MySQLi connection)

// Initialize feedback variable for the session
$_SESSION['error'] = ''; // Clear previous errors



// Ensure this script is accessed via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Retrieve and Basic Sanitization
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? ''; // Don't trim password

    // Basic Input Validation
    if (empty($username_or_email) || empty($password)) {
        $_SESSION['error'] = "Username/Email and Password are required.";
        header("Location: ./../../login-form.php");
        exit();
    }

    // 2. Query Database (Using MySQLi Prepared Statement)
    // Select user based on username OR email, and ensure they are active
    // Fetch necessary fields for session and verification
    $sql = "SELECT user_id, username, email, password, first_name, last_name, profile_photo_path, is_active
            FROM users
            WHERE (username = ? OR email = ?) AND is_active = 1
            LIMIT 1"; // Limit 1 is important

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the username/email input to both placeholders ('s' for string)
        $stmt->bind_param("ss", $username_or_email, $username_or_email);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            $user = $result->fetch_assoc(); // Fetch user data as an associative array

            // 3. Verify User and Password
            if ($user) {
                // User found and is active, now verify the password
                if (password_verify($password, $user['password'])) {
                    // Password is correct!

                    // 4. Regenerate session ID for security
                    session_regenerate_id(true);

                    // 5. Store user information in the session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['email'] = $user['email']; // Store email if needed elsewhere
                    $_SESSION['profile_photo'] = $user['profile_photo_path']; // Store photo path if needed
                    // Add any other relevant user details you want to keep handy

                    // Clear any potential error message
                    unset($_SESSION['error']);

                    // 6. Redirect to a protected area (e.g., dashboard)
                    header("Location: ./../../mainpage.php");
                    exit();

                } else {
                    // Password does not match
                    $_SESSION['error'] = "Invalid password."; // Generic error
                    header("Location: ./../../login-form.php");
                    exit();
                }
            } else {
                // User not found or not active
                $_SESSION['error'] = "Invalid username/email or password or not active"; // Generic error for security
                header("Location: ./../../login-form.php");
                exit();
            }

        } else {
            // Execution failed
            error_log("MySQLi Execute Error (Login): " . $stmt->error);
            $_SESSION['error'] = "An error occurred during login. Please try again.";
            header("Location: ./../../login-form.php");
            exit();
        }
        // Close the statement
        $stmt->close();

    } else {
        // Preparation failed
        error_log("MySQLi Prepare Error (Login): " . $conn->error);
        $_SESSION['error'] = "A database error occurred. Please try again later.";
        header("Location: ./../../login-form.php");
        exit();
    }

    // Close connection (optional, PHP closes automatically)
    // $conn->close();

} else {
    // If not accessed via POST, redirect to login form
    header("Location: ./../../login-form.php");
    exit();
}
?>