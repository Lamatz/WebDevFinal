<?php
// registration-form.php - Frontend Display

// ALWAYS start session at the top to read feedback messages
session_start();

// --- Retrieve feedback from session ---
// Use null coalescing operator (??) for safety
$errors = $_SESSION['errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
$success_message = $_SESSION['success_message'] ?? '';

// --- Clear session variables after retrieving them ---
// This prevents messages/data from persisting across page loads
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
unset($_SESSION['success_message']);

// Include header AFTER session start and potential redirects
// require_once 'includes/header.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/form-style/registration-form.css">


  <title>Registration Form</title>
</head>

<body>


  <section class="p-3 p-md-4 p-xl-5">
    <div class="container">

      <!--First Section -->
      <div class="card border-light-subtle shadow-sm">
        <div class="row g-0">

        <!-- DISPLAY ERRORS -->
        <?php // Display Success Message
          if (!empty($success_message)): ?>
              <div class="alert alert-success" role="alert">
                  <?php echo htmlspecialchars($success_message); ?>
                    <a href="login-form.php" class="alert-link">Login here</a>.
              </div>
          <?php endif; ?>

          <?php // Display General Database or Logic Errors from session
          if (!empty($errors['database'])): ?>
              <div class="alert alert-danger" role="alert">
                  <?php echo htmlspecialchars($errors['database']); ?>
              </div>
          <?php endif; ?>



          <?php // Only show the form if registration wasn't just successful
                  // OR if there are errors (other than just a success message existing)
            if (empty($success_message)):
            ?>
          <!-- DISPLAY ERROR END -->



          <div class="col-12 col-md-6 information-box">
            <div class="d-flex justify-content-center h-100">
              <div class="col-10 col-xl-8 pb-3">

                <!-- CONTENT HEADING -->
                <div class="row mb-1 pt-3 pt-md-4 pt-xl-5 ">
                  <div class="col-12">
                    <div class="mb-5 align-self">
                      <h2 class="h3">General Information</h2>
                      <h3 class="fs-6 fw-normal text-white m-0">Enter your details to register</h3>
                    </div>
                  </div>
                </div>


                <form action="./assets/php/register.php" method="POST" id="RegistrationForm" enctype="multipart/form-data" novalidate>

                  <div class="row gy-3 gy-md-4 overflow-hidden">

                  <!-- FIRST NAME -->
                    <div class="col-6">
                      <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>"
                        id="first_name" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>"
                        required>
                        <?php if (isset($errors['first_name'])): ?>
                          <div class="invalid-feedback d-block error-light "> <!-- Use d-block if Bootstrap hides it by default -->
                            <?php echo htmlspecialchars($errors['first_name']); ?>
                          </div>
                        <?php endif; ?>
                    </div>

                    <!-- LAST NAME -->
                    <div class="col-6">
                      <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                      name="last_name" id="lastName" placeholder="Last Name" value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>"
                        required>
                        <?php if (isset($errors['last_name'])): ?>
                          <div class="invalid-feedback d-block error-light"> <!-- Use d-block if Bootstrap hides it by default -->
                            <?php echo htmlspecialchars($errors['last_name']); ?>
                          </div>
                        <?php endif; ?>
                    </div>

                    <!-- Birthday -->
                    <div class="col-12">
                      <label for="birthday" class="form-label">Date of Birth <span
                          class="text-danger">*</span></label>
                      <input type="date" class="form-control <?php echo isset($errors['birthday']) ? 'is-invalid' : ''; ?>" 
                      name="birthday" id="birthday" value="<?php echo htmlspecialchars($form_data['birthday'] ?? ''); ?>"
                      required>
                      <?php if (isset($errors['birthday'])): ?>
                          <div class="invalid-feedback d-block error-light"> <!-- Use d-block if Bootstrap hides it by default -->
                            <?php echo htmlspecialchars($errors['birthday']); ?>
                          </div>
                        <?php endif; ?>
                    </div>

                    <!-- GENDER -->
                    <div class="col-12">
                      <p>Sex:<span class="text-danger">*</span></p>
                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="Gender" id="male" value="Male" 
                        <?php echo (($form_data['Gender'] ?? '') === 'Male') ? 'checked' : ''; ?> required>
                        <label for="male" class="form-check-label">Male</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="Gender" id="female" value="Female" 
                        <?php echo (($form_data['Gender'] ?? '') === 'Female') ? 'checked' : ''; ?> required>
                        <label for="female" class="form-check-label">Female</label>
                      </div>
                      <?php if (isset($errors['Gender'])): ?>
                          <div class="invalid-feedback d-block error-light"> <!-- Use d-block if Bootstrap hides it by default -->
                            <?php echo htmlspecialchars($errors['Gender']); ?>
                          </div>
                        <?php endif; ?>
                    </div>



                    <!-- Profile Picture -->
                    <div class="col-12">
                       <label for="image" class="form-label">Profile Picture <span
                           class="text-danger">*</span></label>
                      <input class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>"
                             type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" <?php // Be more specific with accept ?>
                         required>
                        <?php if (isset($errors['image'])): ?>
                          <div class="invalid-feedback d-block error-light">
                            <?php echo htmlspecialchars($errors['image']); ?>
                          </div>
                        <?php endif; ?>
                     </div>



                  </div>

              </div>
            </div>
          </div>


          <!-- SECOND SECTION -->
          <div class="col-12 col-md-6 registration-form-details">
            <div class="card-body p-3 p-md-4 p-xl-5">
              <div class="row">
                <div class="col-12">
                  <div class="mb-5">
                    <h2 class="h3">Account Details</h2>
                    <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                  </div>
                </div>
              </div>

              <div class="row gy-3 gy-md-4 overflow-hidden">


                  <!-- USERNAME -->
                  <div class="col-12">
                  <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                  <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                      name="username" id="username" placeholder="Username" value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>"
                      required>
                      <?php if (isset($errors['username'])): ?>
                        <div class="invalid-feedback d-block"> <!-- Use d-block if Bootstrap hides it by default -->
                          <?php echo htmlspecialchars($errors['username']); ?>
                        </div>
                      <?php endif; ?>
                </div>

                <!-- EMAIL -->
                <div class="col-12">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                    name="email" id="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                    required>
                    <?php if (isset($errors['email'])): ?>
                      <div class="invalid-feedback d-block"> <!-- Use d-block if Bootstrap hides it by default -->
                        <?php echo htmlspecialchars($errors['email']); ?>
                      </div>
                    <?php endif; ?>
                </div>

                <!-- PASSWORD -->
                <div class="col-12">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="password" id="password" value="" required>
                  <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback d-block"> <!-- Use d-block if Bootstrap hides it by default -->
                      <?php echo htmlspecialchars($errors['password']); ?>
                    </div>
                  <?php endif; ?>
                </div>


                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                    <label class="form-check-label text-secondary" for="iAgree">
                      I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                    </label>
                     <?php if (isset($errors['iAgree'])): ?>
                          <div class="invalid-feedback d-block">
                            <?php echo htmlspecialchars($errors['iAgree']); ?>
                          </div>
                        <?php endif; ?>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn bsb-btn-xl sign-up-button" type="submit">Sign up</button>
                  </div>
                </div>
              </div>
              </form>
              <?php endif; // End conditional form display ?>
              <!-- END OF FORM -->

              <div class="row">
                <div class="col-12">
                  <hr class="mt-5 mb-4 border-secondary-subtle">
                  <p class="m-0 text-secondary text-center">Already have an account? <a href="./login-form.php"
                      class="link-primary text-decoration-none">Sign in</a></p>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  </section>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

</body>

</html>