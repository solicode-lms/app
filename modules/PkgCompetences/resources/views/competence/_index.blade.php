{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'competence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'competence.index' }}', 
        filterFormSelector: '#competence-crud-filter-form',
        crudSelector: '#competence-crud',
        tableSelector: '#competence-data-container',
        formSelector: '#competenceForm',
        indexUrl: '{{ route('competences.index') }}', 
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit',  ['competence' => ':id']) }}',
        showUrl: '{{ route('competences.show',  ['competence' => ':id']) }}',
        storeUrl: '{{ route('competences.store') }}', 
        updateAttributesUrl: '{{ route('competences.updateAttributes') }}', 
        deleteUrl: '{{ route('competences.destroy',  ['competence' => ':id']) }}', 
        calculationUrl:  '{{ route('competences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::competence.singular") }}',
    });
</script>

<div id="competence-crud" class="crud">
    @section('competence-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::competence.singular");
    @endphp
    <x-crud-header 
        id="competence-crud-header" icon="fas fa-user-graduate"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::competence.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('competence-crud-table')
    <section id="competence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('competence-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$competences_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-competence','import-competence','export-competence'])
                        <x-crud-actions
                            :instanceItem="$competence_instance"
                            :createPermission="'create-competence'"
                            :createRoute="route('competences.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-competence'"
                            :importRoute="route('competences.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-competence'"
                            :exportXlsxRoute="route('competences.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('competences.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$competence_viewTypes"
                            :viewType="$competence_viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('competence-crud-filters')
                <div class="card-header">
                    <form id="competence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($competences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($competences_filters as $filter)
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
                        @section('competence-crud-search-bar')
                        <div id="competence-crud-search-bar"
                            class="{{ count($competences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('competences_search')"
                                name="competences_search"
                                id="competences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="competence-data-container" class="data-container">
                    @if($competence_viewType == "table")
                    @include("PkgCompetences::competence._$competence_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="competence-data-container-out" >
        @if($competence_viewType == "widgets")
        @include("PkgCompetences::competence._$competence_viewType")
        @endif
    </section>
    @show
</div>