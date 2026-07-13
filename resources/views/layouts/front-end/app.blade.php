<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>The New School</title>
    <meta name="keywords" content="educational, student, college, syllabus, courses, learning platform, e-learning">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Educational Portal - Educationa portal for college student">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('front-end/images/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('front-end/images/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('front-end/images/icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('front-end/images/icons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('front-end/images/icons/safari-pinned-tab.svg') }}" color="#666666">
    <link rel="shortcut icon" href="{{ asset('front-end/images/icons/favicon.ico') }}">
    <meta name="apple-mobile-web-app-title" content="Educational Portal">
    <meta name="application-name" content="Educational Portal">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="{{ asset('front-end/images/icons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{ asset('front-end/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front-end/css/plugins/magnific-popup/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('front-end/css/style.css') }}">
    @yield('css')
</head>

<body>
    <div class="page-wrapper">
        @include('layouts.front-end.header')
        @yield('content')
        {{-- @include('layouts.front-end.footer') --}}
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-close"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    <li class="active">
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    @php
                    $mobileChapterMonths = collect(range(0, 5))->map(fn ($offset) => now()->startOfMonth()->subMonths($offset));
                    $mobileChapterWeekNames = ['First Week', 'Second Week', 'Third Week', 'Fourth Week', 'Fifth Week'];
                    @endphp
                    <li>
                        <a href="{{ route('chapters.index') }}">Chapters</a>
                        <ul>
                            <li><a href="{{ route('chapters.index') }}">Latest Release Chapter</a></li>
                            @foreach($mobileChapterMonths as $month)
                            <li><a
                                    href="{{ route('chapters.index', ['month'=>$month->format('m'),'year'=>$month->format('Y'),'week'=>'all']) }}">{{ $month->format('F Y') }}</a>
                                <ul>
                                    @foreach($mobileChapterWeekNames as $weekIndex => $weekName)
                                    <li><a
                                            href="{{ route('chapters.index', ['month'=>$month->format('m'),'year'=>$month->format('Y'),'week'=>$weekIndex]) }}">{{ $weekName }}</a>
                                    </li>
                                    @endforeach
                                    <li><a
                                            href="{{ route('chapters.index', ['month'=>$month->format('m'),'year'=>$month->format('Y'),'week'=>'all']) }}">All
                                            Week</a></li>
                                </ul>
                            </li>
                            @endforeach
                            <li><a href="{{ route('chapters.index', ['older'=>1]) }}">Older Release Chapter</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                </ul>
            </nav><!-- End .mobile-nav -->

        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->

    <!-- Plugins JS File -->
    <script src="{{ asset('front-end/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front-end/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('front-end/js/superfish.min.js') }}"></script>
    <script src="{{ asset('front-end/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Main JS File -->
    <script src="{{ asset('front-end/js/main.js') }}"></script>
    @yield('javascript')
</body>

</html>
