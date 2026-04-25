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
    <style>
        .password-wrap { position: relative; }
        .password-wrap .my-form-control { padding-right: 44px; }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            line-height: 1;
        }
        .password-toggle:hover { color: #111827; }
    </style>
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
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- ERROR MESSAGE --}}
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group">
                                    <label>Your Email Address</label>
                                    <input type="email" name="email" class="my-form-control"
                                        placeholder="Enter Your Email" required>
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="password-wrap">
                                        <input id="loginPassword" type="password" name="password" class="my-form-control"
                                            placeholder="Enter Your Password" required>
                                        <button type="button" class="password-toggle" data-target="loginPassword" aria-label="Show password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <p class="f-pass">
                                    Forgot your password?
                                    <a href="{{ route('password.request') }}">recover password</a>
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
                                    <a href="{{ url('/auth/google') }}" class="btn btn-primary">
                                        <i class="fa-brands fa-google me-2"></i>
                                        Sign Up with Google
                                    </a>


                                    <p class="or-signup">
                                        Don’t have an account?
                                        <a href="{{ route('register') }}">Sign up here</a>
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
    <script>
        document.querySelectorAll('.password-toggle').forEach((btn) => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                const icon = btn.querySelector('i');
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                if (icon) {
                    icon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
                }
                btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        });
    </script>

</body>

</html>
