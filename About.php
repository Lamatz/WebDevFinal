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



<!doctype html>
<html lang="en-US">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>TrooLife</title>

  <!-- BOOTSTRAP LINKS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>


  <!-- ICON LINKS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



  <!-- CSS LINKS -->
  <link rel="stylesheet" href="css/footer-style.css">
  <link rel="stylesheet" href="css/header-style.css">

  <link rel="stylesheet" href="css/home-style/homebanner-style.css">
  <link rel="stylesheet" href="css/about-style/about-banner.css">
  <link rel="stylesheet" href="css/about-style/global-about.css">
  <link rel="stylesheet" href="css/about-style/section1.css">
  <link rel="stylesheet" href="css/about-style/service-section.css">
  <link rel="stylesheet" href="css/about-style/funfact-section.css">
  <link rel="stylesheet" href="css/about-style/team-section.css">








</head>



<body class="">


  <?php require 'includes/header.php'; ?>



  <!-- Project Banner Section Start -->
  <div class="section about-banner-image">
    <div class="container">

      <div class="row">
        <div class="d-flex align-items-center">
          <div class="divider-1 mb-2 me-2"></div>
          <h3 id="banner-title" style="color:#F6DBA0">Company</h3>
        </div>
      </div>
      <div class="row">
        <div class="content">
          <h1 id="banner-sub" class="title text-white">The TrooLife Corporate Team</h1>
        </div>
      </div>
    </div>
  </div>
  <!-- Project Banner Section End -->

  <!-- Section 1 Start -->
  <div class="section section-about section-padding-top overflow-hidden mb-5">
    <div class="container">
      <!-- TITLE SECTION -->
      <div class="row my-5 ">
        <h2 class="section1-title text-center container ">TrooLife is led by a highly experienced American
          management
          team.</h2>
      </div>
      <!-- TITLE SECTION END -->


      <!-- DIVIDER -->
      <div class="w-100 d-flex align-items-end justify-content-center ">
        <div class="divider-2 mb-5"></div>
      </div>
      <!-- DIVIDER END -->


      <div class="test-background mt-5">
        <div class="row gx-5 mb-n10 mt-5 h-100 my-5">

          <!-- Right Section -->
          <div
            class="col-lg-6 mb-10 col-md-12 order-2 order-lg-1 d-flex justify-content-center align-items-center section1-left">
            <div class="title-brand ">
              <!-- <img src="images/img5.png" alt=""> -->
              <h1>Tr<span>oo</span>Life</h1>
            </div>
          </div>
          <!-- Right Section End -->

          <!-- Left Section -->
          <div class="col-lg-6 mb-10 col-md-12 align-self-center order-1 order-lg-2">
            <div class="section1-wrapper">

              <h2 class="title mb-4">Global IT Infrastructure and Operations</h2>
              <div class="section1-content">
                <p>
                  The IT systems are hosted and managed in the USA by a highly experienced team of experts with
                  high-speed
                  Internet hub connections in Asia
                </p>
                <p>
                  Experienced and responsive Member support is based in Hong Kong and Taiwan.
                </p>
                <p>
                  Product Inventory is shipped from the USA, to a highly-efficient distribution center in Hong Kong,
                  which
                  manages shipments throughout the PRC. The company maintains financial operations in secure American
                  banks
                  and financial institutions to maintain safe and stable operations.
                </p>
              </div>
            </div>
          </div>
          <!-- Left Section End -->

        </div>
      </div>

    </div>
  </div>
  <!-- Section 1 End -->


  <!-- Services Section Start -->
  <div class="section section-padding-top bg-light my-5">
    <div class="container">
      <div class="row">
        <div class="col-12 mb-5">
          <!-- Section Title Start -->
          <div class="section-title">
            <h2 class="title">Why Choose Us</h2>
          </div>
          <!-- Section Title End -->
        </div>

        <div class="col-12">
          <div class="service-inner-container">

            <!-- Service Block Start -->
            <div class="service-block">
              <div class="inner-box d-flex flex-column">
                <h5 class="title">
                  <a href="#">profressional and dedicate team</a>
                </h5>
                <p>Building architectures with modern technology.</p>
                <div class="icon-link-bottom mt-auto">
                  <i class="icon icofont-labour"></i>
                  <a href="#" class="more">more</a>
                </div>
              </div>
            </div>
            <!-- Service Block End -->

            <!-- Service Block Start -->
            <div class="service-block">
              <div class="inner-box d-flex flex-column">
                <h5 class="title">
                  <a href="#">unique design</a>
                </h5>
                <p>Bring the beautifully for your house. Just enjoy!</p>
                <div class="icon-link-bottom mt-auto">
                  <i class="icon icofont-ruler-compass-alt"></i>
                  <a href="#" class="more">more</a>
                </div>
              </div>
            </div>
            <!-- Service Block End -->

            <!-- Service Block Start -->
            <div class="service-block">
              <div class="inner-box d-flex flex-column">
                <h5 class="title">
                  <a href="#">affordable and flexiable</a>
                </h5>
                <p>Bring nature in your house. Health is important</p>
                <div class="icon-link-bottom mt-auto">
                  <i class="icon icofont-credit-card"></i>
                  <a href="#" class="more">more</a>
                </div>
              </div>
            </div>
            <!-- Service Block End -->

            <!-- Service Block Start -->
            <div class="service-block">
              <div class="inner-box d-flex flex-column">
                <h5 class="title">
                  <a href="#">24/7 support</a>
                </h5>
                <p>Consulting solutions and make plan to renovation</p>
                <div class="icon-link-bottom mt-auto">
                  <i class="icon icofont-live-support"></i>
                  <a href="#" class="more">more</a>
                </div>
              </div>
            </div>
            <!-- Service Block End -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Services Section End -->


  <!-- Funfact Section Start -->
  <div class="section section-padding-bottom bg-light">
    <div class="container">
      <div class="funfact-inner-container">
        <div class="row mb-n8">

          <div class="col-sm-4 col-6 mb-8">
            <!-- Single Funfact Start -->
            <div class="single-funfact ">
              <h1>8800</h1>
              <h6 class="title">Partner <br> worldwide</h6>
            </div>
            <!-- Single Funfact End -->
          </div>

          <div class="col-sm-4 col-6 mb-8">
            <!-- Single Funfact Start -->
            <div class="single-funfact ">
              <h1>1250</h1>
              <h4 class="title">employees and <br> staffs</h4>
            </div>
            <!-- Single Funfact End -->
          </div>

          <div class="col-sm-4 col-6 mb-8">
            <!-- Single Funfact Start -->
            <div class="single-funfact ">
              <h1>980</h1>
              <h5 class="title">project completed <br> on 60 countries</h5>
            </div>
            <!-- Single Funfact End -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Funfact Section END-->






  <!-- Team Image Section Start -->

  <div class="section team-section-padding bg-light">
    <div class="container">
      <div class="row">
        <div class="col-12">

          <!-- Section Title Start -->
          <div class="section-title ">
            <h2 class="title">Our Team</h2>
          </div>
          <!-- Section Title End -->

        </div>
      </div>
      <div class="row mb-n6">
        <div class="col-lg-4 col-md-6 col-12 mb-6 ">
          <!-- Team Block Start -->
          <div class="team-block">
            <div class="inner-box">

              <!-- Team Image Start  -->
              <div class="image">
                <a href="#"><img src="images/daniel-image.png" class="rounded-circle" id="ceo-image" alt="Team"></a>
                <ul class="social-icons">
                  <li><a href="#"><i class='fab fa-twitter'></i></a></li>
                  <li><a href="#"><i class='fab fa-facebook'></i></a></li>
                  <li><a href="#"><i class='fab fa-instagram'></i></a></li>
                  <li><a href="#"><i class='fab fa-linkedin'></i></a></li>
                </ul>
              </div>
              <!-- Team Image End -->

              <!-- Team Content Start  -->
              <div class="team-content">
                <h4 class="title"><a href="#">Daniel Lamaton</a></h4>
                <h5 class="subtitle">CEO Founder</h5>
              </div>
              <!-- Team Content End -->

            </div>
          </div>
          <!-- Team Block End -->
        </div>
        <div class="col-lg-4 col-md-6 col-12 mb-6 ">

          <!-- Team Block Start  -->
          <div class="team-block">
            <div class="inner-box">

              <!-- Team Image Start  -->
              <div class="image">
                <a href="#"><img class="rounded-circle" src="images/ranmie-image.png" alt="Team"></a>

                <ul class="social-icons">
                  <li><a href="#"><i class='fab fa-twitter'></i></a></li>
                  <li><a href="#"><i class='fab fa-facebook'></i></a></li>
                  <li><a href="#"><i class='fab fa-instagram'></i></a></li>
                  <li><a href="#"><i class='fab fa-linkedin'></i></a></li>
                </ul>
              </div>
              <!-- Team Image End -->

              <!-- Team Content Start  -->
              <div class="team-content">
                <h4 class="title"><a href="#">Ranmie Montecalvo</a></h4>
                <h5 class="subtitle">Architect &amp; Project Management</h5>
              </div>
              <!-- Team Content End -->

            </div>
          </div>
          <!-- Team Block End -->

        </div>
        <div class="col-lg-4 col-md-6 col-12 mb-6 ">

          <!-- Team Block Start  -->
          <div class="team-block">
            <div class="inner-box">
              <!-- Team Image Start -->
              <div class="image">
                <a href="#"><img class="rounded-circle" src="images/erik-image.png" alt="Team"></a>
                <ul class="social-icons">
                  <li><a href="#"><i class='fab fa-twitter'></i></a></li>
                  <li><a href="#"><i class='fab fa-facebook'></i></a></li>
                  <li><a href="#"><i class='fab fa-instagram'></i></a></li>
                  <li><a href="#"><i class='fab fa-linkedin'></i></a></li>
                </ul>
              </div>
              <!-- Team Image End -->

              <!-- Team Content Start  -->
              <div class="team-content">
                <h4 class="title"><a href="#">Erik Agabon</a></h4>
                <h5 class="subtitle">Executive &amp; Marketing Management</h5>
              </div>
              <!-- Team Content End -->

            </div>
          </div>
          <!-- Team Block End -->

        </div>
      </div>
    </div>
  </div>
  <!-- Team Image Section End -->



  <?php require 'includes/footer.php'; ?>

</body>

</html>