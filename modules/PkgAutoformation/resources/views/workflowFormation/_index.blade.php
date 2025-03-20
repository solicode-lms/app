{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'workflowFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'workflowFormation.index' }}', 
        filterFormSelector: '#workflowFormation-crud-filter-form',
        crudSelector: '#workflowFormation-crud',
        tableSelector: '#workflowFormation-data-container',
        formSelector: '#workflowFormationForm',
        indexUrl: '{{ route('workflowFormations.index') }}', 
        createUrl: '{{ route('workflowFormations.create') }}',
        editUrl: '{{ route('workflowFormations.edit',  ['workflowFormation' => ':id']) }}',
        showUrl: '{{ route('workflowFormations.show',  ['workflowFormation' => ':id']) }}',
        storeUrl: '{{ route('workflowFormations.store') }}', 
        deleteUrl: '{{ route('workflowFormations.destroy',  ['workflowFormation' => ':id']) }}', 
        calculationUrl:  '{{ route('workflowFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::workflowFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::workflowFormation.singular") }}',
    });
</script>

<div id="workflowFormation-crud" class="crud">
    @section('workflowFormation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::workflowFormation.singular");
    @endphp
    <x-crud-header 
        id="workflowFormation-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ __('PkgAutoformation::workflowFormation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('workflowFormation-crud-table')
    <section id="workflowFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('workflowFormation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$workflowFormations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-workflowFormation','import-workflowFormation','export-workflowFormation'])
                        <x-crud-actions
                            :instanceItem="$workflowFormation_instance"
                            :createPermission="'create-workflowFormation'"
                            :createRoute="route('workflowFormations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-workflowFormation'"
                            :importRoute="route('workflowFormations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-workflowFormation'"
                            :exportXlsxRoute="route('workflowFormations.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('workflowFormations.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('workflowFormation-crud-filters')
                <div class="card-header">
                    <form id="workflowFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($workflowFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($workflowFormations_filters as $filter)
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
                        @section('workflowFormation-crud-search-bar')
                        <div id="workflowFormation-crud-search-bar"
                            class="{{ count($workflowFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('workflowFormations_search')"
                                name="workflowFormations_search"
                                id="workflowFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="workflowFormation-data-container" class="data-container">
                    @include('PkgAutoformation::workflowFormation._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>