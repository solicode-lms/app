<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Login')</title>

    <!-- Styles & Scripts -->
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <!-- Font Awesome (optionnel, si utilisé) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        @yield('content')
    </div>
</body>
</html>
