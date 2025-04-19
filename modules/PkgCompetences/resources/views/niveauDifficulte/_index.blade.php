{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'niveauDifficulte',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'niveauDifficulte.index' }}', 
        filterFormSelector: '#niveauDifficulte-crud-filter-form',
        crudSelector: '#niveauDifficulte-crud',
        tableSelector: '#niveauDifficulte-data-container',
        formSelector: '#niveauDifficulteForm',
        indexUrl: '{{ route('niveauDifficultes.index') }}', 
        createUrl: '{{ route('niveauDifficultes.create') }}',
        editUrl: '{{ route('niveauDifficultes.edit',  ['niveauDifficulte' => ':id']) }}',
        showUrl: '{{ route('niveauDifficultes.show',  ['niveauDifficulte' => ':id']) }}',
        storeUrl: '{{ route('niveauDifficultes.store') }}', 
        updateAttributesUrl: '{{ route('niveauDifficultes.updateAttributes') }}', 
        deleteUrl: '{{ route('niveauDifficultes.destroy',  ['niveauDifficulte' => ':id']) }}', 
        calculationUrl:  '{{ route('niveauDifficultes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauDifficulte.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::niveauDifficulte.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $niveauDifficulte_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="niveauDifficulte-crud" class="crud">
    @section('niveauDifficulte-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::niveauDifficulte.singular");
    @endphp
    <x-crud-header 
        id="niveauDifficulte-crud-header" icon="fas fa-battery-three-quarters"  
        iconColor="text-info"
        title="{{ $niveauDifficulte_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauDifficulte-crud-table')
    <section id="niveauDifficulte-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauDifficulte-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$niveauDifficultes_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$niveauDifficulte_instance"
                                :createPermission="'create-niveauDifficulte'"
                                :createRoute="route('niveauDifficultes.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-niveauDifficulte'"
                                :importRoute="route('niveauDifficultes.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-niveauDifficulte'"
                                :exportXlsxRoute="route('niveauDifficultes.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('niveauDifficultes.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$niveauDifficulte_viewTypes"
                                :viewType="$niveauDifficulte_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('niveauDifficulte-crud-filters')
                <div class="card-header">
                    <form id="niveauDifficulte-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauDifficultes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauDifficultes_filters as $filter)
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
                        @section('niveauDifficulte-crud-search-bar')
                        <div id="niveauDifficulte-crud-search-bar"
                            class="{{ count($niveauDifficultes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauDifficultes_search')"
                                name="niveauDifficultes_search"
                                id="niveauDifficultes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="niveauDifficulte-data-container" class="data-container">
                    @if($niveauDifficulte_viewType == "table")
                    @include("PkgCompetences::niveauDifficulte._$niveauDifficulte_viewType")
                    @endif
                </div>
                @section('niveauDifficulte-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-niveauDifficulte")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('niveauDifficultes.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-niveauDifficulte')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('niveauDifficultes.bulkDelete') }}" 
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
     <section id="niveauDifficulte-data-container-out" >
        @if($niveauDifficulte_viewType == "widgets")
        @include("PkgCompetences::niveauDifficulte._$niveauDifficulte_viewType")
        @endif
    </section>
    @show
</div>