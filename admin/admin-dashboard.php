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






// FOR FETCHING USER DATA --------

$all_users_data = []; // Initialize an array to hold all user data

// Prepare SQL to get all admins - Add 'created_at' for the "Date Joined" column
// Including user_id is useful for future actions like deactivate
$sql_all = "SELECT user_id, username, email, first_name, last_name, created_at, is_active
            FROM users
            ORDER BY username ASC"; // Optional: Order the results


// No parameters to bind here since we're selecting all
if ($result_all = mysqli_query($conn, $sql_all)) { // Can use simple query if no parameters
    // Fetch all rows into the array
    while ($row = mysqli_fetch_assoc($result_all)) {
        $all_users_data[] = $row; // Add each users's data to the array
    }
    mysqli_free_result($result_all); // Free the result set
} else {
    // Error fetching all admins
    error_log("Error executing all users query: " . mysqli_error($conn));
    // You might want to set an error message for the user here too
    $_SESSION['error'] = "Could not retrieve user list."; // Or display within the page
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



    <!-- DASHBOARD WEBSITE LINKS -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.2.2/b-3.2.2/r-3.0.4/rg-1.5.1/sp-2.3.3/datatables.min.css" rel="stylesheet" integrity="sha384-prK16DqKkGc9Kw+9yPfb7wK62lObBTe2M5eeEAvMMPT8iouxp4lbvY9CtkoT7w8s" crossorigin="anonymous"> -->



    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/dashboard-style.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">






    <title>Admin User Display</title>
</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="#">TrooLife</a>
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
                        <div class="sb-sidenav-menu-heading">Sections</div>
                        <a class="nav-link" href="#">
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
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">USER ACCOUNTS</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>



                    <!-- Place this inside the <main> container in admin_users.php -->
                    <?php if (isset($_SESSION['user_list_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['user_list_success']);
                            unset($_SESSION['user_list_success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_list_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['user_list_error']);
                            unset($_SESSION['user_list_error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_list_info'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['user_list_info']);
                            unset($_SESSION['user_list_info']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- End of feedback messages -->





                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            User Accounts
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Date Joined</th>
                                        <th>Actions</th>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Date Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>

                                <tbody>
                                    <?php foreach ($all_users_data as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                            <td>
                                                <?php
                                                // Format the date nicely (e.g., YYYY-MM-DD)
                                                // Check if created_at is not null before formatting
                                                echo htmlspecialchars(
                                                    $user['created_at'] ? date('Y-m-d', strtotime($user['created_at'])) : 'N/A'
                                                );
                                                ?>
                                            </td>
                                            <td>


                                                <div class="text-center">
                                                <?php
                                                if ((int) $user['is_active'] === 1):
                                                    ?>
                                                    <!-- User is Active -> Show Deactivate Button -->
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmAndDeactivate(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars(addslashes($user['username'])); ?>')">
                                                        Deactivate
                                                    </button>
                                                <?php else: // User is Inactive (is_active is 0 or potentially NULL/other) ?>
                                                    <!-- User is Inactive -> Show Reactivate Button -->
                                                    <button type="button" class="btn btn-success btn-sm ms-1"
                                                        onclick="confirmAndReactivate(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars(addslashes($user['username'])); ?>')">
                                                        Reactivate
                                                    </button>
                                                <?php endif; ?>
                                                </div>



                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php // --- End of loop --- ?>

                                    <?php // Optional: Display a message if no user found ?>
                                    <?php if (empty($all_users_data)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No user accounts found.</td>
                                        </tr>
                                    <?php endif; ?>
                            </table>
                        </div>
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











  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/dash-script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/datatables-simple-demo.js"></script>


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
                window.location.href = 'assets/php/reactivate_user.php?id=' + userId; 
            }
        }
    </script>




</body>

</html>