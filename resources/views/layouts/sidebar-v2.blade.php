<!-- ========================================================================= -->
<!-- SIDEBAR GAUCHE ADMIN V2 -->
<!-- ========================================================================= -->
<aside id="app-sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 sticky top-0 h-screen z-40 transition-all duration-300">
    
    <!-- Logo SoliLMS & Bouton Masquer -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200">
        <a href="{{ route('dashbaord') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('images/logo.png') }}" alt="SoliLMS" class="w-8 h-8 object-contain shrink-0">
            <div>
                <div class="text-sm font-bold text-slate-900 leading-tight flex items-center gap-1.5">
                    <span>SoliLMS</span>
                    @if(isset($sessionState) && !empty($sessionState->all()['user_annee_formation']))
                        <span class="text-[11px] font-normal text-slate-500">({{ $sessionState->all()['user_annee_formation'] }})</span>
                    @endif
                </div>
                <div class="text-[10px] text-indigo-600 font-semibold uppercase tracking-wider">Admin V2</div>
            </div>
        </a>
       
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
        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Tableaux de Bord</div>

        @auth
            @if(Auth::user()->hasAnyRole(['root']))
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

                <!-- Rapport Recherche / Mémoire -->
                <a href="{{ route('tableau-bord.recherche') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-xs transition {{ Request::is('tableau-bord/recherche*') ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-2xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-microscope text-sm {{ Request::is('tableau-bord/recherche*') ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                        <span>Rapport Mémoire</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-700 border border-amber-200">R&D</span>
                </a>


             @elseif(Auth::user()->hasAnyRole(['admin']))
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
