{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'widgetType',
        filterFormSelector: '#widgetType-crud-filter-form',
        crudSelector: '#widgetType-crud',
        tableSelector: '#widgetType-data-container',
        formSelector: '#widgetTypeForm',
        modalSelector : '#widgetTypeModal',
        indexUrl: '{{ route('widgetTypes.index') }}', 
        createUrl: '{{ route('widgetTypes.create') }}',
        editUrl: '{{ route('widgetTypes.edit',  ['widgetType' => ':id']) }}',
        showUrl: '{{ route('widgetTypes.show',  ['widgetType' => ':id']) }}',
        storeUrl: '{{ route('widgetTypes.store') }}', 
        deleteUrl: '{{ route('widgetTypes.destroy',  ['widgetType' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',
    });
</script>
@endpush
<div id="widgetType-crud" class="crud">
    @section('widgetType-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widgetType.singular");
    @endphp
    <x-crud-header 
        id="widgetType-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgWidgets::widgetType.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widgetType-crud-table')
    <section id="widgetType-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widgetType-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$widgetTypes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-widgetType'"
                            :createRoute="route('widgetTypes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-widgetType'"
                            :importRoute="route('widgetTypes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-widgetType'"
                            :exportRoute="route('widgetTypes.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('widgetType-crud-filters')
                <div class="card-header">
                    <form id="widgetType-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($widgetTypes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($widgetTypes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('widgetType-crud-search-bar')
                        <div id="widgetType-crud-search-bar"
                            class="{{ count($widgetTypes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgetTypes_search')"
                                name="widgetTypes_search"
                                id="widgetTypes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="widgetType-data-container" class="data-container">
                    @include('PkgWidgets::widgetType._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('widgetType-crud-modal')
    <x-modal id="widgetTypeModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>