<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">

    <!-- Mobile Toggle -->
    <button class="navbar-toggler border-0  me-0" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarCollapse">
      <svg width="32" height="32" viewBox="0 0 21 17" fill="none">
        <path d="M0 3V0H21V3H0Z" fill="#C4C4C4" />
        <path d="M0 10V7H21V10H0Z" fill="#C4C4C4" />
        <path d="M0 17V14H21V17H0Z" fill="#C4C4C4" />
      </svg>
    </button>



    <!-- Brand/Logo -->
    <a class="navbar-brand me-auto" href="mainpage.php">
      <img src="images/icons/logo.svg" alt="Brand Logo" width="158" height="46">
    </a>


    <!-- Right Navigation (Fixed Position on Desktop) -->
    <div class="d-flex align-items-center right-nav order-lg-last order-0 righ-nav">
      <a href="#" id="my-cart" class="pb-md-2" target="_blank" rel="noopener">
        <div id="cart-badge">
          <img src="images/icons/cart.svg" alt="Cart">
        </div>
      </a>

      <a href="/webdevfinal/user-profile.php" id="my-office"  rel="noopener">
        <img src="images/icons/person.svg" alt="">
        Profile
      </a>



      
        <a href="/webdevfinal/assets/php/logout.php" id="english-icon">
          <i class="bi bi-escape logout-icon"></i>
          Sign Out
        </a>
      

    </div>

    <!-- Main Navigation (Collapsible) -->
    <div class="collapse navbar-collapse order-5" id="navbarCollapse">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0" id="main-Nav">
        <li class="nav-item"><a class="nav-link active" href="mainpage.php"><span class="white-divider pb-1">Mission</span></a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Lifeline</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Nutrition</a></li>
        <li class="nav-item"><a class="nav-link text-truncate" href="#">Free By Referral</a></li>
        <li class="nav-item"><a class="nav-link" href="About.php" style="color: #F6DBA0 !important;" id="about-link">About Us</a></li>
        <li class="nav-item"><a class="nav-link text-truncate" href="#">Public Relations</a></li>
        <li class="nav-item"></li><a href="/webdevfinal/user-profile.php" id="my-office" class="office-collapse text-center m-auto"
          rel="noopener">
          Profile
        </a></li>
      </ul>
    </div>




  </div>
</nav>



<!--- Profile section- -->

<section class="section0 mt-4 pt-5 pt-lg-3 px-sm-5 px-3">
  <div class="profile-section d-flex justify-content-between align-items-center px-sm-3 mt-lg-4">
    <div class="d-flex align-items-center">
      <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Profile" class="navbar-profile-pic">
      <span class="profile-name manrope ps-3"><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
      
     

    </div>

    <div class="d-flex align-items-center gap-4">
      <a href="#"><img src="images/phone2.png" alt="phone" width="25"></a>
      <a href="#"><img src="images/phone.png" alt="message" width="21"></a>
    </div>
  </div>
</section>