<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Main Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('images/man.png') }}" class="user-image img-circle elevation-2"
                            alt="User Image">
                        <span class="d-none d-md-inline">
                            @if (Auth::check() && Auth::user()->nom)
                                {{ Auth::user()->nom }}
                            @endif
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-info">
                            <img src="{{ asset('images/man.png') }}" class="user-image img-circle elevation-2"
                                alt="User Image">
                            <p>
                            @if (Auth::check() && Auth::user()->nom)
                            {{ Auth::user()->name }}
                            <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                            @endif
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                se déconnecter
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Droits d'auteur © 2024-2025 <a href="#" class="text-info">SoliLMS</a>.</strong> Tous droits
            réservés.
        </footer>
    </div>

    <script>
        window.notifications = window.notifications || [];
        @if (session('success'))
    
        window.notifications.push({
            type : "showSuccess",
            message: "{{ session('success') }}"
        });   
        @endif
        @if (session('error'))
        window.notifications.push({
            type : "showError",
            message: "{{ session('error') }}"
        });   
        @endif
        @if (session('info'))
        window.notifications.push({
            type : "showInfo",
            message: "{{ session('info') }}"
        });   
        @endif
    </script>
    @stack('scripts')
</body>
</html>
