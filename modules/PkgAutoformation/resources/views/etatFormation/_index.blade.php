{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'etatFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatFormation.index' }}', 
        filterFormSelector: '#etatFormation-crud-filter-form',
        crudSelector: '#etatFormation-crud',
        tableSelector: '#etatFormation-data-container',
        formSelector: '#etatFormationForm',
        indexUrl: '{{ route('etatFormations.index') }}', 
        createUrl: '{{ route('etatFormations.create') }}',
        editUrl: '{{ route('etatFormations.edit',  ['etatFormation' => ':id']) }}',
        showUrl: '{{ route('etatFormations.show',  ['etatFormation' => ':id']) }}',
        storeUrl: '{{ route('etatFormations.store') }}', 
        deleteUrl: '{{ route('etatFormations.destroy',  ['etatFormation' => ':id']) }}', 
        calculationUrl:  '{{ route('etatFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::etatFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::etatFormation.singular") }}',
    });
</script>

<div id="etatFormation-crud" class="crud">
    @section('etatFormation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::etatFormation.singular");
    @endphp
    <x-crud-header 
        id="etatFormation-crud-header" icon="fas fa-check"  
        iconColor="text-info"
        title="{{ __('PkgAutoformation::etatFormation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatFormation-crud-table')
    <section id="etatFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatFormation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$etatFormations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-etatFormation','import-etatFormation','export-etatFormation'])
                        <x-crud-actions
                            :instanceItem="$etatFormation_instance"
                            :createPermission="'create-etatFormation'"
                            :createRoute="route('etatFormations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-etatFormation'"
                            :importRoute="route('etatFormations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-etatFormation'"
                            :exportXlsxRoute="route('etatFormations.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('etatFormations.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('etatFormation-crud-filters')
                <div class="card-header">
                    <form id="etatFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatFormations_filters as $filter)
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
                        @section('etatFormation-crud-search-bar')
                        <div id="etatFormation-crud-search-bar"
                            class="{{ count($etatFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatFormations_search')"
                                name="etatFormations_search"
                                id="etatFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="etatFormation-data-container" class="data-container">
                    @include('PkgAutoformation::etatFormation._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>