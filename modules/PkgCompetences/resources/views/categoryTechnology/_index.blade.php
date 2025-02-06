{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'categoryTechnology',
        filterFormSelector: '#categoryTechnology-crud-filter-form',
        crudSelector: '#categoryTechnology-crud',
        tableSelector: '#categoryTechnology-data-container',
        formSelector: '#categoryTechnologyForm',
        indexUrl: '{{ route('categoryTechnologies.index') }}', 
        createUrl: '{{ route('categoryTechnologies.create') }}',
        editUrl: '{{ route('categoryTechnologies.edit',  ['categoryTechnology' => ':id']) }}',
        showUrl: '{{ route('categoryTechnologies.show',  ['categoryTechnology' => ':id']) }}',
        storeUrl: '{{ route('categoryTechnologies.store') }}', 
        deleteUrl: '{{ route('categoryTechnologies.destroy',  ['categoryTechnology' => ':id']) }}', 
        calculationUrl:  '{{ route('categoryTechnologies.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
    });
</script>

<div id="categoryTechnology-crud" class="crud">
    @section('categoryTechnology-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::categoryTechnology.singular");
    @endphp
    <x-crud-header 
        id="categoryTechnology-crud-header" icon="fas fa-bolt"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::categoryTechnology.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('categoryTechnology-crud-table')
    <section id="categoryTechnology-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('categoryTechnology-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$categoryTechnologies_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-categoryTechnology'"
                            :createRoute="route('categoryTechnologies.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-categoryTechnology'"
                            :importRoute="route('categoryTechnologies.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-categoryTechnology'"
                            :exportRoute="route('categoryTechnologies.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('categoryTechnology-crud-filters')
                <div class="card-header">
                    <form id="categoryTechnology-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($categoryTechnologies_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($categoryTechnologies_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('categoryTechnology-crud-search-bar')
                        <div id="categoryTechnology-crud-search-bar"
                            class="{{ count($categoryTechnologies_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('categoryTechnologies_search')"
                                name="categoryTechnologies_search"
                                id="categoryTechnologies_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="categoryTechnology-data-container" class="data-container">
                    @include('PkgCompetences::categoryTechnology._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>