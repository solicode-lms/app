{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'widgetOperation',
        filterFormSelector: '#widgetOperation-crud-filter-form',
        crudSelector: '#widgetOperation-crud',
        tableSelector: '#widgetOperation-data-container',
        formSelector: '#widgetOperationForm',
        modalSelector : '#widgetOperationModal',
        indexUrl: '{{ route('widgetOperations.index') }}', 
        createUrl: '{{ route('widgetOperations.create') }}',
        editUrl: '{{ route('widgetOperations.edit',  ['widgetOperation' => ':id']) }}',
        showUrl: '{{ route('widgetOperations.show',  ['widgetOperation' => ':id']) }}',
        storeUrl: '{{ route('widgetOperations.store') }}', 
        deleteUrl: '{{ route('widgetOperations.destroy',  ['widgetOperation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
    });
</script>
@endpush
<div id="widgetOperation-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::groupe.singular");
    @endphp
    <x-crud-header 
        id="widgetOperation-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgWidgets::widgetOperation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="widgetOperation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$widgetOperations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-widgetOperation'"
                            :createRoute="route('widgetOperations.create')"
                            :createText="__('Ajouter une widgetOperation')"
                            :importPermission="'import-widgetOperation'"
                            :importRoute="route('widgetOperations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-widgetOperation'"
                            :exportRoute="route('widgetOperations.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="widgetOperation-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($widgetOperations_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
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
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="widgetOperationModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>