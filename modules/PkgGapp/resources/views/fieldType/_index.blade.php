{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'fieldType',
        filterFormSelector: '#fieldType-crud-filter-form',
        crudSelector: '#fieldType-crud',
        tableSelector: '#fieldType-data-container',
        formSelector: '#fieldTypeForm',
        modalSelector : '#fieldTypeModal',
        indexUrl: '{{ route('fieldTypes.index') }}', 
        createUrl: '{{ route('fieldTypes.create') }}',
        editUrl: '{{ route('fieldTypes.edit',  ['fieldType' => ':id']) }}',
        showUrl: '{{ route('fieldTypes.show',  ['fieldType' => ':id']) }}',
        storeUrl: '{{ route('fieldTypes.store') }}', 
        deleteUrl: '{{ route('fieldTypes.destroy',  ['fieldType' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::fieldType.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::fieldType.singular") }}',
    });
</script>
@endpush
<div id="fieldType-crud" class="crud">
    @section('fieldType-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::fieldType.singular");
    @endphp
    <x-crud-header 
        id="fieldType-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::fieldType.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('fieldType-crud-table')
    <section id="fieldType-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('fieldType-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$fieldTypes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-fieldType'"
                            :createRoute="route('fieldTypes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-fieldType'"
                            :importRoute="route('fieldTypes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-fieldType'"
                            :exportRoute="route('fieldTypes.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('fieldType-crud-filters')
                <div class="card-header">
                    <form id="fieldType-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($fieldTypes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('fieldType-crud-search-bar')
                        <div id="fieldType-crud-search-bar"
                            class="{{ count($fieldTypes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('fieldTypes_search')"
                                name="fieldTypes_search"
                                id="fieldTypes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="fieldType-data-container" class="data-container">
                    @include('PkgGapp::fieldType._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('fieldType-crud-modal')
    <x-modal id="fieldTypeModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>