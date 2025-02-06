{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'niveauDifficulte',
        filterFormSelector: '#niveauDifficulte-crud-filter-form',
        crudSelector: '#niveauDifficulte-crud',
        tableSelector: '#niveauDifficulte-data-container',
        formSelector: '#niveauDifficulteForm',
        indexUrl: '{{ route('niveauDifficultes.index') }}', 
        createUrl: '{{ route('niveauDifficultes.create') }}',
        editUrl: '{{ route('niveauDifficultes.edit',  ['niveauDifficulte' => ':id']) }}',
        showUrl: '{{ route('niveauDifficultes.show',  ['niveauDifficulte' => ':id']) }}',
        storeUrl: '{{ route('niveauDifficultes.store') }}', 
        deleteUrl: '{{ route('niveauDifficultes.destroy',  ['niveauDifficulte' => ':id']) }}', 
        calculationUrl:  '{{ route('niveauDifficultes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauDifficulte.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::niveauDifficulte.singular") }}',
    });
</script>

<div id="niveauDifficulte-crud" class="crud">
    @section('niveauDifficulte-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::niveauDifficulte.singular");
    @endphp
    <x-crud-header 
        id="niveauDifficulte-crud-header" icon="fas fa-chart-line"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::niveauDifficulte.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauDifficulte-crud-table')
    <section id="niveauDifficulte-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauDifficulte-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$niveauDifficultes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-niveauDifficulte'"
                            :createRoute="route('niveauDifficultes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-niveauDifficulte'"
                            :importRoute="route('niveauDifficultes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-niveauDifficulte'"
                            :exportRoute="route('niveauDifficultes.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('niveauDifficulte-crud-filters')
                <div class="card-header">
                    <form id="niveauDifficulte-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauDifficultes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauDifficultes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('niveauDifficulte-crud-search-bar')
                        <div id="niveauDifficulte-crud-search-bar"
                            class="{{ count($niveauDifficultes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauDifficultes_search')"
                                name="niveauDifficultes_search"
                                id="niveauDifficultes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="niveauDifficulte-data-container" class="data-container">
                    @include('PkgCompetences::niveauDifficulte._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>