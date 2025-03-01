{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'tache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'tache.index' }}', 
        filterFormSelector: '#tache-crud-filter-form',
        crudSelector: '#tache-crud',
        tableSelector: '#tache-data-container',
        formSelector: '#tacheForm',
        indexUrl: '{{ route('taches.index') }}', 
        createUrl: '{{ route('taches.create') }}',
        editUrl: '{{ route('taches.edit',  ['tache' => ':id']) }}',
        showUrl: '{{ route('taches.show',  ['tache' => ':id']) }}',
        storeUrl: '{{ route('taches.store') }}', 
        deleteUrl: '{{ route('taches.destroy',  ['tache' => ':id']) }}', 
        calculationUrl:  '{{ route('taches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::tache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::tache.singular") }}',
    });
</script>

<div id="tache-crud" class="crud">
    @section('tache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::tache.singular");
    @endphp
    <x-crud-header 
        id="tache-crud-header" icon="fas fa-clipboard-list"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::tache.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('tache-crud-table')
    <section id="tache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('tache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$taches_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-tache','import-tache','export-tache'])
                        <x-crud-actions
                            :instanceItem="$tache_instance"
                            :createPermission="'create-tache'"
                            :createRoute="route('taches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-tache'"
                            :importRoute="route('taches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-tache'"
                            :exportXlsxRoute="route('taches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('taches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('tache-crud-filters')
                <div class="card-header">
                    <form id="tache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($taches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($taches_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('tache-crud-search-bar')
                        <div id="tache-crud-search-bar"
                            class="{{ count($taches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('taches_search')"
                                name="taches_search"
                                id="taches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="tache-data-container" class="data-container">
                    @include('PkgGestionTaches::tache._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>