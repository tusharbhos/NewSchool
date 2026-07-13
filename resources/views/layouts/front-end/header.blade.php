<header class="header">
    <div class="header-top">
        <div class="container">

            <div class="header-left">
                <a href="#" style="color:#115782;font-weight: 500;">The New School</a>
            </div>

            <div class="header-right p-4">
                <ul class="top-menu">
                    <li>
                        <a href="#">Settings</a>
                        <ul>
                            <li><a href="{{ route('change.password') }}"><i class="icon-logout"></i> Change Password
                                </a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                        class="icon-logout"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                </ul>
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-top -->

    <div class="header-middle sticky-header">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('front-end/images/logo1.jpg') }}" alt="The New School"
                        style="height:40px; width: auto; ">
                </a>
                @php
                $route = request()->route()->getName();
                $chapterMonths = collect(range(0, 5))->map(fn ($offset) => now()->startOfMonth()->subMonths($offset));
                $chapterWeekNames = ['First Week', 'Second Week', 'Third Week', 'Fourth Week', 'Fifth Week'];
                @endphp
                <nav class="main-nav">
                    <ul class="menu sf-js-enabled sf-arrows">
                        <li
                            class="megamenu-container  @if($route == 'student.dashboard' || $route == 'home') active @endif">
                            <a href="{{ route('home') }}">Home</a>
                        </li>

                        <li class="@if($route === 'chapters.index') active @endif">
                            <a href="{{ route('chapters.index') }}" class="sf-with-ul">Chapters</a>
                            <ul>
                                <li><a href="{{ route('chapters.index') }}">Latest Release Chapter</a></li>
                                @foreach($chapterMonths as $month)
                                <li><a href="{{ route('chapters.index', ['month'=>$month->format('m'),'year'=>$month->format('Y'),'week'=>'all']) }}"
                                        class="sf-with-ul">{{ $month->format('F Y') }}</a>
                                    <ul>
                                        @foreach($chapterWeekNames as $weekIndex => $weekName)
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
                    </ul><!-- End .menu -->
                </nav><!-- End .main-nav -->
            </div><!-- End .header-left -->

            <div class="header-right">

                <div class="dropdown cart-dropdown" style="color:#115782; text-align: center; font-weight: 400;">
                    Welcome, {{ Auth::user()->name }}
                    @if(session('classes')) <br>({{ session('classes') }})@endif
                </div><!-- End .cart-dropdown -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-middle -->
</header><!-- End .header -->
