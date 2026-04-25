<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <title>Email Verification OTP - Ollya</title>
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
                        <a href="{{ route('login') }}" class="backto-home">
                            <i class="fas fa-chevron-left"></i> Back to Login
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
                            <h2 class="title">Verify Your Email</h2>
                            <p>Enter the 6-digit OTP sent to your email.</p>
                        </div>

                        <div class="main-content inloginp">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif

                            <form method="POST" action="{{ route('verification.otp.verify') }}">
                                @csrf

                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="my-form-control"
                                        value="{{ old('email', $email ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>OTP Code</label>
                                    <input type="text" name="otp" class="my-form-control" maxlength="6"
                                        placeholder="Enter 6-digit OTP" value="{{ old('otp') }}" required>
                                </div>

                                <div class="text-center mb-3">
                                    <button type="submit" class="default-btn">
                                        <span>Verify OTP</span>
                                    </button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('verification.otp.resend') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
                                <div class="text-center">
                                    <button type="submit" class="default-btn reverse">
                                        <span>Resend OTP</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
