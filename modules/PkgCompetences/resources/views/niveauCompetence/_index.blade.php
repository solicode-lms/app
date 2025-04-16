{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'niveauCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'niveauCompetence.index' }}', 
        filterFormSelector: '#niveauCompetence-crud-filter-form',
        crudSelector: '#niveauCompetence-crud',
        tableSelector: '#niveauCompetence-data-container',
        formSelector: '#niveauCompetenceForm',
        indexUrl: '{{ route('niveauCompetences.index') }}', 
        createUrl: '{{ route('niveauCompetences.create') }}',
        editUrl: '{{ route('niveauCompetences.edit',  ['niveauCompetence' => ':id']) }}',
        showUrl: '{{ route('niveauCompetences.show',  ['niveauCompetence' => ':id']) }}',
        storeUrl: '{{ route('niveauCompetences.store') }}', 
        updateAttributesUrl: '{{ route('niveauCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('niveauCompetences.destroy',  ['niveauCompetence' => ':id']) }}', 
        calculationUrl:  '{{ route('niveauCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $niveauCompetence_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="niveauCompetence-crud" class="crud">
    @section('niveauCompetence-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::niveauCompetence.singular");
    @endphp
    <x-crud-header 
        id="niveauCompetence-crud-header" icon="fas fa-battery-three-quarters"  
        iconColor="text-info"
        title="{{ $niveauCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauCompetence-crud-table')
    <section id="niveauCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$niveauCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$niveauCompetence_instance"
                                :createPermission="'create-niveauCompetence'"
                                :createRoute="route('niveauCompetences.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-niveauCompetence'"
                                :importRoute="route('niveauCompetences.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-niveauCompetence'"
                                :exportXlsxRoute="route('niveauCompetences.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('niveauCompetences.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$niveauCompetence_viewTypes"
                                :viewType="$niveauCompetence_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('niveauCompetence-crud-filters')
                <div class="card-header">
                    <form id="niveauCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauCompetences_filters as $filter)
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
                        @section('niveauCompetence-crud-search-bar')
                        <div id="niveauCompetence-crud-search-bar"
                            class="{{ count($niveauCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauCompetences_search')"
                                name="niveauCompetences_search"
                                id="niveauCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="niveauCompetence-data-container" class="data-container">
                    @if($niveauCompetence_viewType == "table")
                    @include("PkgCompetences::niveauCompetence._$niveauCompetence_viewType")
                    @endif
                </div>
                @section('niveauCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-niveauCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('niveauCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-niveauCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('niveauCompetences.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    @endcan
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="niveauCompetence-data-container-out" >
        @if($niveauCompetence_viewType == "widgets")
        @include("PkgCompetences::niveauCompetence._$niveauCompetence_viewType")
        @endif
    </section>
    @show
</div>