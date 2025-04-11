{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : true,
        entity_name: 'realisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationTache.index' }}', 
        filterFormSelector: '#realisationTache-crud-filter-form',
        crudSelector: '#realisationTache-crud',
        tableSelector: '#realisationTache-data-container',
        formSelector: '#realisationTacheForm',
        indexUrl: '{{ route('realisationTaches.index') }}', 
        createUrl: '{{ route('realisationTaches.create') }}',
        editUrl: '{{ route('realisationTaches.edit',  ['realisationTache' => ':id']) }}',
        showUrl: '{{ route('realisationTaches.show',  ['realisationTache' => ':id']) }}',
        storeUrl: '{{ route('realisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('realisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationTaches.destroy',  ['realisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('realisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::realisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::realisationTache.singular") }}',
    });
</script>

<div id="realisationTache-crud" class="crud">
    @section('realisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::realisationTache.singular");
    @endphp
    <x-crud-header 
        id="realisationTache-crud-header" icon="fas fa-laptop-code"  
        iconColor="text-info"
        title="{{ $realisationTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationTache-crud-table')
    <section id="realisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$realisationTaches_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$realisationTache_instance"
                            :createPermission="'create-realisationTache'"
                            :createRoute="route('realisationTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-realisationTache'"
                            :importRoute="route('realisationTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-realisationTache'"
                            :exportXlsxRoute="route('realisationTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('realisationTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$realisationTache_viewTypes"
                            :viewType="$realisationTache_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('realisationTache-crud-filters')
                <div class="card-header">
                    <form id="realisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationTaches_filters as $filter)
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
                        @section('realisationTache-crud-search-bar')
                        <div id="realisationTache-crud-search-bar"
                            class="{{ count($realisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationTaches_search')"
                                name="realisationTaches_search"
                                id="realisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="realisationTache-data-container" class="data-container">
                    @if($realisationTache_viewType == "table")
                    @include("PkgGestionTaches::realisationTache._$realisationTache_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="realisationTache-data-container-out" >
        @if($realisationTache_viewType == "widgets")
        @include("PkgGestionTaches::realisationTache._$realisationTache_viewType")
        @endif
    </section>
    @show
</div>