{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'specialite',
        filterFormSelector: '#specialite-crud-filter-form',
        crudSelector: '#specialite-crud',
        tableSelector: '#specialite-data-container',
        formSelector: '#specialiteForm',
        indexUrl: '{{ route('specialites.index') }}', 
        createUrl: '{{ route('specialites.create') }}',
        editUrl: '{{ route('specialites.edit',  ['specialite' => ':id']) }}',
        showUrl: '{{ route('specialites.show',  ['specialite' => ':id']) }}',
        storeUrl: '{{ route('specialites.store') }}', 
        deleteUrl: '{{ route('specialites.destroy',  ['specialite' => ':id']) }}', 
        calculationUrl:  '{{ route('specialites.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::specialite.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::specialite.singular") }}',
    });
</script>

<div id="specialite-crud" class="crud">
    @section('specialite-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::specialite.singular");
    @endphp
    <x-crud-header 
        id="specialite-crud-header" icon="fas fa-award"  
        iconColor="text-info"
        title="{{ __('PkgFormation::specialite.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('specialite-crud-table')
    <section id="specialite-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('specialite-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$specialites_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-specialite'"
                            :createRoute="route('specialites.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-specialite'"
                            :importRoute="route('specialites.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-specialite'"
                            :exportXlsxRoute="route('specialites.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('specialites.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('specialite-crud-filters')
                <div class="card-header">
                    <form id="specialite-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($specialites_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($specialites_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('specialite-crud-search-bar')
                        <div id="specialite-crud-search-bar"
                            class="{{ count($specialites_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('specialites_search')"
                                name="specialites_search"
                                id="specialites_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="specialite-data-container" class="data-container">
                    @include('PkgFormation::specialite._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>