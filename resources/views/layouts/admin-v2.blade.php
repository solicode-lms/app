<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Solicode LMS - V2')</title>

    <!-- Tailwind CSS (CDN avec plugin Forms pour Preline UI) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar V2 -->
        @include('layouts.sidebar-v2')
        <script>
            if (localStorage.getItem('solilms_sidebar_v2_collapsed') === 'true') {
                document.getElementById('app-sidebar')?.classList.add('hidden');
            }
        </script>

        <!-- ========================================================================= -->
        <!-- CONTENU PRINCIPAL -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Header Top Navbar V2 -->
            <header class="bg-white border-b border-slate-200 h-16 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition" title="Afficher / Masquer le menu">
                        <i class="fas fa-bars text-base"></i>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">
                            @yield('page_title', 'Admin V2 - Solicode')
                        </h1>
                        <p class="text-[11px] text-slate-500">
                            @yield('page_subtitle', 'Plateforme d\'apprentissage Solicode')
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @yield('actions')

                    <a href="{{ route('dashbaord') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <i class="fas fa-home text-slate-400"></i>
                        <span>LMS</span>
                    </a>
                </div>
            </header>

            <!-- Zone de Contenu Injectée -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-8">
                @yield('content')
            </main>

            <!-- Footer Standard V2 -->
            <footer class="bg-white border-t border-slate-200 py-4 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2 mt-auto">
                <div class="flex items-center gap-2">
                    <strong>SoliLMS</strong>
                    <span class="text-slate-300">&bull;</span>
                    <span>@yield('footer_info', 'Plateforme d\'apprentissage Solicode')</span>
                </div>
                <div class="text-slate-400 flex items-center gap-2">
                    <span>&copy; {{ date('Y') }} <a href="{{ route('dashbaord') }}" class="hover:text-indigo-600 font-medium transition">Solicode</a>. Tous droits réservés.</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500 font-medium">Version 2.0</span>
                </div>
            </footer>

        </div>

    </div>

    <!-- Script Vanilla JS pour Masquer/Afficher la Sidebar avec persistance localStorage -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            if (sidebar) {
                const isHidden = sidebar.classList.toggle('hidden');
                localStorage.setItem('solilms_sidebar_v2_collapsed', isHidden ? 'true' : 'false');
            }
        }
    </script>

    <!-- Floating UI & Preline UI CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@latest/dist/floating-ui.dom.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>

    @yield('scripts')
</body>
</html>
