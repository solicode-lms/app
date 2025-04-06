{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'dependanceTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'dependanceTache.index' }}', 
        filterFormSelector: '#dependanceTache-crud-filter-form',
        crudSelector: '#dependanceTache-crud',
        tableSelector: '#dependanceTache-data-container',
        formSelector: '#dependanceTacheForm',
        indexUrl: '{{ route('dependanceTaches.index') }}', 
        createUrl: '{{ route('dependanceTaches.create') }}',
        editUrl: '{{ route('dependanceTaches.edit',  ['dependanceTache' => ':id']) }}',
        showUrl: '{{ route('dependanceTaches.show',  ['dependanceTache' => ':id']) }}',
        storeUrl: '{{ route('dependanceTaches.store') }}', 
        updateAttributesUrl: '{{ route('dependanceTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('dependanceTaches.destroy',  ['dependanceTache' => ':id']) }}', 
        calculationUrl:  '{{ route('dependanceTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::dependanceTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::dependanceTache.singular") }}',
    });
</script>

<div id="dependanceTache-crud" class="crud">
    @section('dependanceTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::dependanceTache.singular");
    @endphp
    <x-crud-header 
        id="dependanceTache-crud-header" icon="fas fa-link"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::dependanceTache.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('dependanceTache-crud-table')
    <section id="dependanceTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('dependanceTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$dependanceTaches_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-dependanceTache','import-dependanceTache','export-dependanceTache'])
                        <x-crud-actions
                            :instanceItem="$dependanceTache_instance"
                            :createPermission="'create-dependanceTache'"
                            :createRoute="route('dependanceTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-dependanceTache'"
                            :importRoute="route('dependanceTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-dependanceTache'"
                            :exportXlsxRoute="route('dependanceTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('dependanceTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$dependanceTache_viewTypes"
                            :viewType="$dependanceTache_viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('dependanceTache-crud-filters')
                <div class="card-header">
                    <form id="dependanceTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($dependanceTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($dependanceTaches_filters as $filter)
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
                        @section('dependanceTache-crud-search-bar')
                        <div id="dependanceTache-crud-search-bar"
                            class="{{ count($dependanceTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('dependanceTaches_search')"
                                name="dependanceTaches_search"
                                id="dependanceTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="dependanceTache-data-container" class="data-container">
                    @if($dependanceTache_viewType == "table")
                    @include("PkgGestionTaches::dependanceTache._$dependanceTache_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="dependanceTache-data-container-out" >
        @if($dependanceTache_viewType == "widgets")
        @include("PkgGestionTaches::dependanceTache._$dependanceTache_viewType")
        @endif
    </section>
    @show
</div>