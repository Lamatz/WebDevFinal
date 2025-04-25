      
<?php
// login.php - Frontend Login Form

// ALWAYS start session at the top to read feedback messages
session_start();

// --- Retrieve feedback from session ---
$error = $_SESSION['error'] ?? ''; // From login_process.php
$success_message = $_SESSION['success_message'] ?? ''; // Potentially from registration

// --- Clear session variables after retrieving them ---
unset($_SESSION['error']);
unset($_SESSION['success_message']); // Clear registration success if shown here

// Include header AFTER session start and potential redirects
// require_once 'includes/header.php'; // Assumes header.php exists
?>





<!doctype html>

<html lang="en">

<head>
    <title>Admin Login</title> <!-- More specific title -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your Custom CSS - Link AFTER Bootstrap -->
    <link rel="stylesheet" href="css/admin-login.css">

</head>

<body>
    <section class="ftco-section">
        <div class="container">

        <?php // Display Success Message (e.g., after registration)
        if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php // Display Login Error Message
        if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Admin Portal Login</h2> <!-- Slightly more descriptive -->
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o" aria-hidden="true"></span> <!-- Hide decorative icon -->
                        </div>
                        <h3 class="text-center mb-4">Sign In</h3> <!-- Alternative heading -->

                        <!-- FORM START -->
                        <form action="/webdevfinal/admin/assets/php/admin_login.php" method="POST" class="login-form" novalidate>
                            <div class="form-group mb-3"> <!-- Added mb-3 for consistent spacing -->
                                <!-- Added label (visually hidden) and id/autocomplete -->
                                <label for="username" class="visually-hidden">Username</label>
                                <input type="text" class="form-control rounded-left" placeholder="Username" id="username" name="username" autocomplete="username" required>
                            </div>
                            <div class="form-group mb-3 d-flex"> <!-- Added mb-3 -->
                                <!-- Added label (visually hidden) and id/autocomplete -->
                                <label for="password" class="visually-hidden">Password</label>
                                <input type="password" class="form-control rounded-left" placeholder="Password" id="password" name="password" autocomplete="current-password" required>
                            </div>


							<!-- PROBABLY NOT PART OF THE SCOPE -->
                            <!-- <div class="form-group mb-3 d-md-flex"> 
                                <div class="w-50">
                                    <label class="checkbox-wrap checkbox-primary">Remember Me

                                        <input type="checkbox" name="remember" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="#">Forgot Password?</a> 
                                </div>
                            </div> -->
							
                            <div class="form-group mt-5 ">
                                <button type="submit" name="login_submit" class="btn btn-primary rounded submit p-3 px-5 w-100">Sign In</button> <!-- Changed text, added w-100 for full width -->
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

    