{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'anneeFormation',
        filterFormSelector: '#anneeFormation-crud-filter-form',
        crudSelector: '#anneeFormation-crud',
        tableSelector: '#anneeFormation-data-container',
        formSelector: '#anneeFormationForm',
        indexUrl: '{{ route('anneeFormations.index') }}', 
        createUrl: '{{ route('anneeFormations.create') }}',
        editUrl: '{{ route('anneeFormations.edit',  ['anneeFormation' => ':id']) }}',
        showUrl: '{{ route('anneeFormations.show',  ['anneeFormation' => ':id']) }}',
        storeUrl: '{{ route('anneeFormations.store') }}', 
        deleteUrl: '{{ route('anneeFormations.destroy',  ['anneeFormation' => ':id']) }}', 
        calculationUrl:  '{{ route('anneeFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::anneeFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::anneeFormation.singular") }}',
    });
</script>

<div id="anneeFormation-crud" class="crud">
    @section('anneeFormation-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::anneeFormation.singular");
    @endphp
    <x-crud-header 
        id="anneeFormation-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgFormation::anneeFormation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('anneeFormation-crud-table')
    <section id="anneeFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('anneeFormation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$anneeFormations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-anneeFormation'"
                            :createRoute="route('anneeFormations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-anneeFormation'"
                            :importRoute="route('anneeFormations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-anneeFormation'"
                            :exportRoute="route('anneeFormations.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('anneeFormation-crud-filters')
                <div class="card-header">
                    <form id="anneeFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($anneeFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($anneeFormations_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('anneeFormation-crud-search-bar')
                        <div id="anneeFormation-crud-search-bar"
                            class="{{ count($anneeFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('anneeFormations_search')"
                                name="anneeFormations_search"
                                id="anneeFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="anneeFormation-data-container" class="data-container">
                    @include('PkgFormation::anneeFormation._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>