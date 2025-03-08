{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'resource',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'resource.index' }}', 
        filterFormSelector: '#resource-crud-filter-form',
        crudSelector: '#resource-crud',
        tableSelector: '#resource-data-container',
        formSelector: '#resourceForm',
        indexUrl: '{{ route('resources.index') }}', 
        createUrl: '{{ route('resources.create') }}',
        editUrl: '{{ route('resources.edit',  ['resource' => ':id']) }}',
        showUrl: '{{ route('resources.show',  ['resource' => ':id']) }}',
        storeUrl: '{{ route('resources.store') }}', 
        deleteUrl: '{{ route('resources.destroy',  ['resource' => ':id']) }}', 
        calculationUrl:  '{{ route('resources.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::resource.singular") }}',
    });
</script>

<div id="resource-crud" class="crud">
    @section('resource-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::resource.singular");
    @endphp
    <x-crud-header 
        id="resource-crud-header" icon="fas fa-book"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::resource.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('resource-crud-table')
    <section id="resource-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('resource-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$resources_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-resource','import-resource','export-resource'])
                        <x-crud-actions
                            :instanceItem="$resource_instance"
                            :createPermission="'create-resource'"
                            :createRoute="route('resources.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-resource'"
                            :importRoute="route('resources.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-resource'"
                            :exportXlsxRoute="route('resources.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('resources.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('resource-crud-filters')
                <div class="card-header">
                    <form id="resource-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($resources_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($resources_filters as $filter)
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
                        @section('resource-crud-search-bar')
                        <div id="resource-crud-search-bar"
                            class="{{ count($resources_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('resources_search')"
                                name="resources_search"
                                id="resources_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="resource-data-container" class="data-container">
                    @include('PkgCreationProjet::resource._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>