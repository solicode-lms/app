<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tableau de Bord Responsable — Solicode LMS</title>
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
        <!-- SIDEBAR GAUCHE RESPONSABLE -->
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
                        <div class="text-[10px] text-slate-400 font-medium">Espace Responsable</div>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Masquer le menu">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </button>
            </div>

            <!-- Fiche Mini-Profil -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/60 flex items-center gap-3">
                <img class="w-10 h-10 rounded-full object-cover border-2 border-indigo-500 shadow-xs" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Directeur') }}&background=e0e7ff&color=3730a3" alt="Avatar">
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name ?? 'Mme H. CHRAIBI' }}</div>
                    <div class="text-[11px] text-slate-500 truncate">Direction Pédagogique</div>
                </div>
            </div>

            <!-- Navigation Principale -->
            <nav class="flex-grow p-4 space-y-1.5 overflow-y-auto">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 mb-2">Gouvernance & Audit</div>

                <!-- Dashboard (Actif) -->
                <a href="{{ route('tableau-bord.responsable') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100 shadow-2xs">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Vue Institutionnelle</span>
                </a>

                <!-- Benchmark Cohortes -->
                <a href="#benchmark" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span>Benchmark Cohortes</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">4 filières</span>
                </a>

                <!-- Cohérence IA -->
                <a href="#scatter" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        <span>Indice Cohérence IA</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800">r = 0.78</span>
                </a>

                <!-- Certification 65/35 -->
                <a href="#export" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Bordereaux 65/35</span>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600">Conforme</span>
                </a>
            </nav>

            <!-- Pied de Sidebar : Conformité Docimologique -->
            <div class="p-4 border-t border-slate-200 text-xs bg-slate-50/50">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-slate-500 font-medium">Conformité EFM 65/35</span>
                    <span class="font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">100%</span>
                </div>
                <div class="text-[10px] text-slate-400">Tous les bordereaux scellés</div>
            </div>
        </aside>

        <!-- ========================================================================= -->
        <!-- ZONE DE CONTENU PRINCIPALE (DROITE) -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Header Navbar Responsable -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition" title="Afficher / Masquer la barre latérale">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div>
                            <h1 class="text-base font-bold text-slate-900 leading-tight">Tableau de Bord Institutionnel</h1>
                            <p class="text-xs text-slate-500">Supervision académique & Harmonisation des promotions</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Année 2025 - 2026
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 sm:p-6 lg:p-8 space-y-8 flex-grow">

                <!-- 1. CARTES KPI INSTITUTIONNELS -->
                <section class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Indicateurs Institutionnels & Certification Réglementaire</h2>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                            Audit Réglementaire Conforme
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        
                        <!-- KPI 1 : Délai moyen de prise en main du sprint (Sprint 1) -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Délai moyen de prise en main du sprint</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Harmonisé
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $kpiAmorcage['delai_moyen_global_formate'] ?? '1h50' }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Délai moyen d'amorçage sur l'ensemble des filières actives.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Benchmark inter-groupes</span>
                                <span class="font-bold text-indigo-700">DWB-101 vs DMB-101</span>
                            </div>
                        </div>

                        <!-- KPI 2 : Couverture Référentiel National -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Taux de couverture du référentiel</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Cible &ge; 90%
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">91.2%</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Part des compétences nationales validées sur toutes les cohortes.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Modules certifiables</span>
                                <span class="font-bold text-slate-800">12 / 12 modules</span>
                            </div>
                        </div>

                        <!-- KPI 3 : Conformité Bordereaux Examen -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Conformité des bordereaux d'examen</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    100% Conformes
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">100%</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Bordereaux de notes validés sans divergence de calcul.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Statut administratif</span>
                                <span class="font-bold text-emerald-700 flex items-center gap-1">Prêt pour export</span>
                            </div>
                        </div>

                        <!-- KPI 4 : Indice de Cohérence Docimologique -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Indice de cohérence docimologique (IA)</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    Forte
                                </span>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-indigo-600 tracking-tight">r = 0.78</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Corrélation entre évaluation directe et projet libre outillé.</p>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                <span>Cas suspects à vérifier</span>
                                <span class="font-bold text-rose-600">2 cas identifiés</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. MATRICE COMPARATIVE & BENCHMARK DES PROMOTIONS -->
                <section id="benchmark" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                    <div class="p-5 border-b border-slate-200 bg-slate-50/70 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Matrice Comparative Inter-Promotions & Rythme d'Amorçage</h3>
                            <p class="text-xs text-slate-500">Benchmark des cohortes pour l'harmonisation des exigences pédagogiques</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                                    <th class="py-3 px-5">Groupe / Promotion</th>
                                    <th class="py-3 px-4">Délai Moyen d'Amorçage (T_amorçage)</th>
                                    <th class="py-3 px-4">Effectif Amorcé</th>
                                    <th class="py-3 px-4">Taux d'Amorce</th>
                                    <th class="py-3 px-5 text-right">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @if(!empty($kpiAmorcage['benchmark']))
                                    @foreach($kpiAmorcage['benchmark'] as $item)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="py-3 px-5 font-bold text-slate-900">{{ $item['groupe_code'] }} ({{ $item['groupe_nom'] }})</td>
                                            <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $item['temps_moyen_formate'] }}</td>
                                            <td class="py-3 px-4 font-mono">{{ $item['nb_amorces'] }} / {{ $item['total_apprenants'] }} stagiaires</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    {{ $item['taux_amorce'] }}% amorcés
                                                </span>
                                            </td>
                                            <td class="py-3 px-5 text-right">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    Conforme
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-3 px-5 font-bold text-slate-900">DWB-101 (Développement Web)</td>
                                        <td class="py-3 px-4 font-mono font-bold text-indigo-700">1h45</td>
                                        <td class="py-3 px-4 font-mono">17 / 20 stagiaires</td>
                                        <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">85.0% amorcés</span></td>
                                        <td class="py-3 px-5 text-right"><span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Conforme</span></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-3 px-5 font-bold text-slate-900">DMB-101 (Développement Mobile)</td>
                                        <td class="py-3 px-4 font-mono font-bold text-indigo-700">1h55</td>
                                        <td class="py-3 px-4 font-mono">15 / 20 stagiaires</td>
                                        <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">75.0% amorcés</span></td>
                                        <td class="py-3 px-5 text-right"><span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Conforme</span></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>

            </main>

            <!-- Footer Standard Responsable -->
            <footer class="bg-white border-t border-slate-200 py-5 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3 mt-auto">
                <div>
                    <strong>Solicode LMS V2</strong> &bull; Tableau de Bord de Gouvernance &bull; Indicateurs de Pilotage Sprint 1
                </div>
                <div class="text-slate-400">
                    Direction Académique
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
