
<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta charset="utf-8">
	<title>Ollya</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- site favicon -->
	<link rel="icon" type="image/png" href="assets/images/favicon.png">
	<!-- Place favicon.ico in the root directory -->

	<!-- All stylesheet and icons css  -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/animate.css">
	<link rel="stylesheet" href="assets/css/all.min.css">
	<link rel="stylesheet" href="assets/css/swiper.min.css">
	<link rel="stylesheet" href="assets/css/lightcase.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <!-- All stylesheet and icons bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
	<!-- preloader start here -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
	<!-- preloader ending here -->


	<!-- scrollToTop start here -->
    <a href="{{route('frontend.index')}}" class="scrollToTop"><i class="fa-solid fa-angle-up"></i></a>
    <!-- scrollToTop ending here -->


    <!-- ================> header section start here <================== -->
    <header class="header" id="navbar">
		<div class="header__bottom">
			<div class="container">
				<nav class="navbar navbar-expand-lg">
					<a class="navbar-brand" href="index.html"><img src="assets/images/logo/logo.png" alt="logo"></a>
					<button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
						data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
						aria-label="Toggle navigation">
						<span class="navbar-toggler--icon"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
						<div class="navbar-nav mainmenu">
							<ul>
								<li>
									<a href="{{route('frontend.index')}}">Home</a>
								</li>
                                <li><a href="{{route('frontend.about')}}">About</a></li>
								<li>
									<a href="{{route('frontend.membership')}}">Membership</a>
								</li>
								<li>
									<a href="{{route('frontend.community')}}">Community</a>
								</li>
								<li><a href="{{route('frontend.contact')}}">contact</a></li>
							</ul>
						</div>
						<div class="header__more">
                            <button class="default-btn dropdown-toggle" type="button" id="moreoption" data-bs-toggle="dropdown" aria-expanded="false">My Account</button>
                            <ul class="dropdown-menu" aria-labelledby="moreoption">
                                <li><a class="dropdown-item" href="{{route('login')}}">Log In</a></li>
                                <li><a class="dropdown-item" href="{{route('register')}}">Sign Up</a></li>
                            </ul>
						</div>
					</div>
				</nav>
			</div>
		</div>
    </header>
    <!-- ================> header section end here <================== -->


@yield('content')

<!-- ================> Footer section start here <================== -->
	<footer class="footer overflow-hidden">
		<div class="footer__top bg_img" style="background-image: url(assets/images/footer/bg-3.jpg)">
			<div class="footer__newsletter wow fadeInUp" data-wow-duration="1.5s">
				<div class="container">
					<div class="row g-4 justify-content-center">
						<div class="col-lg-6 col-12">
							<div class="footer__newsletter--area">
								<div class="footer__newsletter--title">
									<h4>Newsletter Sign up</h4>
								</div>
								<div class="footer__newsletter--form">
									<form action="index.html#">
										<input type="email" placeholder="Your email address">
										<button type="submit" class="default-btn"><span>Subscribe</span></button>
									</form>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-12">
							<div class="footer__newsletter--area justify-content-xxl-end">
								<div class="footer__newsletter--title me-xl-4">
									<h4>Join Community</h4>
								</div>
								<div class="footer__newsletter--social">
									<ul>
										<li><a href="index.html#"><i class="fa-brands fa-twitter"></i></a></li>
										<li><a href="index.html#"><i class="fa-brands fa-twitch"></i></a></li>
										<li><a href="index.html#"><i class="fa-brands fa-instagram"></i></a></li>
										<li><a href="index.html#"><i class="fa-brands fa-dribbble"></i></a></li>
										<li><a href="index.html#"><i class="fa-brands fa-facebook-messenger"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer__toparea padding-top padding-bottom wow fadeInUp" data-wow-duration="1.5s">
				<div class="container">
					<div class="row g-5 g-lg-0">
						<div class="col-lg-3 col-sm-6 col-12">
							<div class="footer__item">
								<div class="footer__inner">
									<div class="footer__content">
										<div class="footer__content--title">
											<h4>Our Information</h4>
										</div>
										<div class="footer__content--desc">
											<ul>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> About Us</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Contact Us</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Customer Reviews</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Success Stories</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Business License</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-sm-6 col-12">
							<div class="footer__item">
								<div class="footer__inner">
									<div class="footer__content">
										<div class="footer__content--title">
											<h4>My Account</h4>
										</div>
										<div class="footer__content--desc">
											<ul>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Manage Account</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Safety Tips</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Account Varification</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Safety and Security</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Membership Level</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-sm-6 col-12">
							<div class="footer__item">
								<div class="footer__inner">
									<div class="footer__content">
										<div class="footer__content--title">
											<h4>Help Center</h4>
										</div>
										<div class="footer__content--desc">
											<ul>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Help center</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> FAQ</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Quick Start Guide</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Tutorials</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Associate Blog</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-sm-6 col-12">
							<div class="footer__item">
								<div class="footer__inner">
									<div class="footer__content">
										<div class="footer__content--title">
											<h4>Community</h4>
										</div>
										<div class="footer__content--desc">
											<ul>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Privacy policy</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> End User Agreements</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Refund Policy</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Cookie policy</a></li>
												<li><a href="index.html#"><i class="fa-solid fa-angle-right"></i> Report abuse</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="footer__bottom wow fadeInUp" data-wow-duration="1.5s">
			<div class="container">
				<div class="footer__content text-center">
					<p class="mb-0">All Rights Reserved &copy; <a href="{{route('frontend.index')}}">Ollya</a> || Design By: CodexCoder</p>
				</div>
			</div>
		</div>
	</footer>
    <!-- ================> Footer section end here <================== -->

	
	<!-- All Needed JS -->
	<script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
	<script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>
	<script src="assets/js/isotope.pkgd.min.js"></script>
	<script src="assets/js/swiper.min.js"></script>
	<!-- <script src="assets/js/all.min.js"></script> -->
	<script src="assets/js/wow.js"></script>
	<script src="assets/js/lightcase.js"></script>
	<script src="assets/js/jquery.countdown.min.js"></script>
	<script src="assets/js/waypoints.min.js"></script>
	<script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
	<script src="assets/js/plugins.js"></script>
	<script src="assets/js/main.js"></script>


	<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
	<script>
		window.ga = function () {
			ga.q.push(arguments)
		};
		ga.q = [];
		ga.l = +new Date;
		ga('create', 'UA-XXXXX-Y', 'auto');
		ga('set', 'anonymizeIp', true);
		ga('set', 'transport', 'beacon');
		ga('send', 'pageview')
	</script>
	<script src="https://www.google-analytics.com/analytics.js" async></script>
</body>
</html>
