{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sysController',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysController.index' }}', 
        filterFormSelector: '#sysController-crud-filter-form',
        crudSelector: '#sysController-crud',
        tableSelector: '#sysController-data-container',
        formSelector: '#sysControllerForm',
        indexUrl: '{{ route('sysControllers.index') }}', 
        createUrl: '{{ route('sysControllers.create') }}',
        editUrl: '{{ route('sysControllers.edit',  ['sysController' => ':id']) }}',
        showUrl: '{{ route('sysControllers.show',  ['sysController' => ':id']) }}',
        storeUrl: '{{ route('sysControllers.store') }}', 
        deleteUrl: '{{ route('sysControllers.destroy',  ['sysController' => ':id']) }}', 
        calculationUrl:  '{{ route('sysControllers.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysController.singular") }}',
    });
</script>

<div id="sysController-crud" class="crud">
    @section('sysController-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysController.singular");
    @endphp
    <x-crud-header 
        id="sysController-crud-header" icon="fas fa-server"  
        iconColor="text-info"
        title="{{ __('Core::sysController.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysController-crud-table')
    <section id="sysController-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysController-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$sysControllers_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-sysController'"
                            :createRoute="route('sysControllers.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-sysController'"
                            :importRoute="route('sysControllers.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-sysController'"
                            :exportXlsxRoute="route('sysControllers.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('sysControllers.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('sysController-crud-filters')
                <div class="card-header">
                    <form id="sysController-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysControllers_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysControllers_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('sysController-crud-search-bar')
                        <div id="sysController-crud-search-bar"
                            class="{{ count($sysControllers_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysControllers_search')"
                                name="sysControllers_search"
                                id="sysControllers_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysController-data-container" class="data-container">
                    @include('Core::sysController._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>