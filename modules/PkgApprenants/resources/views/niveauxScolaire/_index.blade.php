{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'niveauxScolaire',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'niveauxScolaire.index' }}', 
        filterFormSelector: '#niveauxScolaire-crud-filter-form',
        crudSelector: '#niveauxScolaire-crud',
        tableSelector: '#niveauxScolaire-data-container',
        formSelector: '#niveauxScolaireForm',
        indexUrl: '{{ route('niveauxScolaires.index') }}', 
        createUrl: '{{ route('niveauxScolaires.create') }}',
        editUrl: '{{ route('niveauxScolaires.edit',  ['niveauxScolaire' => ':id']) }}',
        showUrl: '{{ route('niveauxScolaires.show',  ['niveauxScolaire' => ':id']) }}',
        storeUrl: '{{ route('niveauxScolaires.store') }}', 
        deleteUrl: '{{ route('niveauxScolaires.destroy',  ['niveauxScolaire' => ':id']) }}', 
        calculationUrl:  '{{ route('niveauxScolaires.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::niveauxScolaire.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::niveauxScolaire.singular") }}',
    });
</script>

<div id="niveauxScolaire-crud" class="crud">
    @section('niveauxScolaire-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::niveauxScolaire.singular");
    @endphp
    <x-crud-header 
        id="niveauxScolaire-crud-header" icon="fas fa-graduation-cap"  
        iconColor="text-info"
        title="{{ __('PkgApprenants::niveauxScolaire.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauxScolaire-crud-table')
    <section id="niveauxScolaire-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauxScolaire-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$niveauxScolaires_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-niveauxScolaire','import-niveauxScolaire','export-niveauxScolaire'])
                        <x-crud-actions
                            :createPermission="'create-niveauxScolaire'"
                            :createRoute="route('niveauxScolaires.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-niveauxScolaire'"
                            :importRoute="route('niveauxScolaires.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-niveauxScolaire'"
                            :exportXlsxRoute="route('niveauxScolaires.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('niveauxScolaires.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('niveauxScolaire-crud-filters')
                <div class="card-header">
                    <form id="niveauxScolaire-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauxScolaires_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauxScolaires_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('niveauxScolaire-crud-search-bar')
                        <div id="niveauxScolaire-crud-search-bar"
                            class="{{ count($niveauxScolaires_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauxScolaires_search')"
                                name="niveauxScolaires_search"
                                id="niveauxScolaires_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="niveauxScolaire-data-container" class="data-container">
                    @include('PkgApprenants::niveauxScolaire._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>