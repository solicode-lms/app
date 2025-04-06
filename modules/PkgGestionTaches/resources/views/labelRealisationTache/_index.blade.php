{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'labelRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'labelRealisationTache.index' }}', 
        filterFormSelector: '#labelRealisationTache-crud-filter-form',
        crudSelector: '#labelRealisationTache-crud',
        tableSelector: '#labelRealisationTache-data-container',
        formSelector: '#labelRealisationTacheForm',
        indexUrl: '{{ route('labelRealisationTaches.index') }}', 
        createUrl: '{{ route('labelRealisationTaches.create') }}',
        editUrl: '{{ route('labelRealisationTaches.edit',  ['labelRealisationTache' => ':id']) }}',
        showUrl: '{{ route('labelRealisationTaches.show',  ['labelRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('labelRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('labelRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('labelRealisationTaches.destroy',  ['labelRealisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('labelRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::labelRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::labelRealisationTache.singular") }}',
    });
</script>

<div id="labelRealisationTache-crud" class="crud">
    @section('labelRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::labelRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="labelRealisationTache-crud-header" icon="fas fa-tag"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::labelRealisationTache.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('labelRealisationTache-crud-table')
    <section id="labelRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('labelRealisationTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$labelRealisationTaches_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-labelRealisationTache','import-labelRealisationTache','export-labelRealisationTache'])
                        <x-crud-actions
                            :instanceItem="$labelRealisationTache_instance"
                            :createPermission="'create-labelRealisationTache'"
                            :createRoute="route('labelRealisationTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-labelRealisationTache'"
                            :importRoute="route('labelRealisationTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-labelRealisationTache'"
                            :exportXlsxRoute="route('labelRealisationTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('labelRealisationTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$labelRealisationTache_viewTypes"
                            :viewType="$labelRealisationTache_viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('labelRealisationTache-crud-filters')
                <div class="card-header">
                    <form id="labelRealisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($labelRealisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($labelRealisationTaches_filters as $filter)
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
                        @section('labelRealisationTache-crud-search-bar')
                        <div id="labelRealisationTache-crud-search-bar"
                            class="{{ count($labelRealisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('labelRealisationTaches_search')"
                                name="labelRealisationTaches_search"
                                id="labelRealisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="labelRealisationTache-data-container" class="data-container">
                    @if($labelRealisationTache_viewType == "table")
                    @include("PkgGestionTaches::labelRealisationTache._$labelRealisationTache_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="labelRealisationTache-data-container-out" >
        @if($labelRealisationTache_viewType == "widgets")
        @include("PkgGestionTaches::labelRealisationTache._$labelRealisationTache_viewType")
        @endif
    </section>
    @show
</div>