<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <title>Ollya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- site favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">

    <!-- All stylesheet and icons css  -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/lightcase.css">
    <link rel="stylesheet" href="assets/css/style.css">

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
    <a href="#" class="scrollToTop"><i class="fa-solid fa-angle-up"></i></a>

    <!-- signup section start here -->
    <section class="log-reg">
        <div class="top-menu-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-7">
                        <div class="logo">
                            <a href="{{ route('frontend.index') }}"><img src="assets/images/logo/logo.png" alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-5">
                        <a href="{{ route('frontend.index') }}" class="backto-home"><i class="fas fa-chevron-left"></i> Back to Home</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="image"></div>
                <div class="col-lg-7">
                    <div class="log-reg-inner">
                        <div class="section-header">
                            <h2 class="title">Welcome to Ollya</h2>
                            <p>Let's create your profile! Just fill in the fields below, and we’ll get a new account.</p>
                        </div>
                        <div class="main-content">

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <h4 class="content-title">Account Details</h4>
                                <div class="form-group">
                                    <label>Username*</label>
                                    <input type="text" name="username" class="my-form-control" placeholder="Enter Your Username" value="{{ old('username') }}">
                                </div>
                                <div class="form-group">
                                    <label>Email Address*</label>
                                    <input type="email" name="email" class="my-form-control" placeholder="Enter Your Email" value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label>Password*</label>
                                    <input type="password" name="password" class="my-form-control" placeholder="Enter Your Password">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password*</label>
                                    <input type="password" name="password_confirmation" class="my-form-control" placeholder="Confirm Your Password">
                                </div>

                                <h4 class="content-title mt-5">Profile Details</h4>
                                <div class="form-group">
                                    <label>Name*</label>
                                    <input type="text" name="name" class="my-form-control" placeholder="Enter Your Full Name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label>Birthday*</label>
                                    <input type="date" name="birthday" class="my-form-control" value="{{ old('birthday') }}">
                                </div>
                                <div class="form-group">
                                    <label>I am a*</label>
                                    <div class="banner__inputlist">
                                        <div class="s-input me-3">
                                            <input type="radio" name="gender" value="Man" id="males1" {{ old('gender') == 'Man' ? 'checked' : '' }}>
                                            <label for="males1">Man</label>
                                        </div>
                                        <div class="s-input">
                                            <input type="radio" name="gender" value="Woman" id="females1" {{ old('gender') == 'Woman' ? 'checked' : '' }}>
                                            <label for="females1">Woman</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Looking for a*</label>
                                    <div class="banner__inputlist">
                                        <div class="s-input me-3">
                                            <input type="radio" name="looking_for" value="Man" id="looking_male" {{ old('looking_for') == 'Man' ? 'checked' : '' }}>
                                            <label for="looking_male">Man</label>
                                        </div>
                                        <div class="s-input">
                                            <input type="radio" name="looking_for" value="Woman" id="looking_female" {{ old('looking_for') == 'Woman' ? 'checked' : '' }}>
                                            <label for="looking_female">Woman</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Marital status*</label>
                                    <div class="banner__inputlist">
                                        <select name="marital_status" class="my-form-control">
                                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>City*</label>
                                    <input type="text" name="city" class="my-form-control" placeholder="Enter Your City" value="{{ old('city') }}">
                                </div>

                                <button type="submit" class="default-btn reverse"><span>Create Your Profile</span></button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- signup section end here -->

    <!-- All Needed JS -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/swiper.min.js"></script>
    <script src="assets/js/wow.js"></script>
    <script src="assets/js/counterup.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <script src="assets/js/lightcase.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
