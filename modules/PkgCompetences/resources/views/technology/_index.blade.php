{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'technology',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'technology.index' }}', 
        filterFormSelector: '#technology-crud-filter-form',
        crudSelector: '#technology-crud',
        tableSelector: '#technology-data-container',
        formSelector: '#technologyForm',
        indexUrl: '{{ route('technologies.index') }}', 
        createUrl: '{{ route('technologies.create') }}',
        editUrl: '{{ route('technologies.edit',  ['technology' => ':id']) }}',
        showUrl: '{{ route('technologies.show',  ['technology' => ':id']) }}',
        storeUrl: '{{ route('technologies.store') }}', 
        updateAttributesUrl: '{{ route('technologies.updateAttributes') }}', 
        deleteUrl: '{{ route('technologies.destroy',  ['technology' => ':id']) }}', 
        calculationUrl:  '{{ route('technologies.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::technology.singular") }}',
    });
</script>

<div id="technology-crud" class="crud">
    @section('technology-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::technology.singular");
    @endphp
    <x-crud-header 
        id="technology-crud-header" icon="fas fa-tag"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::technology.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('technology-crud-table')
    <section id="technology-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('technology-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$technologies_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-technology','import-technology','export-technology'])
                        <x-crud-actions
                            :instanceItem="$technology_instance"
                            :createPermission="'create-technology'"
                            :createRoute="route('technologies.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-technology'"
                            :importRoute="route('technologies.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-technology'"
                            :exportXlsxRoute="route('technologies.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('technologies.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$viewTypes"
                            :viewType="$viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('technology-crud-filters')
                <div class="card-header">
                    <form id="technology-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($technologies_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($technologies_filters as $filter)
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
                        @section('technology-crud-search-bar')
                        <div id="technology-crud-search-bar"
                            class="{{ count($technologies_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('technologies_search')"
                                name="technologies_search"
                                id="technologies_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="technology-data-container" class="data-container">
                    @if($viewType == "table")
                    @include("PkgCompetences::technology._$viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="widgetUtilisateur-data-container-out" >
        @if($viewType == "widgets")
        @include("PkgCompetences::technology._$viewType")
        @endif
    </section>
    @show
</div>