@extends('layouts.app')
@section('content')



    <!-- ================> Membership start here <================== -->
    <div class="membership padding-top padding-bottom">
        <div class="container">
            <div class="section__header style-2 text-center">
				<h2>Membership Levels</h2>
				<p>Our dating platform is like a breath of fresh air. Clean and trendy design with ready to use features we are sure you will love.</p>
			</div>
            <div class="section__wrapper">
				<div class="row g-4 justify-content-center row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1">
					<div class="col">
						<div class="membership__item">
                            <div class="membership__inner">
                                <div class="membership__head">
                                    <h4>7 Day Free Trial</h4>
                                    <p>$15.00 Now And Then $30.00 Per Month.</p>
                                </div>
                                <div class="membership__body">
                                    <h4>FREE</h4>
                                    <ul>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Directory</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Profile</span></li>
                                        <li><i class="fa-solid fa-circle-xmark"></i> <span>Send Private Messages</span></li>
                                        <li><i class="fa-solid fa-circle-xmark"></i> <span>Add Media To Your Profile</span></li>
                                    </ul>
                                </div>
                                <div class="membership__footer">
                                    <a href="login.html" class="default-btn reverse"><span>Select Plan</span></a>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="col">
						<div class="membership__item">
                            <div class="membership__inner">
                                <div class="membership__head">
                                    <h4>Bronze</h4>
                                    <p>$15.00 Now And Then $30.00 Per Month.</p>
                                </div>
                                <div class="membership__body">
                                    <h4>$15.00</h4>
                                    <ul>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Directory</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Profile</span></li>
                                        <li><i class="fa-solid fa-circle-xmark"></i> <span>Send Private Messages</span></li>
                                        <li><i class="fa-solid fa-circle-xmark"></i> <span>Add Media To Your Profile</span></li>
                                    </ul>
                                </div>
                                <div class="membership__footer">
                                    <a href="login.html" class="default-btn reverse"><span>Select Plan</span></a>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="col">
						<div class="membership__item">
                            <div class="membership__inner">
                                <div class="membership__head">
                                    <h4>Silver</h4>
                                    <p>$15.00 Now And Then $30.00 Per Month.</p>
                                </div>
                                <div class="membership__body">
                                    <h4>$20.00</h4>
                                    <ul>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Directory</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Profile</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>Send Private Messages</span></li>
                                        <li><i class="fa-solid fa-circle-xmark"></i> <span>Add Media To Your Profile</span></li>
                                    </ul>
                                </div>
                                <div class="membership__footer">
                                    <a href="login.html" class="default-btn reverse"><span>Select Plan</span></a>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="col">
						<div class="membership__item">
                            <div class="membership__inner">
                                <div class="membership__head">
                                    <h4>Gold</h4>
                                    <p>$15.00 Now And Then $30.00 Per Month.</p>
                                </div>
                                <div class="membership__body">
                                    <h4>$30.00</h4>
                                    <ul>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Directory</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>View Members Profile</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>Send Private Messages</span></li>
                                        <li><i class="fa-solid fa-circle-check"></i> <span>Add Media To Your Profile</span></li>
                                    </ul>
                                </div>
                                <div class="membership__footer">
                                    <a href="login.html" class="default-btn reverse"><span>Select Plan</span></a>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <!-- ================> Membership end here <================== -->




   
	
	

	<!-- All Needed JS -->
	<script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
	<script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>
	<script src="assets/js/isotope.pkgd.min.js"></script>
	<script src="assets/js/swiper.min.js"></script>
	<!-- <script src="assets/js/all.min.js"></script> -->
	<script src="assets/js/wow.js"></script>
	<script src="assets/js/counterup.js"></script>
	<script src="assets/js/jquery.countdown.min.js"></script>
	<script src="assets/js/lightcase.js"></script>
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
	
@endsection