<?php
session_start();

// --- Authentication Check ---
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    header('Location: /webdevfinal/login-form.php');
    exit();
}

$is_logged_in = true;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="/webdevfinal/css/style.css"> <?php // Adjust path ?>

    <title>Main Page</title> 

    <style>
        /* Basic styling for the profile pic in the navbar */
        .navbar-profile-pic {
            height: 35px; width: 35px; border-radius: 50%; object-fit: cover;
            margin-right: 8px; vertical-align: middle;
        }
    </style>
</head>



<body>

    <!-- NAVBAR START -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/webdevfinal/index.php">Troolife</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="/webdevfinal/index.php">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/webdevfinal/about.php">About</a> </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <?php // --- User is Logged In (We know this is true on this page) --- ?>
                    <li class="nav-item d-flex align-items-center">
                         <?php if (isset($_SESSION['profile_photo']) && !empty($_SESSION['profile_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Profile Photo" class="navbar-profile-pic">
                         <?php endif; ?>
                         <span class="navbar-text"> Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?>! </span>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/webdevfinal/user-profile.php">Profile</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/webdevfinal/edit_profile.php">Edit Profile</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/webdevfinal/assets/php/logout.php">Logout</a> </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->


    <!-- MAIN CONTENT START -->
    <main class="container mt-4">
        <h1>Client Profile Page</h1>
        <p>It works!! This content is only shown if you are logged in.</p>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    </main>
    <!-- MAIN CONTENT END -->




    <!-- FOOTER START -->
    <footer class="container mt-5 text-center text-muted">
        <hr>
    </footer>
    <!-- FOOTER END -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



</body> 
</html> 