<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>School - LMS</title>
    <meta name="keywords" content="School, LMS">
    <meta name="description" content="School - Content Management System">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('front-end/images/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('front-end/images/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('front-end/images/icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('front-end/images/icons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('front-end/images/icons/safari-pinned-tab.svg') }}" color="#2e7cc6">
    <link rel="shortcut icon" href="{{ asset('front-end/images/icons/favicon.ico') }}">
    <meta name="apple-mobile-web-app-title" content="School">
    <meta name="application-name" content="School - LMS">
    <meta name="msapplication-TileColor" content="#2e7cc6">
    <meta name="msapplication-config" content="{{ asset('front-end/images/icons/browserconfig.xml') }}">
    <meta name="theme-color" content="#2e7cc6">

    <script src="{{ asset('front-end/js/angular.js') }}"></script>
    <script src="{{ asset('front-end/js/angular-sanitize.js') }}"></script>
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ asset('front-end/css/bootstrap.min.css') }}">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('front-end/css/style.css') }}">

    <style type="text/css">
    .alert p {
        color: white !important;
    }

    .center-block {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    @media screen and (min-width: 768px) .form-box {
        padding: 3.7rem 6rem 3.7rem !important;
    }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <main class="main">
            <div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17"
                style="background-image: url('{{ asset("front-end/images/successful-schoolgirl.webp") }}'); object-fit: contain !important; height: 100vh;">
                <div class="container">

                    <div class="form-box">
                        <img class="center-block" src="{{ asset('front-end/images/logo1.jpg')}}"
                            style="max-height: 100px; justify-content: center;">
                        <div class="form-tab">

                            <ul class="nav nav-pills nav-fill" role="tablist">
                                <li class="nav-item">

                                    <a class="nav-link active" id="signin-tab-2" data-toggle="tab" href="#signin-2"
                                        role="tab" aria-controls="signin-2" aria-selected="false">Sign In</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                                @endif
                                {{ Session::forget('error') }}

                                <div class="tab-pane fade show active" id="signin-2" role="tabpanel"
                                    aria-labelledby="signin-tab-2">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div ng-bind-html="message" class="form-group"></div>
                                        <div class="form-group">
                                            <label for="singin-email-2">Email address *</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="singin-password-2">Password *</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div><!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit"
                                                class="btn btn-primary btn-rounded"><span>Login</span><i
                                                    class="icon-long-arrow-right"></i></button>
                                        </div>
                                    </form>
                                </div><!-- .End .tab-pane -->
                            </div><!-- End .tab-content -->
                        </div><!-- End .form-tab -->
                    </div><!-- End .form-box -->
                </div><!-- End .container -->
            </div><!-- End .login-page section-bg -->
        </main><!-- End .main -->
    </div>
    <!-- Plugins JS File -->
    <script src="{{ asset('front-end/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front-end/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('front-end/js/superfish.min.js') }}"></script>
    <script src="{{ asset('front-end/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front-end/js/main.js') }}"></script>
</body>

</html>