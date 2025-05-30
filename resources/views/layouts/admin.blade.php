<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('vendor/admin-lte/js/adminlte.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">

    <!-- iziModal CSS -->
    {{-- //TODO télécharger iziModal dans vendor --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/css/iziModal.min.css">
    <!-- iziModal JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/js/iziModal.min.js"></script>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
          @include('layouts.left-navbar')  
      
        
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
    

              <!-- Notifications Dropdown Menu -->
              @include('layouts.notifications')
            
              <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                  <i class="fas fa-expand-arrows-alt"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                role="button">
                  <i class="fas fa-sign-out-alt"></i>
                </a>
               
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
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
                <b>Version</b> 0.1.0
            </div>
            <strong>Droits d'auteur © 2024-2025 <a href="#" class="text-info">SoliLMS</a>.</strong> Tous droits
            réservés.
        </footer>
    </div>
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
    <script>
        window.notifications = window.notifications || [];
        @if (session('info'))
        window.notifications.push({
            type : "info",
            message: "{{ session('info') }}"
        });   
        @endif
        @if (session('success'))
        window.notifications.push({
            type : "success",
            message: "{{ session('success') }}"
        });   
        @endif
        @if (session('warning'))
        window.notifications.push({
            type : "warning",
            message: "{{ session('warning') }}"
        });   
        @endif
        @if (session('error'))
        window.notifications.push({
            type : "error",
            message: "{{ session('error') }}"
        });   
        @endif
    </script>
    <script>
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
    console.log("contextState");
    console.log(window.contextState);
    console.log("viewState");
    console.log(window.viewState);
    </script>
    @stack('scripts')
</body>
</html>