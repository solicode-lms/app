{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'realisationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationProjet.index' }}', 
        filterFormSelector: '#realisationProjet-crud-filter-form',
        crudSelector: '#realisationProjet-crud',
        tableSelector: '#realisationProjet-data-container',
        formSelector: '#realisationProjetForm',
        indexUrl: '{{ route('realisationProjets.index') }}', 
        createUrl: '{{ route('realisationProjets.create') }}',
        editUrl: '{{ route('realisationProjets.edit',  ['realisationProjet' => ':id']) }}',
        showUrl: '{{ route('realisationProjets.show',  ['realisationProjet' => ':id']) }}',
        storeUrl: '{{ route('realisationProjets.store') }}', 
        deleteUrl: '{{ route('realisationProjets.destroy',  ['realisationProjet' => ':id']) }}', 
        calculationUrl:  '{{ route('realisationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::realisationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::realisationProjet.singular") }}',
    });
</script>

<div id="realisationProjet-crud" class="crud">
    @section('realisationProjet-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::realisationProjet.singular");
    @endphp
    <x-crud-header 
        id="realisationProjet-crud-header" icon="fas fa-coffee"  
        iconColor="text-info"
        title="{{ __('PkgRealisationProjets::realisationProjet.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationProjet-crud-table')
    <section id="realisationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationProjet-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$realisationProjets_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-realisationProjet','import-realisationProjet','export-realisationProjet'])
                        <x-crud-actions
                            :instanceItem="$realisationProjet_instance"
                            :createPermission="'create-realisationProjet'"
                            :createRoute="route('realisationProjets.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-realisationProjet'"
                            :importRoute="route('realisationProjets.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-realisationProjet'"
                            :exportXlsxRoute="route('realisationProjets.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('realisationProjets.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('realisationProjet-crud-filters')
                <div class="card-header">
                    <form id="realisationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationProjets_filters as $filter)
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
                        @section('realisationProjet-crud-search-bar')
                        <div id="realisationProjet-crud-search-bar"
                            class="{{ count($realisationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationProjets_search')"
                                name="realisationProjets_search"
                                id="realisationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="realisationProjet-data-container" class="data-container">
                    @include('PkgRealisationProjets::realisationProjet._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>