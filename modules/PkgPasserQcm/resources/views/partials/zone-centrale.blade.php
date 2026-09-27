    <!-- Zone Centrale -->
    <section x-data="zoneCentraleComponent()" class="flex-grow flex flex-col max-w-3xl">
        
        <template x-if="$store.qcm.uas.length > 0">
            <div>
                <!-- Breadcrumb / Infos -->
                <div class="mb-6 flex justify-between items-end">
                    <div>
                        <span class="text-indigo-600 font-medium text-sm tracking-wide uppercase" x-text="`Unité d'Apprentissage ${activeUa.etape}`"></span>
                        <h2 class="text-2xl font-bold text-gray-900 mt-1" x-text="activeUa.ua_titre"></h2>
                    </div>
                    <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full" x-text="`${activeUa.questions.length} Questions`"></span>
                </div>

                <!-- Liste des questions pour cette UA -->
                <template x-for="question in activeUa.questions" :key="question.id">
                    <div>
                        @include('PkgPasserQcm::partials.question-card')
                    </div>
                </template>
            </div>
        </template>

        <template x-if="$store.qcm.uas.length === 0">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-gray-500">Aucune question trouvée pour ce QCM.</p>
            </div>
        </template>



        <!-- Overlay de chargement (Bloquant pour soumission) -->
        <div x-show="$store.qcm.isLoading" 
             style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 font-medium">Soumission en cours...</span>
            </div>
        </div>

        <!-- Toast de sauvegarde (Non bloquant) -->
        <div x-show="$store.qcm.isSaving" 
             style="display: none;" 
             class="fixed bottom-4 right-4 z-40 bg-white border border-gray-100 shadow-lg rounded-full px-4 py-2 flex items-center gap-3 transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4">
            <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm text-gray-600 font-medium">Sauvegarde en cours...</span>
        </div>
    </section>
