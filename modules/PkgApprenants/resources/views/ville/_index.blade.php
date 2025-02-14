{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'ville',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'ville.index' }}', 
        filterFormSelector: '#ville-crud-filter-form',
        crudSelector: '#ville-crud',
        tableSelector: '#ville-data-container',
        formSelector: '#villeForm',
        indexUrl: '{{ route('villes.index') }}', 
        createUrl: '{{ route('villes.create') }}',
        editUrl: '{{ route('villes.edit',  ['ville' => ':id']) }}',
        showUrl: '{{ route('villes.show',  ['ville' => ':id']) }}',
        storeUrl: '{{ route('villes.store') }}', 
        deleteUrl: '{{ route('villes.destroy',  ['ville' => ':id']) }}', 
        calculationUrl:  '{{ route('villes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::ville.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::ville.singular") }}',
    });
</script>

<div id="ville-crud" class="crud">
    @section('ville-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::ville.singular");
    @endphp
    <x-crud-header 
        id="ville-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgApprenants::ville.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('ville-crud-table')
    <section id="ville-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('ville-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$villes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-ville'"
                            :createRoute="route('villes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-ville'"
                            :importRoute="route('villes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-ville'"
                            :exportXlsxRoute="route('villes.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('villes.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('ville-crud-filters')
                <div class="card-header">
                    <form id="ville-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($villes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($villes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('ville-crud-search-bar')
                        <div id="ville-crud-search-bar"
                            class="{{ count($villes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('villes_search')"
                                name="villes_search"
                                id="villes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="ville-data-container" class="data-container">
                    @include('PkgApprenants::ville._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>