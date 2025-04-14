{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'workflowTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'workflowTache.index' }}', 
        filterFormSelector: '#workflowTache-crud-filter-form',
        crudSelector: '#workflowTache-crud',
        tableSelector: '#workflowTache-data-container',
        formSelector: '#workflowTacheForm',
        indexUrl: '{{ route('workflowTaches.index') }}', 
        createUrl: '{{ route('workflowTaches.create') }}',
        editUrl: '{{ route('workflowTaches.edit',  ['workflowTache' => ':id']) }}',
        showUrl: '{{ route('workflowTaches.show',  ['workflowTache' => ':id']) }}',
        storeUrl: '{{ route('workflowTaches.store') }}', 
        updateAttributesUrl: '{{ route('workflowTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('workflowTaches.destroy',  ['workflowTache' => ':id']) }}', 
        calculationUrl:  '{{ route('workflowTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::workflowTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::workflowTache.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $workflowTache_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="workflowTache-crud" class="crud">
    @section('workflowTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::workflowTache.singular");
    @endphp
    <x-crud-header 
        id="workflowTache-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $workflowTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('workflowTache-crud-table')
    <section id="workflowTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('workflowTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$workflowTaches_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$workflowTache_instance"
                            :createPermission="'create-workflowTache'"
                            :createRoute="route('workflowTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-workflowTache'"
                            :importRoute="route('workflowTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-workflowTache'"
                            :exportXlsxRoute="route('workflowTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('workflowTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$workflowTache_viewTypes"
                            :viewType="$workflowTache_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('workflowTache-crud-filters')
                <div class="card-header">
                    <form id="workflowTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($workflowTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($workflowTaches_filters as $filter)
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
                        @section('workflowTache-crud-search-bar')
                        <div id="workflowTache-crud-search-bar"
                            class="{{ count($workflowTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('workflowTaches_search')"
                                name="workflowTaches_search"
                                id="workflowTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="workflowTache-data-container" class="data-container">
                    @if($workflowTache_viewType == "table")
                    @include("PkgGestionTaches::workflowTache._$workflowTache_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="workflowTache-data-container-out" >
        @if($workflowTache_viewType == "widgets")
        @include("PkgGestionTaches::workflowTache._$workflowTache_viewType")
        @endif
    </section>
    @show
</div>