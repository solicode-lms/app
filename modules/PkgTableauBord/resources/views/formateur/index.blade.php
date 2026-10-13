<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cockpit Formateur — Solicode LMS</title>
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="flex min-h-screen">

        <!-- ========================================================================= -->
        <!-- SIDEBAR GAUCHE FORMATEUR -->
        <!-- ========================================================================= -->
        <aside id="app-sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 sticky top-0 h-screen z-40 transition-all duration-300">
            <!-- Logo SoliLMS & Bouton Masquer -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        SL
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900 leading-tight">Solicode LMS</div>
                        <div class="text-[10px] text-slate-400 font-medium">Espace Formateur</div>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Masquer le menu">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <!-- Fiche Mini-Profil -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/60 flex items-center gap-3">
                <img class="w-10 h-10 rounded-full object-cover border-2 border-indigo-500 shadow-xs" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Formateur') }}&background=e0e7ff&color=4338ca" alt="Avatar">
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name ?? 'Dr. F. ESSARRAJ' }}</div>
                    <div class="text-[11px] text-slate-500 truncate">Formateur Référent &bull; {{ $affectationProjet->groupe->code ?? 'DWB-101' }}</div>
                </div>
            </div>

            <!-- Navigation Principale -->
            <nav class="flex-grow p-4 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Pilotage Didactique</div>

                <!-- Dashboard (Actif) -->
                <a href="{{ route('tableau-bord.formateur') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100 shadow-2xs">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Cockpit de la classe</span>
                </a>

                <!-- File FIFO -->
                <a href="#fifo" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>File Live Coding</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">4 en attente</span>
                </a>

                <!-- Alertes & Remédiations -->
                <a href="#alertes" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Alertes Seuil +10 min</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">2 critiques</span>
                </a>

                <!-- Kanban des Tâches -->
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                    <span>Vue Kanban globale</span>
                </a>

                <!-- Bordereau 65/35 -->
                <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Bordereau 65/35</span>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600">Prêt</span>
                </a>
            </nav>

            <!-- Pied de Sidebar : Charge Logistique -->
            <div class="p-4 border-t border-slate-200 text-xs bg-slate-50/50">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-slate-500 font-medium">Charge logistique</span>
                    <span class="font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">2.1 h / sem</span>
                </div>
                <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full" style="width: 42%"></div>
                </div>
                <div class="text-[10px] text-slate-400 mt-1.5 text-right">Cible &le; 2.5 h / sem</div>
            </div>
        </aside>

        <!-- ========================================================================= -->
        <!-- ZONE DE CONTENU PRINCIPALE (DROITE) -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Header Navbar Formateur -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition" title="Afficher / Masquer la barre latérale">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div>
                            <h1 class="text-base font-bold text-slate-900 leading-tight">Cockpit de Pilotage Didactique</h1>
                            <p class="text-xs text-slate-500">Régulation des passages, indicateurs et soutien en direct</p>
                        </div>
                    </div>

                    <!-- Sélecteur de Promotion / Session -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 bg-slate-100/80 px-3.5 py-1.5 rounded-xl border border-slate-200/60 text-xs">
                            <span class="font-semibold text-slate-700">Groupe :</span>
                            <span class="text-indigo-700 font-bold">{{ $affectationProjet->groupe->code ?? 'DWB-101' }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-slate-600 font-semibold">{{ $affectationProjet->projet->titre ?? 'Blog Personnel' }}</span>
                        </div>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Session Live Active
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 sm:p-6 lg:p-8 space-y-8 flex-grow">

                <!-- 1. CARTES KPI DE COHORTE -->
                <section class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs de Pilotage Didactique & Flux</h2>
                        <span class="text-xs font-semibold text-indigo-600">Promotion {{ $affectationProjet->groupe->code ?? 'DWB-101' }} &bull; Session active</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        
                        <!-- KPI 1 : File d'Attente -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Temps d'attente moyen en file</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Charge Active
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-amber-600 tracking-tight">28 min</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Délai moyen d'attente entre soumission et passage en soutenance.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Apprenants en attente</span>
                                <span class="font-bold text-amber-700">4 en file active</span>
                            </div>
                        </div>

                        <!-- KPI 2 : Validation au 1er passage -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Validation au 1er passage</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    75% validés
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">75%</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">15 validés au 1er passage / 5 orientés en séance de rattrapage.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Séance 2ème tentative</span>
                                <span class="font-bold text-indigo-600">Planifier session (5)</span>
                            </div>
                        </div>

                        <!-- KPI 3 DYNAMIQUE : Temps moyen de mise au travail (Indicateur 01 T_amorçage) -->
                        @include('PkgTableauBord::components.kpi-amorcage', ['type' => 'formateur', 'kpi' => $kpiAmorcage])

                        <!-- KPI 4 : Prise en charge des blocages -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Taux de prise en charge des blocages</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Excellence (&ge; 85%)
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">88%</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Apprenants bloqués ayant reçu un étayage formalisé.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>En attente de soutien</span>
                                <span class="font-bold text-rose-600">1 apprenant</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. FLASHBOX ALERTE SEUIL 10 MIN -->
                <section id="alertes" class="bg-rose-50 border-2 border-rose-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-bold text-rose-900">Apprenants sans livrable post-séance (+10 min)</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-200 text-rose-800">Seuil Critique</span>
                                </div>
                                <p class="text-xs sm:text-sm text-rose-700 mt-1">
                                    Séance plénière de Live Coding terminée. Activation recommandée du trinôme d'entraide ou étayage direct.
                                </p>
                                
                                <div class="mt-3 flex flex-wrap gap-2.5">
                                    <div class="bg-white px-3 py-1.5 rounded-lg border border-rose-200 flex items-center gap-2 shadow-xs">
                                        <img class="w-5 h-5 rounded-full" src="https://ui-avatars.com/api/?name=Amine+Mansouri&background=fee2e2&color=991b1b" alt="Amine">
                                        <span class="text-xs font-bold text-rose-900">Amine Mansouri</span>
                                        <span class="text-[11px] font-mono font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">+14 min</span>
                                    </div>
                                    <div class="bg-white px-3 py-1.5 rounded-lg border border-rose-200 flex items-center gap-2 shadow-xs">
                                        <img class="w-5 h-5 rounded-full" src="https://ui-avatars.com/api/?name=Sara+Bennani&background=fee2e2&color=991b1b" alt="Sara">
                                        <span class="text-xs font-bold text-rose-900">Sara Bennani</span>
                                        <span class="text-[11px] font-mono font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">+11 min</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex sm:flex-col gap-2 shrink-0 w-full lg:w-auto">
                            <button class="w-full sm:w-auto px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                Assigner Trinôme d'Entraide
                            </button>
                            <button class="w-full sm:w-auto px-4 py-2 bg-white hover:bg-rose-100 text-rose-700 border border-rose-300 text-xs font-bold rounded-xl transition">
                                Ouvrir Atelier de Soutien
                            </button>
                        </div>
                    </div>
                </section>

                <!-- 3. CONSOLE OPÉRATIONNELLE : FILE FIFO -->
                <section id="fifo" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                    <div class="p-5 border-b border-slate-200 bg-slate-50/70 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-extrabold text-sm">
                                4
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">File de Soutenance en Direct &bull; Prototype Niveau 2</h3>
                                <p class="text-xs text-slate-500">Ordre d'arrivée strict (premier arrivé, premier soutenu)</p>
                            </div>
                        </div>

                        <button class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Lancer Session Mutualisée 4x4
                        </button>
                    </div>

                    <div class="divide-y divide-slate-200">
                        <!-- Rang #1 En cours -->
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-indigo-50/40 border-l-4 border-indigo-600">
                            <div class="flex items-center gap-4">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold bg-indigo-600 text-white shadow-xs">#1</span>
                                <img class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-xs" src="https://ui-avatars.com/api/?name=Othmane+Chraibi&background=c7d2fe&color=3730a3" alt="Othmane">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-slate-900">Othmane Chraibi</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">En Live Coding Actif</span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">Prototype ORM — E-Commerce &bull; Essai #1</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs transition">Valider Prototype</button>
                                <button class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-xs transition">Révision</button>
                            </div>
                        </div>

                        <!-- Rang #2 Suivant -->
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-4">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold bg-slate-100 text-slate-600">#2</span>
                                <img class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-xs" src="https://ui-avatars.com/api/?name=Yassine+El+Amrani&background=fed7aa&color=c2410c" alt="Yassine">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-slate-900">Yassine El Amrani</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200">Suivant</span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">Prototype ORM — E-Commerce &bull; Essai #1</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-mono font-bold text-slate-700">18:12 min</span>
                                <button class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-xs transition">Appeler Yassine</button>
                            </div>
                        </div>

                        <!-- Rang #3 -->
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition">
                            <div class="flex items-center gap-4">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold bg-slate-100 text-slate-600">#3</span>
                                <img class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-xs" src="https://ui-avatars.com/api/?name=Khadija+Mansour&background=bbf7d0&color=15803d" alt="Khadija">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-slate-900">Khadija Mansour</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200">En attente</span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">Prototype ORM — E-Commerce &bull; Essai #1</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-mono font-bold text-slate-700">22:45 min</span>
                                <button class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg transition">Détails</button>
                            </div>
                        </div>
                    </div>
                </section>

            </main>

            <!-- Footer Standard Formateur -->
            <footer class="bg-white border-t border-slate-200 py-5 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3 mt-auto">
                <div>
                    <strong>Solicode LMS V2</strong> &bull; Cockpit Formateur &bull; Pilotage Didactique & Régulation
                </div>
                <div class="text-slate-400">
                    Mise à jour en temps réel
                </div>
            </footer>

        </div>
    </div>

    <!-- Script Minimal Vanilla JS pour Masquer/Afficher la Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>
