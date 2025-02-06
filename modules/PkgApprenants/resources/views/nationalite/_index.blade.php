{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'nationalite',
        filterFormSelector: '#nationalite-crud-filter-form',
        crudSelector: '#nationalite-crud',
        tableSelector: '#nationalite-data-container',
        formSelector: '#nationaliteForm',
        indexUrl: '{{ route('nationalites.index') }}', 
        createUrl: '{{ route('nationalites.create') }}',
        editUrl: '{{ route('nationalites.edit',  ['nationalite' => ':id']) }}',
        showUrl: '{{ route('nationalites.show',  ['nationalite' => ':id']) }}',
        storeUrl: '{{ route('nationalites.store') }}', 
        deleteUrl: '{{ route('nationalites.destroy',  ['nationalite' => ':id']) }}', 
        calculationUrl:  '{{ route('nationalites.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::nationalite.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::nationalite.singular") }}',
    });
</script>

<div id="nationalite-crud" class="crud">
    @section('nationalite-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::nationalite.singular");
    @endphp
    <x-crud-header 
        id="nationalite-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgApprenants::nationalite.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('nationalite-crud-table')
    <section id="nationalite-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('nationalite-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$nationalites_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-nationalite'"
                            :createRoute="route('nationalites.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-nationalite'"
                            :importRoute="route('nationalites.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-nationalite'"
                            :exportRoute="route('nationalites.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('nationalite-crud-filters')
                <div class="card-header">
                    <form id="nationalite-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($nationalites_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($nationalites_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('nationalite-crud-search-bar')
                        <div id="nationalite-crud-search-bar"
                            class="{{ count($nationalites_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('nationalites_search')"
                                name="nationalites_search"
                                id="nationalites_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="nationalite-data-container" class="data-container">
                    @include('PkgApprenants::nationalite._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>