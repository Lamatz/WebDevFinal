<?php
// profile.php - User Profile Display/Edit Page

// ALWAYS start session at the very top
session_start();


// --- Retrieve and Clear Session Messages/Data ---
$errors = $_SESSION['errors'] ?? []; // <--- Keep this one
$success_message = $_SESSION['success'] ?? null;
$form_data = $_SESSION['form_data'] ?? []; // Data to repopulate form on error
$fetch_error = $_SESSION['error_fetch'] ?? null; // For initial fetch errors

unset($_SESSION['errors']);
unset($_SESSION['success']);
unset($_SESSION['form_data']);
unset($_SESSION['error_fetch']);

// --- Authentication Check ---
// If user_id is not set in the session, they are not logged in.
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view this page.";
    // Redirect to the login page. Adjust path as necessary.
    header('Location: /webdevfinal/login-form.php');
    exit(); // Stop script execution after redirect
}

require_once 'includes/db_connect.php'; // Database connection

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

// Define default profile image path (used if DB value is null or empty)
$default_profile_pic = '/webdevfinal/assets/uploads/default_pfp.jpg'; // Adjust path as needed

// Initialize variables to hold user data (provide defaults)
// $username = '';
$email = '';
$first_name = '';
$last_name = '';
$birthday = '';
$sex = '';
$profile_photo_path = $default_profile_pic; // Start with default

// Prepare the SQL statement to prevent SQL injection attacks
// Select the columns you need from the 'users' table
$sql = "SELECT username, email, first_name, last_name, birthday, sex, profile_photo_path, created_at, updated_at
        FROM users
        WHERE user_id = ?"; // Use a placeholder (?) for the user ID

// Prepare the statement
if ($stmt = mysqli_prepare($conn, $sql)) {

    // Bind the user_id parameter to the placeholder
    // "i" indicates the parameter is an integer
    mysqli_stmt_bind_param($stmt, "i", $user_id);

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
            $birthday = $userData['birthday'] ?? '';
            $sex = $userData['sex'] ?? '';
            $created_at = $userData['created_at'] ?? '';
            $updated_at = $userData['updated_at'] ?? '';
            // $password;  

            // Handle the profile photo path specifically
            // If it's NULL or an empty string in the DB, use the default
            $profile_photo_path = $userData['profile_photo_path'] ?? $default_profile_pic;
            if (empty($profile_photo_path)) {
                $profile_photo_path = $default_profile_pic;
            }
            // Optional: If you store only the filename, prepend the path here
            // else {
            //     $profile_photo_path = '/webdevfinal/uploads/' . $profile_photo_path; // Example
            // }

        } else {
            // This case is unlikely if the session user_id is valid,
            // but handle it just in case (e.g., user deleted since login).
            $_SESSION['error'] = "Could not find your profile data.";
            // Optionally, destroy session and redirect to login
            // session_destroy();
            // header('Location: /webdevfinal/login-form.php');
            // exit();
        }
    } else {
        // Error executing the statement
        error_log("Error executing profile query: " . mysqli_stmt_error($stmt));
        echo "An error occurred while retrieving your profile.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);

} else {
    // Error preparing the statement
    error_log("Error preparing profile query: " . mysqli_error($conn));
    echo "An error occurred while preparing to retrieve your profile.";
}

// Close the database connection
mysqli_close($conn);

// --- Data is now fetched directly from the database ---
// You can now use the variables $username, $email, $first_name, etc.,
// in your HTML below.

// $errors = $_SESSION['errors'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">



    <link rel="stylesheet" href="css/client-style/profile-style.css">

</head>

<body>

    <div class="container rounded bg-white mt-5 mb-5">
        <br>

        <!-- Display Success/Error Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show " role="alert">
                <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php // Display general fetch errors or database errors from process script ?>
        <?php $general_error = $errors['database'] ?? $_SESSION['error_fetch'] ?? null; ?>
        <?php if ($general_error): ?>
            <div class="alert alert-danger alert-dismissible fade show " role="alert">
                <?php echo htmlspecialchars($general_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_fetch']); // Clear fetch error after display ?>
        <?php endif; ?>

        <?php if ($_SESSION['error']): ?>
            <div class="alert alert-danger alert-dismissible fade show " role="alert">
                <?php echo htmlspecialchars($errors); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); // Clear fetch error after display ?>
        <?php endif; ?>


        <?php // Display file upload specific error if not caught by field validation
        if (isset($errors['profile_photo']) && !isset($errors['database'])): ?> 
            <div class="alert alert-danger alert-dismissible fade show " role="alert">
                <?php echo htmlspecialchars($errors['profile_photo']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>



        <!-- BACK BUTTON -->
        <div class=" pt-0 text-end me-3"> <?php // Moved button, aligned right ?>
            <a href="mainpage.php" class="btn btn-secondary profile-button">Back</a> <?php // Simpler back button ?>
        </div>


        <div class="row">
            <form action="./assets/php/edit_profile_process.php" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-md-row ">
                <div class="col-md-4 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">


                        <img class="rounded-circle mt-5" width="150px" height="150px" style="object-fit: cover;"
                            id="profileImageDisplay" src="<?php echo htmlspecialchars($profile_photo_path); ?>"
                            onerror="this.onerror=null; this.src='<?php echo htmlspecialchars($default_profile_pic); ?>';"
                            alt="Profile Picture">


                        <span class="fw-bold"><?php echo htmlspecialchars($username); ?></span>
                        <span class="text-secondary"><?php echo htmlspecialchars($email); ?></span>
                        <span> </span>
                        <?php // Make this button trigger the file input ?>

                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="changePhotoBtn" disabled>Change
                            Photo </button>

                        <?php // Hidden file input - ADDED ?>
                        <input type="file" name="profile_photo" id="profileImageInput" class="editable-field d-none"
                            accept="image/jpeg, image/png, image/gif" disabled>
                        <?php // Display file upload specific error from session ?>
                        <?php if (isset($errors['profile_photo'])): ?>
                            <div class="text-danger mt-2">
                                <small><?php echo htmlspecialchars($errors['profile_photo']); ?></small>
                            </div>
                        <?php endif; ?>
                        <!-- </form> -->


                    </div>
                </div>

                <div class="col-md-8 d-flex justify-content-center">


                    <!-- FORM START -->
                    <!-- <form action="./assets/php/edit_profile_process.php" method="POST" enctype="multipart/form-data"
                    class="w-100">  -->
                    <div class="p-3 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Profile Settings</h4>
                        </div>

                        <div class="row mt-2">

                            <!-- FIRST NAME -->
                            <div class="col-md-6 mb-2"> <?php // Added mb-2 for spacing ?>
                                <label for="first_name" class="labels mb-1">First Name</label>
                                <?php // Use for attribute ?>
                                <input type="text" id="first_name" class="form-control editable-field"
                                    placeholder="First name" name="first_name"
                                    value="<?php echo htmlspecialchars($first_name); ?>" disabled>
                                <?php // Use disabled instead of readonly ?>
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <!-- Use d-block if Bootstrap hides it by default -->
                                        <?php echo htmlspecialchars($errors['first_name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- LAST NAME -->
                            <div class="col-md-6 mb-2">
                                <label for="last_name" class="labels mb-1">Last Name</label>
                                <input type="text" id="last_name" class="form-control editable-field"
                                    placeholder="Last name" name="last_name"
                                    value="<?php echo htmlspecialchars($last_name); ?>" disabled>
                                <?php // Use disabled ?>
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <!-- Use d-block if Bootstrap hides it by default -->
                                        <?php echo htmlspecialchars($errors['last_name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- USERNAME -->
                            <div class="col-md-12 mb-2">
                                <label class="labels mb-1">Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="username"
                                    value="<?php echo htmlspecialchars($username); ?>" disabled>
                                <?php // Keep username disabled ?>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <!-- Use d-block if Bootstrap hides it by default -->
                                        <?php echo htmlspecialchars($errors['username']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- EMAIL -->
                            <div class="col-md-12 mb-2">
                                <label for="email" class="labels mb-1">Email</label>
                                <input type="email" id="email" class="form-control editable-field"
                                    placeholder="Email address" name="email"
                                    value="<?php echo htmlspecialchars($email); ?>" disabled> <?php // Use disabled ?>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <!-- Use d-block if Bootstrap hides it by default -->
                                        <?php echo htmlspecialchars($errors['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>



                            <!-- BIRTHDAY -->
                            <div class="col-md-6 mb-2">
                                <label for="birthday" class="labels mb-1">Date of Birth</label>
                                <input type="date" id="birthday" class="form-control editable-field"
                                    placeholder="YYYY-MM-DD" name="birthday"
                                    value="<?php echo htmlspecialchars($birthday); ?>" disabled>
                                <?php // Use disabled ?>
                                <?php if (isset($errors['birthday'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <!-- Use d-block if Bootstrap hides it by default -->
                                        <?php echo htmlspecialchars($errors['birthday']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>


                            <!-- GENDER / SEX -->
                            <div class="col-md-6 mb-2 align-content-center">
                                <p class="labels mb-1">Sex:</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input editable-field" name="sex" id="male"
                                        value="Male" <?php echo (($sex) === 'Male') ? 'checked' : ''; ?> disabled>
                                    <label for="male" class="form-check-label">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input editable-field" name="sex" id="female"
                                        value="Female" <?php echo (($sex) === 'Female') ? 'checked' : ''; ?> disabled>
                                    <label for="female" class="form-check-label">Female</label>
                                </div>
                                <?php if (isset($errors['sex'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <!-- Use d-block if Bootstrap hides it by default -->
                                        <?php echo htmlspecialchars($errors['sex']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- CREATED_AT -->
                            <div class="col-md-6 mb-2">
                                <label for="birthday" class="labels mb-1">Created at</label>
                                <p class="m-0">
                                    <?php
                                    try {
                                        $created_dt = new DateTime($created_at);
                                        // M=Short month, j=Day, Y=Year, g=12hr, i=min, A=AM/PM
                                        echo htmlspecialchars($created_dt->format('M j, Y, g:i A'));
                                    } catch (Exception $e) {
                                        echo htmlspecialchars($created_at); // Fallback
                                    }
                                    ?>
                                </p>
                            </div>

                            <!-- UPDATED_AT -->
                            <div class="col-md-6 mb-2">
                                <label for="birthday" class="labels mb-1">Updated at</label>
                                <p class="m-0">
                                    <?php
                                    try {
                                        $updated_dt = new DateTime($updated_at);
                                        echo htmlspecialchars($updated_dt->format('M j, Y, g:i A'));
                                    } catch (Exception $e) {
                                        echo htmlspecialchars($updated_at); // Fallback
                                    }
                                    ?>
                                </p>
                            </div>

                            <!-- PASSWORD SECTION -->
                            <hr class="my-4"> <?php // Separator for password section ?>
                            <h5 class="mb-3">Change Password (Optional)</h5>

                            <div class="col-md-12 mb-2">
                                <label for="new_password" class="labels mb-1">New Password</label>
                                <input type="password" id="new_password" class="form-control editable-field"
                                    name="new_password" placeholder="Leave blank to keep current password" disabled>
                                <?php // Use disabled ?>
                            </div>
                            <div class="col-md-12 mb-3"> <?php // Added mb-3 ?>
                                <label for="confirm_password" class="labels mb-1">Confirm New Password</label>
                                <input type="password" id="confirm_password" class="form-control editable-field"
                                    name="confirm_password" placeholder="Confirm new password" disabled>
                                <?php // Use disabled ?>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback d-block"> <!-- Use d-block if Bootstrap hides it by default -->
                                    <?php echo htmlspecialchars($errors['password']); ?>
                                </div>
                            <?php endif; ?>
                        </div> <?php // end row mt-3 ?>

                        <div class="mt-4 text-center">
                            <button class="btn btn-secondary profile-button mx-2 text-white" type="button"
                                id="editProfileBtn">Edit Profile</button>
                            <button class="btn btn-primary profile-button mx-2" type="submit" id="saveProfileBtn"
                                disabled>Save Changes</button> <?php // Initially disabled ?>
                        </div>

                    </div> <?php // end p-3 py-4 ?>
            </form> <?php // end form ?>

        </div>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"> </script>

    <?php // Simple JavaScript to enable editing (Place before closing </body>) ?>
    <!-- JavaScript to enable editing -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get references to the buttons and fields
            const editBtn = document.getElementById('editProfileBtn');
            const saveBtn = document.getElementById('saveProfileBtn');
            const editableFields = document.querySelectorAll('.editable-field'); // Use the class we added
            const changePhotoBtn = document.getElementById('changePhotoBtn'); // Get change photo button
            const profileImageInput = document.getElementById('profileImageInput'); // Get file input
            const profileImageDisplay = document.getElementById('profileImageDisplay'); // Get img tag

            let originalValues = {}; // Object to store original values



            // --- Helper Functions ---

            // Function to store the original values from the fields into data attributes
            function storeOriginalValues() {
                originalValues = {}; // Reset
                editableFields.forEach(field => {
                    // Store the current value in a 'data-original-value' attribute
                    // Use toString() to handle potential non-string values safely, though unlikely here
                    field.dataset.originalValue = field.value.toString();
                });

                originalValues['profileImageDisplaySrc'] = profileImageDisplay.src;
            }

            // Function to restore values from the data attributes and disable fields
            function restoreAndDisableFields() {
                editableFields.forEach(field => {
                    // Check if an original value was stored before trying to restore
                    if (field.dataset.originalValue !== undefined) {
                        field.value = field.dataset.originalValue;
                    }
                    field.disabled = true; // Disable the field
                    // Optional: remove the stored attribute after restoring if desired
                    // delete field.dataset.originalValue;
                });


                // --- ADD THIS PART ---
                // Restore the image source from the stored original value
                if (originalValues['profileImageDisplaySrc']) {
                    profileImageDisplay.src = originalValues['profileImageDisplaySrc'];
                }
                // --- END OF ADDED PART ---
                profileImageInput.value = ''; // IMPORTANT
            }

            // --- Initial Setup ---

            // Store the initial values loaded from the database when the page loads
            storeOriginalValues();

            // --- Event Listener ---

            editBtn.addEventListener('click', function () {
                // Determine the current state by checking if fields are disabled
                // We check the first editable field; assume all have the same state
                const isCurrentlyDisabled = editableFields.length > 0 && editableFields[0].disabled;

                if (isCurrentlyDisabled) {
                    // --- Action: Enable Editing ---

                    // Store the *current* values again just before enabling,
                    // in case the user edits, saves (page reloads), then edits again.
                    // Or if they edit, cancel, then edit again without reload.
                    storeOriginalValues();

                    // Enable all editable fields
                    editableFields.forEach(field => {
                        field.disabled = false;
                    });

                    // Enable the Save button
                    saveBtn.disabled = false;

                    // Enaable the Change Photo Button
                    changePhotoBtn.disabled = false;

                    // Change Edit button text and style (optional)
                    editBtn.textContent = 'Cancel';
                    editBtn.classList.remove('btn-secondary');


                    // Focus the first editable field for better UX
                    if (editableFields.length > 0) {
                        editableFields[0].focus();
                    }

                } else {
                    // --- Action: Cancel Editing ---

                    // Restore original values and disable fields
                    restoreAndDisableFields();

                    // Disable the Save button
                    saveBtn.disabled = true;

                    // Disable the Change Photo Button
                    changePhotoBtn.disabled = true;

                    // Change Edit button text and style back
                    editBtn.textContent = 'Edit Profile';
                    editBtn.classList.add('btn-secondary'); // Revert to original style
                    
                }
            });

            // // CHANGE PHOTO Button
            changePhotoBtn.addEventListener('click', function () {
                profileImageInput.click(); // Trigger the hidden file input
            });

            // FILE INPUT Change Event (for preview)
            profileImageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    // Use FileReader to show a preview
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImageDisplay.src = e.target.result; // Update image preview
                    }
                    reader.readAsDataURL(file);

                    // Enable save button if editing wasn't already active
                    if (saveBtn.disabled) {
                        // Optionally, enable fields only if user clicks edit *after* choosing photo?
                        // For simplicity now, selecting a photo enables save.
                        saveBtn.disabled = false;
                        // Maybe change edit button text if desired?
                        // editBtn.textContent = 'Cancel';
                        // editBtn.classList.remove('btn-secondary');
                        // Enable fields too? Depends on desired workflow
                        // editableFields.forEach(field => { field.disabled = false; });
                    }
                }
            });

            // --- Optional: Reset state after successful form submission ---
            // If your form submission reloads the page, this isn't strictly necessary
            // as the page will reload with the initial (disabled) state.
            // If you use AJAX to submit, you would call restoreAndDisableFields()
            // and reset the button states in your AJAX success handler.

        }); // <-- End of DOMContentLoaded listener
    </script>
</body>

</html>