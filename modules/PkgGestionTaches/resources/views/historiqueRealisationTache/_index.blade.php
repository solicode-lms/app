{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'historiqueRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'historiqueRealisationTache.index' }}', 
        filterFormSelector: '#historiqueRealisationTache-crud-filter-form',
        crudSelector: '#historiqueRealisationTache-crud',
        tableSelector: '#historiqueRealisationTache-data-container',
        formSelector: '#historiqueRealisationTacheForm',
        indexUrl: '{{ route('historiqueRealisationTaches.index') }}', 
        createUrl: '{{ route('historiqueRealisationTaches.create') }}',
        editUrl: '{{ route('historiqueRealisationTaches.edit',  ['historiqueRealisationTache' => ':id']) }}',
        showUrl: '{{ route('historiqueRealisationTaches.show',  ['historiqueRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('historiqueRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('historiqueRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('historiqueRealisationTaches.destroy',  ['historiqueRealisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('historiqueRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::historiqueRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::historiqueRealisationTache.singular") }}',
    });
</script>

<div id="historiqueRealisationTache-crud" class="crud">
    @section('historiqueRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::historiqueRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="historiqueRealisationTache-crud-header" icon="fas fa-history"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::historiqueRealisationTache.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('historiqueRealisationTache-crud-table')
    <section id="historiqueRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('historiqueRealisationTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$historiqueRealisationTaches_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$historiqueRealisationTache_instance"
                            :createPermission="'create-historiqueRealisationTache'"
                            :createRoute="route('historiqueRealisationTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-historiqueRealisationTache'"
                            :importRoute="route('historiqueRealisationTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-historiqueRealisationTache'"
                            :exportXlsxRoute="route('historiqueRealisationTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('historiqueRealisationTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$historiqueRealisationTache_viewTypes"
                            :viewType="$historiqueRealisationTache_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('historiqueRealisationTache-crud-filters')
                <div class="card-header">
                    <form id="historiqueRealisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($historiqueRealisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($historiqueRealisationTaches_filters as $filter)
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
                        @section('historiqueRealisationTache-crud-search-bar')
                        <div id="historiqueRealisationTache-crud-search-bar"
                            class="{{ count($historiqueRealisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('historiqueRealisationTaches_search')"
                                name="historiqueRealisationTaches_search"
                                id="historiqueRealisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="historiqueRealisationTache-data-container" class="data-container">
                    @if($historiqueRealisationTache_viewType == "table")
                    @include("PkgGestionTaches::historiqueRealisationTache._$historiqueRealisationTache_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="historiqueRealisationTache-data-container-out" >
        @if($historiqueRealisationTache_viewType == "widgets")
        @include("PkgGestionTaches::historiqueRealisationTache._$historiqueRealisationTache_viewType")
        @endif
    </section>
    @show
</div>