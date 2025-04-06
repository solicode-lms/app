{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'prioriteTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'prioriteTache.index' }}', 
        filterFormSelector: '#prioriteTache-crud-filter-form',
        crudSelector: '#prioriteTache-crud',
        tableSelector: '#prioriteTache-data-container',
        formSelector: '#prioriteTacheForm',
        indexUrl: '{{ route('prioriteTaches.index') }}', 
        createUrl: '{{ route('prioriteTaches.create') }}',
        editUrl: '{{ route('prioriteTaches.edit',  ['prioriteTache' => ':id']) }}',
        showUrl: '{{ route('prioriteTaches.show',  ['prioriteTache' => ':id']) }}',
        storeUrl: '{{ route('prioriteTaches.store') }}', 
        updateAttributesUrl: '{{ route('prioriteTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('prioriteTaches.destroy',  ['prioriteTache' => ':id']) }}', 
        calculationUrl:  '{{ route('prioriteTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::prioriteTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::prioriteTache.singular") }}',
    });
</script>

<div id="prioriteTache-crud" class="crud">
    @section('prioriteTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::prioriteTache.singular");
    @endphp
    <x-crud-header 
        id="prioriteTache-crud-header" icon="fas fa-list-ol"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::prioriteTache.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('prioriteTache-crud-table')
    <section id="prioriteTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('prioriteTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$prioriteTaches_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-prioriteTache','import-prioriteTache','export-prioriteTache'])
                        <x-crud-actions
                            :instanceItem="$prioriteTache_instance"
                            :createPermission="'create-prioriteTache'"
                            :createRoute="route('prioriteTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-prioriteTache'"
                            :importRoute="route('prioriteTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-prioriteTache'"
                            :exportXlsxRoute="route('prioriteTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('prioriteTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$prioriteTache_viewTypes"
                            :viewType="$prioriteTache_viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('prioriteTache-crud-filters')
                <div class="card-header">
                    <form id="prioriteTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($prioriteTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($prioriteTaches_filters as $filter)
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
                        @section('prioriteTache-crud-search-bar')
                        <div id="prioriteTache-crud-search-bar"
                            class="{{ count($prioriteTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('prioriteTaches_search')"
                                name="prioriteTaches_search"
                                id="prioriteTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="prioriteTache-data-container" class="data-container">
                    @if($prioriteTache_viewType == "table")
                    @include("PkgGestionTaches::prioriteTache._$prioriteTache_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="prioriteTache-data-container-out" >
        @if($prioriteTache_viewType == "widgets")
        @include("PkgGestionTaches::prioriteTache._$prioriteTache_viewType")
        @endif
    </section>
    @show
</div>