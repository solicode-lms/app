{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'livrablesRealisation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'livrablesRealisation.index' }}', 
        filterFormSelector: '#livrablesRealisation-crud-filter-form',
        crudSelector: '#livrablesRealisation-crud',
        tableSelector: '#livrablesRealisation-data-container',
        formSelector: '#livrablesRealisationForm',
        indexUrl: '{{ route('livrablesRealisations.index') }}', 
        createUrl: '{{ route('livrablesRealisations.create') }}',
        editUrl: '{{ route('livrablesRealisations.edit',  ['livrablesRealisation' => ':id']) }}',
        showUrl: '{{ route('livrablesRealisations.show',  ['livrablesRealisation' => ':id']) }}',
        storeUrl: '{{ route('livrablesRealisations.store') }}', 
        updateAttributesUrl: '{{ route('livrablesRealisations.updateAttributes') }}', 
        deleteUrl: '{{ route('livrablesRealisations.destroy',  ['livrablesRealisation' => ':id']) }}', 
        calculationUrl:  '{{ route('livrablesRealisations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::livrablesRealisation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::livrablesRealisation.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $livrablesRealisation_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="livrablesRealisation-crud" class="crud">
    @section('livrablesRealisation-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::livrablesRealisation.singular");
    @endphp
    <x-crud-header 
        id="livrablesRealisation-crud-header" icon="fas fa-file-code"  
        iconColor="text-info"
        title="{{ $livrablesRealisation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('livrablesRealisation-crud-table')
    <section id="livrablesRealisation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('livrablesRealisation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$livrablesRealisations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$livrablesRealisation_instance"
                                :createPermission="'create-livrablesRealisation'"
                                :createRoute="route('livrablesRealisations.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-livrablesRealisation'"
                                :importRoute="route('livrablesRealisations.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-livrablesRealisation'"
                                :exportXlsxRoute="route('livrablesRealisations.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('livrablesRealisations.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$livrablesRealisation_viewTypes"
                                :viewType="$livrablesRealisation_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('livrablesRealisation-crud-filters')
                <div class="card-header">
                    <form id="livrablesRealisation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($livrablesRealisations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($livrablesRealisations_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" 
                                    :targetDynamicDropdown="isset($filter['targetDynamicDropdown']) ? $filter['targetDynamicDropdown'] : null"
                                    :targetDynamicDropdownApiUrl="isset($filter['targetDynamicDropdownApiUrl']) ? $filter['targetDynamicDropdownApiUrl'] : null" 
                                    :targetDynamicDropdownFilter="isset($filter['targetDynamicDropdownFilter']) ? $filter['targetDynamicDropdownFilter'] : null" />
                            @endforeach
                        </x-filter-group>
                        @section('livrablesRealisation-crud-search-bar')
                        <div id="livrablesRealisation-crud-search-bar"
                            class="{{ count($livrablesRealisations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('livrablesRealisations_search')"
                                name="livrablesRealisations_search"
                                id="livrablesRealisations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="livrablesRealisation-data-container" class="data-container">
                    @if($livrablesRealisation_viewType == "table")
                    @include("PkgRealisationProjets::livrablesRealisation._$livrablesRealisation_viewType")
                    @endif
                </div>
                @section('realisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('livrablesRealisations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('livrablesRealisations.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="livrablesRealisation-data-container-out" >
        @if($livrablesRealisation_viewType == "widgets")
        @include("PkgRealisationProjets::livrablesRealisation._$livrablesRealisation_viewType")
        @endif
    </section>
    @show
</div>