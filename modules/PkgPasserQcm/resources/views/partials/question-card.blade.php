<div x-data="questionComponent(question)" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex-grow mb-6">
    
    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 leading-snug mb-8" x-text="question.enonce"></h3>

    <!-- Options -->
    <div class="space-y-4">
        <template x-for="proposition in question.propositions" :key="proposition.id">
            <label 
                class="relative flex cursor-pointer rounded-xl border p-5 shadow-sm focus:outline-none transition-all"
                :class="isSelected(proposition.id) ? 'border-indigo-600 bg-indigo-50/30' : 'border-gray-200 bg-white hover:border-indigo-300 hover:bg-indigo-50/50'"
            >
                
                <input 
                    :type="isMultiple ? 'checkbox' : 'radio'" 
                    :name="`reponse_${question.id}${isMultiple ? '[]' : ''}`" 
                    :value="proposition.id" 
                    @change="toggle(proposition.id)"
                    :checked="isSelected(proposition.id)"
                    class="sr-only" 
                />
                
                <span class="flex flex-1">
                    <span class="flex flex-col">
                        <span class="block text-sm font-medium" 
                              :class="isSelected(proposition.id) ? 'text-indigo-900' : 'text-gray-900'"
                              x-text="proposition.libelle"></span>
                    </span>
                </span>
                
                <template x-if="isMultiple">
                    <div class="w-5 h-5 rounded border-2 flex items-center justify-center ml-4 mt-0.5"
                         :class="isSelected(proposition.id) ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-gray-300'">
                         <template x-if="isSelected(proposition.id)">
                             <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                         </template>
                    </div>
                </template>
                
                <template x-if="!isMultiple">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ml-4 mt-0.5"
                         :class="isSelected(proposition.id) ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'">
                         <template x-if="isSelected(proposition.id)">
                             <div class="w-2 h-2 rounded-full bg-white"></div>
                         </template>
                    </div>
                </template>
                
            </label>
        </template>
    </div>
</div>
