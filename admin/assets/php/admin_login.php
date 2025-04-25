<?php
// login_process.php - Backend Login Logic
// echo "<pre>POST Data:\n";
// var_dump($_POST);
// echo "</pre>";

// ALWAYS start session at the very top
session_start();

// Include necessary files
require_once './../../../includes/db_connect.php'; // Provides $conn (MySQLi connection)

// TRY IF THIS WORKS
// require_once '/webdevfinal/includes/db_connect.php'; // Provides $conn (MySQLi connection)

// Initialize feedback variable for the session
$_SESSION['error'] = ''; // Clear previous errors



// Ensure this script is accessed via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Retrieve and Basic Sanitization
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // Don't trim password

    // Basic Input Validation
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and Password are required.";
        header("Location: /webdevfinal/admin/admin-login-form.php");
        exit();
    }

    // 2. Query Database (Using MySQLi Prepared Statement)
    // Select user based on username OR email, and ensure they are active
    // Fetch necessary fields for session and verification
    $sql = "SELECT admin_id, username, email, password, first_name, last_name
            FROM admins
            WHERE username = ?";


    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the username/email input to both placeholders ('s' for string)
        $stmt->bind_param("s", $username);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc(); // Fetch user data as an associative array

            // 3. Verify User and Password
            if ($admin) {
                // User found and is active, now verify the password
                if (password_verify($password, $admin['password'])) {
                    // Password is correct!

                    // 4. Regenerate session ID for security
                    session_regenerate_id(true);

                    // 5. Store user information in the session
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['first_name'] = $admin['first_name'];
                    $_SESSION['last_name'] = $admin['last_name'];
                    $_SESSION['email'] = $admin['email']; // Store email if needed elsewhere


                    // Clear any potential error message
                    unset($_SESSION['error']);

                    // 6. Redirect to a protected area (e.g., dashboard)
                    header("Location: ./../../admin-dashboard.php");
                    exit();

                } else {
                    // Password does not match
                    $_SESSION['error'] = "Invalid password."; // Generic error
                    header("Location: ./../../admin-login-form.php");
                    exit();
                }
            } else {
                // User not found or not active
                $_SESSION['error'] = "Invalid username or password."; // Generic error for security
                header("Location: ./../../admin-login-form.php");
                exit();
            }

        } else {
            // Execution failed
            error_log("MySQLi Execute Error (Login): " . $stmt->error);
            $_SESSION['error'] = "An error occurred during login. Please try again.";
            header("Location: ./../../admin-login-form.php");
            exit();
        }
        // Close the statement
        $stmt->close();

    } else {
        // Preparation failed
        error_log("MySQLi Prepare Error (Login): " . $conn->error);
        $_SESSION['error'] = "A database error occurred. Please try again later.";
        header("Location: ./../../admin-login-form.php");
        exit();
    }

    // Close connection (optional, PHP closes automatically)
    // $conn->close();

} else {
    // If not accessed via POST, redirect to login form
    header("Location: ./../../admin-login-form.php");
    exit();
}
?>