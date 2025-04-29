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



	<!-- ICON LINKS -->
	<link rel='stylesheet' href='https://www.troolife.com/wp-content/themes/picostrap5-child-base/css-output/bundle.css?ver=956' media='all'>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


	<!-- CSS LINKS -->
	<link rel="stylesheet" href="css/header-style.css">
	<link rel="stylesheet" href="css/footer-style.css">

	<link rel='stylesheet' href='css/home-style/bundle.css' media='all'>
	<link rel="stylesheet" href="css/home-style/section1-style.css">
	<link rel="stylesheet" href="css/home-style/section7-style.css">
	<link rel="stylesheet" href="css/home-style/home-style.css">
	<link rel="stylesheet" href="css/home-style/homebanner-style.css">
	<link rel="stylesheet" href="css/global-style.css">


	<!-- <style>
        /* Basic styling for the profile pic in the navbar */
        .navbar-profile-pic {
            height: 35px; width: 35px; border-radius: 50%; object-fit: cover;
            margin-right: 8px; vertical-align: middle;
        }
    </style> -->

</head>



<body class="">

	<?php require 'includes/header.php'; ?>



	<main id='theme-main'>

		<!-- HOME BANNER -->

		<section class="section1">
			<div class="position-relative overflow-hidden">
				<div class="d-flex min-vh-100 justify-content-center text-center">
					<video style="z-index:1;object-fit: cover; object-position: 50% 50%;"
						class="position-absolute w-100 min-vh-100" autoplay="" preload="" muted="" loop="" playsinline="">
						<source src="video/vid.mp4" type="video/mp4">
					</video>
					<div style="z-index:2" class="align-self-center text-center text-light col-md-8">
						<div class="lc-block container d-flex align-items-center justify-content-center flex-column">

							<h1 id="homebanner" class="container">
								<img style="margin-top: -8px;" src="images/icons/logo2.svg" alt="logo"> is dedicated to enhancing the
								quality and longevity of lives.
							</h1>


							<a href="#" class="btn btn-primary rounded-pill px-4 py-2 mt-5 d-inline-flex align-items-center gap-2">
								Learn more
								<i class="material-icons d-flex justify-content-center align-items-center">keyboard_arrow_right</i>
							</a>
						</div>
					</div>
				</div>
			</div>
			</div>
		</section>




		<section class="section2">
			<div class="container-fluid px-4">
				<div class="row">
					<div class="col-md-12">
						<div class="lc-block">
							<div>
								<h2 class="fw-semibold">Our Mission</h2>
								<div class="divider-teal"></div>
								<p> Our TrooLife mission is to empower Members to make a true difference in their own personal health,
									wellness and longevity. For them to enhance their own quality of life, and then to inspire a better
									quality of life for their family, friends and associates. </p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>






		<!-- HOME SECTION -->
		<div class="home-section">
			<section class="section3 section3-5">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-lg-6 d-flex justify-content-end order-2 order-lg-1 ps-lg-5 home-text">
							<div class="lc-block">
								<div>
									<h3>The things you consistently do</h3>
									<p class="line-breaks" style="color: #fff;"> to enhance your mind, your body, your relationships and
										your finances – will enhance your quality of life - and the quality of your extended life.

										Also be aware that the things that you do not bother to do for your mind, body, relationships and
										finances – will reduce your quality of life. </p>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-lg-6 home-image container-fluid d-flex justify-content-end order-1 order-lg-2">
							<div class="home-right-image">
								<img decoding="async" src="images/img1.png">
							</div>
						</div>
					</div>
				</div>
			</section>


			<section class="section4 section4-6">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-lg-6 home-image container-fluid d-flex justify-content-start ">
							<div class="home-left-image">
								<img decoding="async" src="images/img2.png">
							</div>
						</div>
						<div class="col-lg-6 d-flex justify-content-start pe-lg-5 home-text">
							<div class="lc-block">
								<h3 class="">Health experts say</h3>
								<p> we should have five servings or fresh fruits and vegetables every day, however this is not practical
									or possible for most active adults. This is why these same experts recommend supplementing your diet
									with high-quality vitamins, minerals, antioxidants and amino acids to help provide optimal health,
									vitality and mental clarity. </p>
							</div>
						</div>
					</div>
				</div>
			</section>


			<section class="section5 section3-5">
				<div class="container-fluid ">
					<div class="row align-items-center">
						<!-- Text Column -->
						<div class="col-lg-6 d-flex justify-content-end order-2 order-lg-1 ps-lg-5 home-text">
							<div class="lc-block">
								<h3>Through our LifeLine program</h3>
								<p class="mb-0">TrooLife will act as your personal wellness coach, encouraging you to eat well, to
									maintain a healthy activity level and to help you de-stress from your work and family pressures.
									LifeLine will also advise you on how to optimize your important personal relationships with your
									family, friends and associates, to enhance your own life, and theirs.</p>
							</div>
						</div>
						<div class="col-md-12 col-lg-6 home-image container-fluid d-flex justify-content-end order-1 order-lg-2">
							<div class="home-right-image">
								<img decoding="async" src="images/img3.png">
							</div>
						</div>
					</div>
				</div>
			</section>

			<section class="section6 section4-6">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-lg-6 home-image container-fluid d-flex justify-content-start ">
							<div class="home-left-image">
								<img decoding="async" src="images/img4.png">
							</div>
						</div>
						<div class="col-lg-6 d-flex justify-content-start pe-lg-5 home-text">
							<div class="lc-block">
								<h3 class="">TrooLife is also committed</h3>
								<p> to providing you excellent product quality and value, with the means to receive your nutritional
									supplements and wellness coaching for free, from a few simple referrals. Please take a few minutes to
									find out how. </p>
							</div>
						</div>
					</div>
				</div>

				<div class="d-flex justify-content-center mt-5 pt-lg-3" id="home-button">
					<button type="button" class="btn btn-primary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
						Get Started
						<i class="material-icons d-flex justify-content-center align-items-center">keyboard_arrow_right</i>
					</button>
				</div>

			</section>

		</div>



		<!-- SECTION - 7 ------------------- -->

		<section class="section7">
			<div class="container">
				<div class="row">
					<div class="col-12 text-center sectionheader">
						<h2>Realize Your True Life Potential</h2>
						<div class="divider-ochre mx-auto"></div>
					</div>
				</div>

				<!-- First Icon -->
				<div class="row">
					<div class="col-md-4 mb-4 mb-md-0 icon-container">
						<div class="inner-container text-center ">
							<img src="images/icons/wellness-coaching.svg" alt="icon1" class="img-fluid mb-3">
							<h3 class="potential-title container">LifeLine Wellness Coaching</h3>
							<a href="#" class="btn btn-primary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
								Learn more
								<i class="material-icons d-flex justify-content-center align-items-center">keyboard_arrow_right</i>
							</a>
						</div>
					</div>

					<!-- Second Icon -->
					<div class="col-md-4 mb-4 mb-md-0 icon-container">
						<div class="inner-container text-center">
							<img src="images/icons/nutritional-health.svg" alt="icon2" class="img-fluid mb-3">
							<h3 class="potential-title mb-md-5 pb-md-4">Nutritional Health</h3>
							<a href="#" class="btn btn-primary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
								Learn more
								<i class="material-icons d-flex justify-content-center align-items-center">keyboard_arrow_right</i>
							</a>
						</div>
					</div>

					<!-- Third Icon -->
					<div class="col-md-4 icon-container">
						<div class="inner-container text-center">
							<img src="images/icons/referral-benefits.svg" alt="icon3" class="img-fluid mb-3">
							<h3 class="potential-title mb-md-5 pb-md-4">Referral Benefits</h3>
							<a href="#" class="btn btn-primary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2">
								Learn more
								<i class="material-icons d-flex justify-content-center align-items-center">keyboard_arrow_right</i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		</div>

	</main>



	<?php require 'includes/footer.php'; ?>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
	</script>
</body>

</html>