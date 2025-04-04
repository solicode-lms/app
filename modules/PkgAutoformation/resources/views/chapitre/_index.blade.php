{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'chapitre',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'chapitre.index' }}', 
        filterFormSelector: '#chapitre-crud-filter-form',
        crudSelector: '#chapitre-crud',
        tableSelector: '#chapitre-data-container',
        formSelector: '#chapitreForm',
        indexUrl: '{{ route('chapitres.index') }}', 
        createUrl: '{{ route('chapitres.create') }}',
        editUrl: '{{ route('chapitres.edit',  ['chapitre' => ':id']) }}',
        showUrl: '{{ route('chapitres.show',  ['chapitre' => ':id']) }}',
        storeUrl: '{{ route('chapitres.store') }}', 
        deleteUrl: '{{ route('chapitres.destroy',  ['chapitre' => ':id']) }}', 
        calculationUrl:  '{{ route('chapitres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::chapitre.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::chapitre.singular") }}',
    });
</script>

<div id="chapitre-crud" class="crud">
    @section('chapitre-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::chapitre.singular");
    @endphp
    <x-crud-header 
        id="chapitre-crud-header" icon="fas fa-chalkboard"  
        iconColor="text-info"
        title="{{ __('PkgAutoformation::chapitre.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('chapitre-crud-table')
    <section id="chapitre-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('chapitre-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$chapitres_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-chapitre','import-chapitre','export-chapitre'])
                        <x-crud-actions
                            :instanceItem="$chapitre_instance"
                            :createPermission="'create-chapitre'"
                            :createRoute="route('chapitres.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-chapitre'"
                            :importRoute="route('chapitres.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-chapitre'"
                            :exportXlsxRoute="route('chapitres.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('chapitres.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('chapitre-crud-filters')
                <div class="card-header">
                    <form id="chapitre-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($chapitres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($chapitres_filters as $filter)
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
                        @section('chapitre-crud-search-bar')
                        <div id="chapitre-crud-search-bar"
                            class="{{ count($chapitres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('chapitres_search')"
                                name="chapitres_search"
                                id="chapitres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="chapitre-data-container" class="data-container">
                    @include('PkgAutoformation::chapitre._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>