{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'realisationFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationFormation.index' }}', 
        filterFormSelector: '#realisationFormation-crud-filter-form',
        crudSelector: '#realisationFormation-crud',
        tableSelector: '#realisationFormation-data-container',
        formSelector: '#realisationFormationForm',
        indexUrl: '{{ route('realisationFormations.index') }}', 
        createUrl: '{{ route('realisationFormations.create') }}',
        editUrl: '{{ route('realisationFormations.edit',  ['realisationFormation' => ':id']) }}',
        showUrl: '{{ route('realisationFormations.show',  ['realisationFormation' => ':id']) }}',
        storeUrl: '{{ route('realisationFormations.store') }}', 
        deleteUrl: '{{ route('realisationFormations.destroy',  ['realisationFormation' => ':id']) }}', 
        calculationUrl:  '{{ route('realisationFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::realisationFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::realisationFormation.singular") }}',
    });
</script>

<div id="realisationFormation-crud" class="crud">
    @section('realisationFormation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::realisationFormation.singular");
    @endphp
    <x-crud-header 
        id="realisationFormation-crud-header" icon="fas fa-book-open"  
        iconColor="text-info"
        title="{{ __('PkgAutoformation::realisationFormation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationFormation-crud-table')
    <section id="realisationFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationFormation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$realisationFormations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-realisationFormation','import-realisationFormation','export-realisationFormation'])
                        <x-crud-actions
                            :instanceItem="$realisationFormation_instance"
                            :createPermission="'create-realisationFormation'"
                            :createRoute="route('realisationFormations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-realisationFormation'"
                            :importRoute="route('realisationFormations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-realisationFormation'"
                            :exportXlsxRoute="route('realisationFormations.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('realisationFormations.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('realisationFormation-crud-filters')
                <div class="card-header">
                    <form id="realisationFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationFormations_filters as $filter)
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
                        @section('realisationFormation-crud-search-bar')
                        <div id="realisationFormation-crud-search-bar"
                            class="{{ count($realisationFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationFormations_search')"
                                name="realisationFormations_search"
                                id="realisationFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="realisationFormation-data-container" class="data-container">
                    @include('PkgAutoformation::realisationFormation._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>