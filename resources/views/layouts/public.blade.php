<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <!-- Scripts -->
    @vite(['resources/css/public.css', 'resources/js/public.js'])
</head>
<body class="bg-gray-100 text-gray-800">
  <!-- Header -->
  <header class="bg-white shadow">
    <div class="container mx-auto px-4 py-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-blue-600"> {{ config('app.name') }} </h1>
      <nav>
        <ul class="flex space-x-4">
          <li><a href="/admin/" class="text-gray-600 hover:text-blue-500">Mon espace de formation</a></li>
        </ul>
      </nav>
    </div>
  </header>

  @yield('content')


  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-4 text-center">
      <p class="text-sm">Droits d'auteur © 2024-2025 SoliLMS. Tous droits réservés.</p>
    </div>
  </footer>
</body>
</html>
