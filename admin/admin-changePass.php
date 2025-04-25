<?php
// profile.php - Example Protected Client Page (NO INCLUDES)

// ALWAYS start session at the very top of any page that uses sessions
session_start();

// --- Authentication Check ---
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    header('Location: /webdevfinal/admin/admin-login-form.php');
    exit();
}

// --- If execution reaches here, the user IS logged in ---

// Determine if the user is logged in (needed for conditional display below)
$is_logged_in = true; // We know they are because they passed the check above

require_once './../includes/db_connect.php'; // Database connection


$admin_id = $_SESSION['admin_id']; // Get the logged-in user's ID from the session


$username = '';
$email = '';
$first_name = '';
$last_name = '';


$sql = "SELECT username, email ,first_name, last_name
        FROM admins
        WHERE admin_id = ?"; // Use a placeholder (?) for the user ID



if ($stmt = mysqli_prepare($conn, $sql)) {

    // Bind the user_id parameter to the placeholder
    // "i" indicates the parameter is an integer
    mysqli_stmt_bind_param($stmt, "i", $admin_id);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the result set from the executed statement
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the user data as an associative array
        if ($userData = mysqli_fetch_assoc($result)) {
            // Assign database values to PHP variables
            // Use null coalescing operator (??) to provide defaults if a DB value is NULL
            $username = $userData['username'] ?? 'N/A';
            $email = $userData['email'] ?? 'N/A';
            $first_name = $userData['first_name'] ?? '';
            $last_name = $userData['last_name'] ?? '';
        } else {
            // $_SESSION['error'] = "Could not find your profile data.";
            error_log("Logged in admin (ID: $logged_in_admin_id) not found in database.");
        }
    } else {
        error_log("Error executing logged-in admin query: " . mysqli_stmt_error($stmt));
        echo "An error occurred while retrieving your profile.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);

} else {
    // Error preparing the statement
    error_log("Error preparing logged-in admin query: " . mysqli_error($conn));
    echo "An error occurred while preparing to retrieve your profile.";
}




mysqli_close($conn);
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link rel="stylesheet" href="css/dashboard-style.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Change Password</title>


</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>


        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="navbarDropdown"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <i class="fas fa-user fa-fw"></i>
                    <p class="m-0 ps-2"> <?php echo htmlspecialchars($username); ?></p>
                </a>
                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">

                    <li><a class="dropdown-item" href="assets/php/admin_logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>


    <div id="layoutSidenav">

        <!-- SIDEBAR CONTENT START -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="admin-dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            User Accounts
                        </a>
                        <a class="nav-link" href="admin-add-admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Add User/Admin
                        </a>
                        <a class="nav-link" href="admin-changePass.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Change Password
                        </a>
                    </div>
                </div>

                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo htmlspecialchars($first_name); ?> <?php echo htmlspecialchars($last_name); ?>
                </div>
            </nav>
        </div>
        <!-- SIDEBAR CONTENT END -->


        <div id="layoutSidenav_content">


            <!-- MAIN CONTENT -->
            <main class="container-fluid px-4">


                <h1 class="mt-4">CHANGE PASSWORD</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>

                <!-- Feedback Messages Area -->
                <?php if (isset($_SESSION['cp_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['cp_success']);
                        unset($_SESSION['cp_success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['cp_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['cp_error']);
                        unset($_SESSION['cp_error']); ?>
                        <?php // Optionally display detailed errors if available
                            if (isset($_SESSION['cp_error_details']) && is_array($_SESSION['cp_error_details'])) {
                                echo '<ul>';
                                foreach ($_SESSION['cp_error_details'] as $detail) {
                                    echo '<li>' . htmlspecialchars($detail) . '</li>';
                                }
                                echo '</ul>';
                                unset($_SESSION['cp_error_details']);
                            }
                            ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <!-- End Feedback Messages -->  

                <div class="container d-flex justify-content-center">
                    <div class="card mt-4 col-xxl-6 col-lg-8 col-md-10 col-sm-12 ">
                        <div class="card-body pt-3">
                            <div class="tab-pane fade pt-3 active show" id="profile-change-password" role="tabpanel">
                                <!-- Change Password Form -->
                                <form method="POST" action="assets/php/admin_process_change_password.php">

                                    <div class="row mb-3 align-items-center">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="current_password" type="password" class="form-control"
                                                id="currentPassword" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="new_password" type="password" class="form-control"
                                                id="newPassword" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="confirm_password" type="password" class="form-control"
                                                id="renewPassword" required>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>











    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/v/bs5/dt-2.2.2/b-3.2.2/r-3.0.4/rg-1.5.1/sp-2.3.3/datatables.min.js"
        integrity="sha384-nymamBYemXgS5CQhRMYwuTFGz1N0o4cocvaMr1vST3CaMJ1h8BW5DfWm9BKAWdzY"
        crossorigin="anonymous"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/dash-script.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script> -->
    <!-- <script src="assets/js/datatables-simple-demo.js"></script> -->


    <!-- Add this script block near the end of your admin_users.php body -->
    <script>
        function confirmAndDeactivate(userId, username) {
            if (confirm(`Are you sure you want to DEACTIVATE user '${username}' (ID: ${userId})? They will not be able to log in.`)) {
                // Adjust path if deactivate_user.php is elsewhere
                window.location.href = 'assets/php/deactivate_user.php?id=' + userId;
            }
        }
        // Optional: Reactivate function (requires reactivate_user.php script)
        function confirmAndReactivate(userId, username) {
            if (confirm(`Are you sure you want to REACTIVATE user '${username}' (ID: ${userId})? They will be able to log in again.`)) {
                // Adjust path if reactivate_user.php is elsewhere
                window.location.href = 'assets/php/reactivate_user.php?id=' + userId; // Assumes you create this file
            }
        }
    </script>




</body>

</html>