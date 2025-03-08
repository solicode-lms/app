{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'module',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'module.index' }}', 
        filterFormSelector: '#module-crud-filter-form',
        crudSelector: '#module-crud',
        tableSelector: '#module-data-container',
        formSelector: '#moduleForm',
        indexUrl: '{{ route('modules.index') }}', 
        createUrl: '{{ route('modules.create') }}',
        editUrl: '{{ route('modules.edit',  ['module' => ':id']) }}',
        showUrl: '{{ route('modules.show',  ['module' => ':id']) }}',
        storeUrl: '{{ route('modules.store') }}', 
        deleteUrl: '{{ route('modules.destroy',  ['module' => ':id']) }}', 
        calculationUrl:  '{{ route('modules.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::module.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::module.singular") }}',
    });
</script>

<div id="module-crud" class="crud">
    @section('module-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::module.singular");
    @endphp
    <x-crud-header 
        id="module-crud-header" icon="fas fa-puzzle-piece"  
        iconColor="text-info"
        title="{{ __('PkgFormation::module.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('module-crud-table')
    <section id="module-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('module-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$modules_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-module','import-module','export-module'])
                        <x-crud-actions
                            :instanceItem="$module_instance"
                            :createPermission="'create-module'"
                            :createRoute="route('modules.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-module'"
                            :importRoute="route('modules.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-module'"
                            :exportXlsxRoute="route('modules.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('modules.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('module-crud-filters')
                <div class="card-header">
                    <form id="module-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($modules_filters as $filter)
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
                        @section('module-crud-search-bar')
                        <div id="module-crud-search-bar"
                            class="{{ count($modules_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('modules_search')"
                                name="modules_search"
                                id="modules_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="module-data-container" class="data-container">
                    @include('PkgFormation::module._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>