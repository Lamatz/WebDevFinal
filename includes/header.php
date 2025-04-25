<?php
// includes/header.php

// Start session if it hasn't already been started on the including page
// This makes the header self-sufficient regarding session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine if the user is logged in by checking for the user_id in the session
$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Optional: Define a default page title if not set by the including page
$default_page_title = "My Website";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Your Custom CSS -->
    <?php // Adjust path to your main CSS file. Using root-relative path is often best. ?>
    <link rel="stylesheet" href="/webdevfinal/css/style.css"> <?php // Example path ?>

    <?php // Use the page title set by the including page, or the default ?>
    <title><?php echo htmlspecialchars($page_title ?? $default_page_title); ?></title>

    <style>
        /* Basic styling for the profile pic in the navbar */
        .navbar-profile-pic {
            height: 35px;
            width: 35px;
            border-radius: 50%;
            object-fit: cover; /* Ensures image covers the area nicely */
            margin-right: 8px;
            vertical-align: middle;
        }
        .navbar-nav .nav-item{
            padding: 0, 50px !important;
        }
    </style>



</head>


<body>

    <?php // Example using a Bootstrap Navbar ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container-fluid">
            <?php // Link to your home page - Adjust path if needed ?>
            <a class="navbar-brand" href="/webdevfinal/index.php">Troolife</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <?php // Left-aligned navigation items (example) ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/webdevfinal/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webdevfinal/about.php">About</a>
                    </li>
                    <?php // Add other public navigation links here ?>
                </ul>

                <?php // Right-aligned navigation items (Login/User Info) ?>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <?php if ($is_logged_in): ?>
                        <?php // --- User is Logged In --- ?>

                        <li class="nav-item d-flex align-items-center">

                             <?php // Display profile photo if available ?>
                            <?php if (isset($_SESSION['profile_photo']) && !empty($_SESSION['profile_photo'])): ?>
                                <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>"
                                     alt="Profile Photo"
                                     class="navbar-profile-pic">
                            <?php endif; ?>

                            <span class="navbar-text">
                                Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?>!
                            </span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/webdevfinal/profile.php">Profile</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/webdevfinal/edit_profile.php">Edit Profile</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/webdevfinal/logout.php">Logout</a>
                        </li>

                    <?php else: ?>
                        <?php // --- User is Logged Out --- ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/webdevfinal/login-form.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webdevfinal/registration-form.php">Register</a>
                        </li>

                    <?php endif; ?>
                </ul>
            </div> <?php // <!-- /.collapse --> ?>
        </div> <?php // <!-- /.container-fluid --> ?>
    </nav>
