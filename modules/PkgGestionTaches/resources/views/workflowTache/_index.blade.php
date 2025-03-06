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
        deleteUrl: '{{ route('workflowTaches.destroy',  ['workflowTache' => ':id']) }}', 
        calculationUrl:  '{{ route('workflowTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::workflowTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::workflowTache.singular") }}',
    });
</script>

<div id="workflowTache-crud" class="crud">
    @section('workflowTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::workflowTache.singular");
    @endphp
    <x-crud-header 
        id="workflowTache-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::workflowTache.plural') }}"
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
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$workflowTaches_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-workflowTache','import-workflowTache','export-workflowTache'])
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
                        />
                        @endcan
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
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
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
                    @include('PkgGestionTaches::workflowTache._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>