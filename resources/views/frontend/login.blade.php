<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <title>Ollya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightcase.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

<section class="log-reg">
    <div class="top-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-7">
                    <div class="logo">
                        <a href="{{ route('frontend.index') }}">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-5">
                    <a href="{{ route('frontend.index') }}" class="backto-home">
                        <i class="fas fa-chevron-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="image image-log"></div>

            <div class="col-lg-7">
                <div class="log-reg-inner">
                    <div class="section-header inloginp">
                        <h2 class="title">Welcome to Ollya</h2>
                    </div>

                    <div class="main-content inloginp">

                        {{-- SUCCESS MESSAGE --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- ERROR MESSAGE --}}
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('frontend.login') }}">
                            @csrf

                            <div class="form-group">
                                <label>Your Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="my-form-control"
                                    placeholder="Enter Your Email"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="my-form-control"
                                    placeholder="Enter Your Password"
                                    required
                                >
                            </div>

                            <p class="f-pass">
                                Forgot your password?
                                <a href="#">recover password</a>
                            </p>

                            <div class="text-center">
                                <button type="submit" class="default-btn">
                                    <span>Sign In</span>
                                </button>
                            </div>

                            <div class="or">
                                <p>OR</p>
                            </div>

                            <div class="or-content">
                                <p>Sign up with your email</p>

                                <p class="or-signup">
                                    Don’t have an account?
                                    <a href="{{ route('frontend.register') }}">Sign up here</a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
