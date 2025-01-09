{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'dataField',
        filterFormSelector: '#dataField-crud-filter-form',
        crudSelector: '#dataField-crud',
        tableSelector: '#dataField-data-container',
        formSelector: '#dataFieldForm',
        modalSelector : '#dataFieldModal',
        indexUrl: '{{ route('dataFields.index') }}', 
        createUrl: '{{ route('dataFields.create') }}',
        editUrl: '{{ route('dataFields.edit',  ['dataField' => ':id']) }}',
        showUrl: '{{ route('dataFields.show',  ['dataField' => ':id']) }}',
        storeUrl: '{{ route('dataFields.store') }}', 
        deleteUrl: '{{ route('dataFields.destroy',  ['dataField' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::dataField.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::dataField.singular") }}',
    });
</script>
@endpush
<div id="dataField-crud" class="crud">
    @section('dataField-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::dataField.singular");
    @endphp
    <x-crud-header 
        id="dataField-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::dataField.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('dataField-crud-table')
    <section id="dataField-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('dataField-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$dataFields_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-dataField'"
                            :createRoute="route('dataFields.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-dataField'"
                            :importRoute="route('dataFields.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-dataField'"
                            :exportRoute="route('dataFields.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('dataField-crud-filters')
                <div class="card-header">
                    <form id="dataField-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($dataFields_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('dataField-crud-search-bar')
                        <div id="dataField-crud-search-bar"
                            class="{{ count($dataFields_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('dataFields_search')"
                                name="dataFields_search"
                                id="dataFields_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="dataField-data-container" class="data-container">
                    @include('PkgGapp::dataField._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('dataField-crud-modal')
    <x-modal id="dataFieldModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>