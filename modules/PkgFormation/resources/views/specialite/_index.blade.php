{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'specialite',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'specialite.index' }}', 
        filterFormSelector: '#specialite-crud-filter-form',
        crudSelector: '#specialite-crud',
        tableSelector: '#specialite-data-container',
        formSelector: '#specialiteForm',
        indexUrl: '{{ route('specialites.index') }}', 
        createUrl: '{{ route('specialites.create') }}',
        editUrl: '{{ route('specialites.edit',  ['specialite' => ':id']) }}',
        showUrl: '{{ route('specialites.show',  ['specialite' => ':id']) }}',
        storeUrl: '{{ route('specialites.store') }}', 
        updateAttributesUrl: '{{ route('specialites.updateAttributes') }}', 
        deleteUrl: '{{ route('specialites.destroy',  ['specialite' => ':id']) }}', 
        calculationUrl:  '{{ route('specialites.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::specialite.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::specialite.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $specialite_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="specialite-crud" class="crud">
    @section('specialite-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::specialite.singular");
    @endphp
    <x-crud-header 
        id="specialite-crud-header" icon="fas fa-award"  
        iconColor="text-info"
        title="{{ $specialite_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('specialite-crud-table')
    <section id="specialite-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('specialite-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$specialites_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$specialite_instance"
                                :createPermission="'create-specialite'"
                                :createRoute="route('specialites.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-specialite'"
                                :importRoute="route('specialites.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-specialite'"
                                :exportXlsxRoute="route('specialites.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('specialites.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$specialite_viewTypes"
                                :viewType="$specialite_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('specialite-crud-filters')
                <div class="card-header">
                    <form id="specialite-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($specialites_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($specialites_filters as $filter)
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
                        @section('specialite-crud-search-bar')
                        <div id="specialite-crud-search-bar"
                            class="{{ count($specialites_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('specialites_search')"
                                name="specialites_search"
                                id="specialites_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="specialite-data-container" class="data-container">
                    @if($specialite_viewType == "table")
                    @include("PkgFormation::specialite._$specialite_viewType")
                    @endif
                </div>
                @section('specialite-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-specialite")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('specialites.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-specialite')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('specialites.bulkDelete') }}" 
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
     <section id="specialite-data-container-out" >
        @if($specialite_viewType == "widgets")
        @include("PkgFormation::specialite._$specialite_viewType")
        @endif
    </section>
    @show
</div>