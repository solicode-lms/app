{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'widgetOperation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'widgetOperation.index' }}', 
        filterFormSelector: '#widgetOperation-crud-filter-form',
        crudSelector: '#widgetOperation-crud',
        tableSelector: '#widgetOperation-data-container',
        formSelector: '#widgetOperationForm',
        indexUrl: '{{ route('widgetOperations.index') }}', 
        createUrl: '{{ route('widgetOperations.create') }}',
        editUrl: '{{ route('widgetOperations.edit',  ['widgetOperation' => ':id']) }}',
        showUrl: '{{ route('widgetOperations.show',  ['widgetOperation' => ':id']) }}',
        storeUrl: '{{ route('widgetOperations.store') }}', 
        deleteUrl: '{{ route('widgetOperations.destroy',  ['widgetOperation' => ':id']) }}', 
        calculationUrl:  '{{ route('widgetOperations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
    });
</script>

<div id="widgetOperation-crud" class="crud">
    @section('widgetOperation-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widgetOperation.singular");
    @endphp
    <x-crud-header 
        id="widgetOperation-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgWidgets::widgetOperation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widgetOperation-crud-table')
    <section id="widgetOperation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widgetOperation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$widgetOperations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-widgetOperation','import-widgetOperation','export-widgetOperation'])
                        <x-crud-actions
                            :createPermission="'create-widgetOperation'"
                            :createRoute="route('widgetOperations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-widgetOperation'"
                            :importRoute="route('widgetOperations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-widgetOperation'"
                            :exportXlsxRoute="route('widgetOperations.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('widgetOperations.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('widgetOperation-crud-filters')
                <div class="card-header">
                    <form id="widgetOperation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($widgetOperations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($widgetOperations_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('widgetOperation-crud-search-bar')
                        <div id="widgetOperation-crud-search-bar"
                            class="{{ count($widgetOperations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgetOperations_search')"
                                name="widgetOperations_search"
                                id="widgetOperations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="widgetOperation-data-container" class="data-container">
                    @include('PkgWidgets::widgetOperation._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>