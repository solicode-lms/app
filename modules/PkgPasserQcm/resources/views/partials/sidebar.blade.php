<aside x-data="sidebarComponent()" class="w-full md:w-64 flex-shrink-0">
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 sticky top-24">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Progression (par UA)</h2>
        
        <nav class="space-y-2">
            <template x-for="(ua, index) in $store.qcm.uas" :key="index">
                <div 
                    @click="$store.qcm.activeUaIndex = index"
                    class="group flex items-start gap-3 p-2 -mx-2 rounded-lg transition-colors cursor-pointer"
                    :class="$store.qcm.activeUaIndex === index ? 'bg-indigo-50 border border-indigo-100' : 'hover:bg-gray-50'"
                >
                    
                    <template x-if="$store.qcm.isUaCompleted(index) && $store.qcm.activeUaIndex !== index">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </template>
                    
                    <template x-if="$store.qcm.activeUaIndex === index">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center mt-0.5 shadow-sm">
                            <span class="text-xs font-bold" x-text="ua.etape"></span>
                        </div>
                    </template>
                    
                    <template x-if="!$store.qcm.isUaCompleted(index) && $store.qcm.activeUaIndex !== index">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 border-gray-300 text-gray-400 flex items-center justify-center mt-0.5">
                            <span class="text-xs font-bold" x-text="ua.etape"></span>
                        </div>
                    </template>
                    
                    <div>
                        <p class="text-sm font-semibold"
                           :class="$store.qcm.activeUaIndex === index ? 'text-indigo-900' : ($store.qcm.isUaCompleted(index) ? 'text-gray-900' : 'text-gray-500 group-hover:text-gray-700')"
                           x-text="`UA ${ua.etape} : ${ua.ua_titre.substring(0, 20)}${ua.ua_titre.length > 20 ? '...' : ''}`">
                        </p>
                        <p class="text-xs"
                           :class="$store.qcm.activeUaIndex === index ? 'text-indigo-600 font-medium' : ($store.qcm.isUaCompleted(index) ? 'text-emerald-600 font-medium' : 'text-gray-400')"
                           x-text="$store.qcm.activeUaIndex === index ? 'En cours' : ($store.qcm.isUaCompleted(index) ? 'Complété' : 'En attente')">
                        </p>
                    </div>
                </div>
            </template>
        </nav>
    </div>
</aside>
