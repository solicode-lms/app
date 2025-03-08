{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'etatsRealisationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatsRealisationProjet.index' }}', 
        filterFormSelector: '#etatsRealisationProjet-crud-filter-form',
        crudSelector: '#etatsRealisationProjet-crud',
        tableSelector: '#etatsRealisationProjet-data-container',
        formSelector: '#etatsRealisationProjetForm',
        indexUrl: '{{ route('etatsRealisationProjets.index') }}', 
        createUrl: '{{ route('etatsRealisationProjets.create') }}',
        editUrl: '{{ route('etatsRealisationProjets.edit',  ['etatsRealisationProjet' => ':id']) }}',
        showUrl: '{{ route('etatsRealisationProjets.show',  ['etatsRealisationProjet' => ':id']) }}',
        storeUrl: '{{ route('etatsRealisationProjets.store') }}', 
        deleteUrl: '{{ route('etatsRealisationProjets.destroy',  ['etatsRealisationProjet' => ':id']) }}', 
        calculationUrl:  '{{ route('etatsRealisationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::etatsRealisationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::etatsRealisationProjet.singular") }}',
    });
</script>

<div id="etatsRealisationProjet-crud" class="crud">
    @section('etatsRealisationProjet-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::etatsRealisationProjet.singular");
    @endphp
    <x-crud-header 
        id="etatsRealisationProjet-crud-header" icon="fas fa-star-half"  
        iconColor="text-info"
        title="{{ __('PkgRealisationProjets::etatsRealisationProjet.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatsRealisationProjet-crud-table')
    <section id="etatsRealisationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatsRealisationProjet-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$etatsRealisationProjets_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-etatsRealisationProjet','import-etatsRealisationProjet','export-etatsRealisationProjet'])
                        <x-crud-actions
                            :instanceItem="$etatsRealisationProjet_instance"
                            :createPermission="'create-etatsRealisationProjet'"
                            :createRoute="route('etatsRealisationProjets.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-etatsRealisationProjet'"
                            :importRoute="route('etatsRealisationProjets.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-etatsRealisationProjet'"
                            :exportXlsxRoute="route('etatsRealisationProjets.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('etatsRealisationProjets.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('etatsRealisationProjet-crud-filters')
                <div class="card-header">
                    <form id="etatsRealisationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatsRealisationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatsRealisationProjets_filters as $filter)
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
                        @section('etatsRealisationProjet-crud-search-bar')
                        <div id="etatsRealisationProjet-crud-search-bar"
                            class="{{ count($etatsRealisationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatsRealisationProjets_search')"
                                name="etatsRealisationProjets_search"
                                id="etatsRealisationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="etatsRealisationProjet-data-container" class="data-container">
                    @include('PkgRealisationProjets::etatsRealisationProjet._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>