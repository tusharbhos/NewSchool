<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg
                   collapse-btn"> <i data-feather="align-justify"></i></a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a>
            </li>           
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
        	<a href="#" data-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="{{ asset('back-end/img/user.png') }}" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">Hello, {{ Auth::user()->name }}</div>
               
               <a class="dropdown-item has-icon text-danger"  href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-sign-out-alt"></i>Logout</a>

               <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>