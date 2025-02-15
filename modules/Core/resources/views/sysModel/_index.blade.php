{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sysModel',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysModel.index' }}', 
        filterFormSelector: '#sysModel-crud-filter-form',
        crudSelector: '#sysModel-crud',
        tableSelector: '#sysModel-data-container',
        formSelector: '#sysModelForm',
        indexUrl: '{{ route('sysModels.index') }}', 
        createUrl: '{{ route('sysModels.create') }}',
        editUrl: '{{ route('sysModels.edit',  ['sysModel' => ':id']) }}',
        showUrl: '{{ route('sysModels.show',  ['sysModel' => ':id']) }}',
        storeUrl: '{{ route('sysModels.store') }}', 
        deleteUrl: '{{ route('sysModels.destroy',  ['sysModel' => ':id']) }}', 
        calculationUrl:  '{{ route('sysModels.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysModel.singular") }}',
    });
</script>

<div id="sysModel-crud" class="crud">
    @section('sysModel-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysModel.singular");
    @endphp
    <x-crud-header 
        id="sysModel-crud-header" icon="fas fa-cubes"  
        iconColor="text-info"
        title="{{ __('Core::sysModel.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysModel-crud-table')
    <section id="sysModel-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysModel-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$sysModels_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-sysModel'"
                            :createRoute="route('sysModels.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-sysModel'"
                            :importRoute="route('sysModels.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-sysModel'"
                            :exportXlsxRoute="route('sysModels.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('sysModels.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('sysModel-crud-filters')
                <div class="card-header">
                    <form id="sysModel-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysModels_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysModels_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('sysModel-crud-search-bar')
                        <div id="sysModel-crud-search-bar"
                            class="{{ count($sysModels_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysModels_search')"
                                name="sysModels_search"
                                id="sysModels_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysModel-data-container" class="data-container">
                    @include('Core::sysModel._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>