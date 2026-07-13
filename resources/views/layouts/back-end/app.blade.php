<!DOCTYPE html>
<html lang="en">
    <head>
		@include('layouts.back-end.header')
		@yield('css')
	</head>
    <body>
        <div class="loader"></div>
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
            	@include('layouts.back-end.navigation_bar')   
            	@include('layouts.back-end.sidebar')            
                <!-- Main Content -->
                <div class="main-content">
                    @include('layouts.back-end.flash')  

                        @yield('content')        
                                    
                    @include('layouts.back-end.settings_bar')
                </div>
               @include('layouts.back-end.footer')
            </div>
        </div>
        @include('layouts.back-end.scripts')
        @yield('javascript')
    </body>
</html>