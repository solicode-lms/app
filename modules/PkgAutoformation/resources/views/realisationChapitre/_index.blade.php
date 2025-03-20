{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'realisationChapitre',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationChapitre.index' }}', 
        filterFormSelector: '#realisationChapitre-crud-filter-form',
        crudSelector: '#realisationChapitre-crud',
        tableSelector: '#realisationChapitre-data-container',
        formSelector: '#realisationChapitreForm',
        indexUrl: '{{ route('realisationChapitres.index') }}', 
        createUrl: '{{ route('realisationChapitres.create') }}',
        editUrl: '{{ route('realisationChapitres.edit',  ['realisationChapitre' => ':id']) }}',
        showUrl: '{{ route('realisationChapitres.show',  ['realisationChapitre' => ':id']) }}',
        storeUrl: '{{ route('realisationChapitres.store') }}', 
        deleteUrl: '{{ route('realisationChapitres.destroy',  ['realisationChapitre' => ':id']) }}', 
        calculationUrl:  '{{ route('realisationChapitres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::realisationChapitre.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::realisationChapitre.singular") }}',
    });
</script>

<div id="realisationChapitre-crud" class="crud">
    @section('realisationChapitre-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::realisationChapitre.singular");
    @endphp
    <x-crud-header 
        id="realisationChapitre-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgAutoformation::realisationChapitre.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationChapitre-crud-table')
    <section id="realisationChapitre-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationChapitre-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$realisationChapitres_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-realisationChapitre','import-realisationChapitre','export-realisationChapitre'])
                        <x-crud-actions
                            :instanceItem="$realisationChapitre_instance"
                            :createPermission="'create-realisationChapitre'"
                            :createRoute="route('realisationChapitres.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-realisationChapitre'"
                            :importRoute="route('realisationChapitres.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-realisationChapitre'"
                            :exportXlsxRoute="route('realisationChapitres.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('realisationChapitres.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('realisationChapitre-crud-filters')
                <div class="card-header">
                    <form id="realisationChapitre-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationChapitres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationChapitres_filters as $filter)
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
                        @section('realisationChapitre-crud-search-bar')
                        <div id="realisationChapitre-crud-search-bar"
                            class="{{ count($realisationChapitres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationChapitres_search')"
                                name="realisationChapitres_search"
                                id="realisationChapitres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="realisationChapitre-data-container" class="data-container">
                    @include('PkgAutoformation::realisationChapitre._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>