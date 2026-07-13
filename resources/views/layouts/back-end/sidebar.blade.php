@php
$route = request()->route()->getName();
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}"> 
                <img alt="image" src="{{ asset('front-end/images/logo1.jpg') }}" class="header-logo" />
                <span class="logo-name">The New School</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            
            <li class="dropdown @if( in_array($route,['dashboard','chapters.report'])) active @endif">
                <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="home"></i><span>Dashboard</span></a>
            </li>

            <li class="dropdown @if( in_array($route,['admin.chapter.index', 'admin.chapter.create', 'admin.chapter.edit', 'admin.chapter.show'])) active @endif"><a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layers"></i><span>Chapters</span></a>
                <ul class="dropdown-menu">
                    <li class="@if($route == 'admin.chapter.create' ) active @endif"><a class="nav-link" href="{{ route('admin.chapter.create') }}">Create Chapter</a></li>
                    <li class="@if(in_array($route,['admin.chapter.index', 'admin.chapter.edit', 'admin.chapter.show'])) active @endif"><a class="nav-link" href="{{ route('admin.chapter.index') }}">List of Chapters</a></li>                   
                </ul>
            </li>

            <li class="dropdown @if( in_array($route,['admin.principal.index','admin.principal.create','admin.principal.edit','admin.principal.show'])) active @endif"><a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="user"></i><span>Principals</span></a>
                <ul class="dropdown-menu">
                    <li class="@if($route == 'admin.principal.create' ) active @endif"><a class="nav-link" href="{{ route('admin.principal.create') }}">Create Principal</a></li>
                    <li class="@if(in_array($route,['admin.principal.index','admin.principal.edit','admin.principal.show'])) active @endif"><a class="nav-link" href="{{ route('admin.principal.index') }}">List of Principals</a></li>
                    
                </ul>
            </li>

            <li class="dropdown @if( in_array($route,['admin.teacher.index','admin.teacher.create','admin.teacher.edit','admin.teacher.show'])) active @endif"><a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="user"></i><span>Teachers</span></a>
                <ul class="dropdown-menu">
                    <li class="@if($route == 'admin.teacher.create' ) active @endif"><a class="nav-link" href="{{ route('admin.teacher.create') }}">Create Teacher</a></li>
                    <li class="@if(in_array($route,['admin.teacher.index','admin.teacher.edit','admin.teacher.show'])) active @endif"><a class="nav-link" href="{{ route('admin.teacher.index') }}">List of Teachers</a></li>                    
                </ul>
            </li>

            <li class="dropdown @if( in_array($route,['admin.class.index','admin.class.create','admin.class.edit','admin.class.show'])) active @endif"><a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Classes</span></a>
                <ul class="dropdown-menu">
                    <li class="@if($route == 'admin.class.create' ) active @endif"><a class="nav-link" href="{{ route('admin.class.create') }}">Create Class</a></li>
                    <li class="@if(in_array($route, ['admin.class.index','admin.class.edit','admin.class.show'])) active @endif"><a class="nav-link" href="{{ route('admin.class.index') }}">List of Classes</a></li>
                </ul>
            </li>

        </ul>
    </aside>
</div>