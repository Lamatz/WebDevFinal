<?php
// admin-add-admin.php

// ALWAYS start session at the very top
session_start();

// --- Authentication Check ---
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    header('Location: /webdevfinal/admin/admin-login-form.php');
    exit();
}

// --- If execution reaches here, the user IS logged in ---



require_once './../includes/db_connect.php'; // Database connection


// --- Fetch Logged-in Admin's Info (for display) ---
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
$sql_all = "SELECT admin_id, username, email, first_name, last_name, created_at
            FROM admins
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

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/dashboard-style.css">

    <title>Admin: add Admin</title>

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
            <main>
                <div class="container-flex px-4">

                    <!-- HEADER TITLE -->
                    <h1 class="mt-4">ADD ACCOUNT</h1>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <!-- HEADER TITLE END -->

                    <!-- Feedback Messages Area -->
                    <?php if (isset($_SESSION['add_admin_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['add_admin_success']);
                            unset($_SESSION['add_admin_success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['add_admin_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong>
                            <?php echo htmlspecialchars($_SESSION['add_admin_error']);
                            unset($_SESSION['add_admin_error']); ?>
                            <?php // Display detailed errors if available
                                if (isset($_SESSION['add_admin_error_details']) && is_array($_SESSION['add_admin_error_details'])) {
                                    echo '<ul>';
                                    foreach ($_SESSION['add_admin_error_details'] as $detail) {
                                        echo '<li>' . htmlspecialchars($detail) . '</li>';
                                    }
                                    echo '</ul>';
                                    unset($_SESSION['add_admin_error_details']);
                                }
                                // Display preserved form data if validation failed
                                $form_data = $_SESSION['add_admin_form_data'] ?? [];
                                unset($_SESSION['add_admin_form_data']);
                                ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php // Ensure $form_data exists even if there was no previous error
                    $form_data = $form_data ?? [];
                    ?>
                    <!-- FEEDBACK MESSAGE AREA END -->


                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg my-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Create Admin Account</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="assets/php/process_add_admin.php" id="addAdminForm"
                                        novalidate>
                                        <div class="row mb-3">

                                            <!-- FIRST NAME -->
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="text" class="form-control" id="inputFirstName"
                                                        name="first_name" required placeholder="First Name"
                                                        value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>">
                                                    <label for="inputFirstName">First name</label>
                                                </div>
                                            </div>

                                            <!-- LAST NAME -->
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="inputLastName"
                                                        name="last_name" required placeholder="Last Name"
                                                        value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>">
                                                    <label for="inputLastName">Last name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">

                                            <!-- USERNAME -->
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="text" class="form-control" id="inputUsername"
                                                        name="username" required placeholder="Username"
                                                        value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>">
                                                    <label for="inputUsername">Username</label>
                                                </div>
                                            </div>

                                            <!-- EMAIL ADDRESS -->
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="email" class="form-control" id="inputEmail"
                                                        name="email" required placeholder="Email"
                                                        value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                                                    <label for="inputEmail">Email address</label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mb-3">

                                            <!-- PASSWORD -->
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="password" class="form-control" id="inputPassword"
                                                        name="password" required placeholder="Create a Password">
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                            </div>

                                            <!-- CONFIRM PASSWORD -->
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input type="password" class="form-control"
                                                        id="inputConfirmPassword" name="confirm_password" required
                                                        placeholder="Create a Password">
                                                    <label for="inputPasswordConfirm">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary">Add Admin</button>
                                            <!-- <a href="admin-dashboard.php" class="btn btn-secondary">Cancel</a> -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <hr class="pt-5 my-5">
                    </div>




                    <h1 class="mt-1">ADMIN ACCOUNTS</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="card mt-2 mb-5">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Admin Accounts
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
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Date Joined</th>
                                    </tr>
                                </tfoot>

                                <tbody>
                                    <?php // --- Loop through the fetched user data --- ?>
                                    <?php foreach ($all_users_data as $admin): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                            <td><?php echo htmlspecialchars($admin['first_name']); ?></td>
                                            <td><?php echo htmlspecialchars($admin['last_name']); ?></td>
                                            <td>
                                                <?php
                                                // Format the date nicely (e.g., YYYY-MM-DD)
                                                // Check if created_at is not null before formatting
                                                echo htmlspecialchars(
                                                    $admin['created_at'] ? date('Y-m-d', strtotime($admin['created_at'])) : 'N/A'
                                                );
                                                ?>
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
                            <!-- END OF TABLE -->
                        </div>
                        <!-- END OF CARD -->

                    </div>
                </div>



            </main>



            <!-- FOOTER START -->
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
            <!-- FOOTER END -->

        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/dash-script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/datatables-simple-demo.js"></script>

</body>


</html>