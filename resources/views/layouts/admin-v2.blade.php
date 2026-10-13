<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de Bord — Solicode LMS V2')</title>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

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

        <!-- ========================================================================= -->
        <!-- SIDEBAR GAUCHE ADMIN V2 -->
        <!-- ========================================================================= -->
        <aside id="app-sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 sticky top-0 h-screen z-40 transition-all duration-300">
            
            <!-- Logo SoliLMS & Bouton Masquer -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        SL
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900 leading-tight">Solicode LMS</div>
                        <div class="text-[10px] text-indigo-600 font-semibold uppercase tracking-wider">Interface V2</div>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Masquer le menu">
                    <i class="fas fa-bars-staggered text-sm"></i>
                </button>
            </div>

            <!-- Fiche Mini-Profil Utilisateur -->
            @auth
            <div class="p-4 border-b border-slate-100 bg-slate-50/70 flex items-center gap-3">
                <img class="w-10 h-10 rounded-full object-cover border-2 border-indigo-500 shadow-xs" 
                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Utilisateur') }}&background=c7d2fe&color=3730a3" 
                     alt="Avatar">
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[11px] text-slate-500 truncate capitalize">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Utilisateur' }}
                    </div>
                </div>
            </div>
            @endauth

            <!-- Navigation Principale -->
            <nav class="flex-grow p-4 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Tableaux de Bord V2</div>

                @auth
                    @if(Auth::user()->hasAnyRole(['admin', 'root']))
                        <!-- Vue Responsable -->
                        <a href="{{ route('tableau-bord.responsable') }}" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-xs transition {{ Request::is('tableau-bord/responsable*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-2xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-chart-pie text-sm {{ Request::is('tableau-bord/responsable*') ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                                <span>Responsable</span>
                            </div>
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::is('tableau-bord/responsable*') ? 'bg-indigo-600' : 'bg-transparent' }}"></span>
                        </a>

                        <!-- Vue Formateur -->
                        <a href="{{ route('tableau-bord.formateur') }}" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-xs transition {{ Request::is('tableau-bord/formateur*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-2xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-chalkboard-user text-sm {{ Request::is('tableau-bord/formateur*') ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                                <span>Formateur</span>
                            </div>
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::is('tableau-bord/formateur*') ? 'bg-indigo-600' : 'bg-transparent' }}"></span>
                        </a>

                        <!-- Vue Apprenant -->
                        <a href="{{ route('tableau-bord.apprenant') }}" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-xs transition {{ Request::is('tableau-bord/apprenant*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-2xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-graduate text-sm {{ Request::is('tableau-bord/apprenant*') ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                                <span>Apprenant</span>
                            </div>
                            <span class="w-1.5 h-1.5 rounded-full {{ Request::is('tableau-bord/apprenant*') ? 'bg-indigo-600' : 'bg-transparent' }}"></span>
                        </a>
                    @elseif(Auth::user()->hasAnyRole(['formateur', 'admin-formateur']))
                        <a href="{{ route('tableau-bord.formateur') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-2xs">
                            <i class="fas fa-chart-line text-sm text-indigo-600"></i>
                            <span>Mon Tableau de bord</span>
                        </a>
                    @elseif(Auth::user()->hasRole('apprenant'))
                        <a href="{{ route('tableau-bord.apprenant') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-2xs">
                            <i class="fas fa-chart-line text-sm text-indigo-600"></i>
                            <span>Mon Tableau de bord</span>
                        </a>
                    @else
                        <a href="{{ route('tableau-bord.responsable') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-2xs">
                            <i class="fas fa-chart-line text-sm text-indigo-600"></i>
                            <span>Tableau de bord</span>
                        </a>
                    @endif
                @endauth

                <div class="pt-6">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Navigation Système</div>
                    <a href="{{ route('dashbaord') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium text-xs transition">
                        <i class="fas fa-arrow-left text-slate-400"></i>
                        <span>Retour Admin Classique</span>
                    </a>
                </div>
            </nav>

            <!-- Pied de Sidebar / Déconnexion -->
            <div class="p-3 border-t border-slate-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-rose-600 hover:bg-rose-50 rounded-xl font-semibold text-xs transition">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

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
                            @yield('page_title', 'Tableau de Bord')
                        </h1>
                        <p class="text-[11px] text-slate-500">
                            @yield('page_subtitle', 'Indicateurs de Pilotage Solicode LMS')
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

            <!-- Footer Standard Responsable / V2 -->
            <footer class="bg-white border-t border-slate-200 py-4 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2 mt-auto">
                <div>
                    <strong>Solicode LMS V2</strong> &bull; Sprint 1 : Indicateur 01 ($T_{\text{amorçage}}$)
                </div>
                <div class="text-slate-400">
                    &copy; {{ date('Y') }} Solicode. Tous droits réservés.
                </div>
            </footer>

        </div>

    </div>

    <!-- Script Minimal Vanilla JS pour Masquer/Afficher la Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            if (sidebar) {
                sidebar.classList.toggle('hidden');
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
