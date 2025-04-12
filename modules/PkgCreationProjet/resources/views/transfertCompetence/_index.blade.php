{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'transfertCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'transfertCompetence.index' }}', 
        filterFormSelector: '#transfertCompetence-crud-filter-form',
        crudSelector: '#transfertCompetence-crud',
        tableSelector: '#transfertCompetence-data-container',
        formSelector: '#transfertCompetenceForm',
        indexUrl: '{{ route('transfertCompetences.index') }}', 
        createUrl: '{{ route('transfertCompetences.create') }}',
        editUrl: '{{ route('transfertCompetences.edit',  ['transfertCompetence' => ':id']) }}',
        showUrl: '{{ route('transfertCompetences.show',  ['transfertCompetence' => ':id']) }}',
        storeUrl: '{{ route('transfertCompetences.store') }}', 
        updateAttributesUrl: '{{ route('transfertCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('transfertCompetences.destroy',  ['transfertCompetence' => ':id']) }}', 
        calculationUrl:  '{{ route('transfertCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $transfertCompetence_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="transfertCompetence-crud" class="crud">
    @section('transfertCompetence-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::transfertCompetence.singular");
    @endphp
    <x-crud-header 
        id="transfertCompetence-crud-header" icon="fas fa-book-open"  
        iconColor="text-info"
        title="{{ $transfertCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('transfertCompetence-crud-table')
    <section id="transfertCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('transfertCompetence-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$transfertCompetences_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$transfertCompetence_instance"
                            :createPermission="'create-transfertCompetence'"
                            :createRoute="route('transfertCompetences.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-transfertCompetence'"
                            :importRoute="route('transfertCompetences.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-transfertCompetence'"
                            :exportXlsxRoute="route('transfertCompetences.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('transfertCompetences.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$transfertCompetence_viewTypes"
                            :viewType="$transfertCompetence_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('transfertCompetence-crud-filters')
                <div class="card-header">
                    <form id="transfertCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($transfertCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($transfertCompetences_filters as $filter)
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
                        @section('transfertCompetence-crud-search-bar')
                        <div id="transfertCompetence-crud-search-bar"
                            class="{{ count($transfertCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('transfertCompetences_search')"
                                name="transfertCompetences_search"
                                id="transfertCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="transfertCompetence-data-container" class="data-container">
                    @if($transfertCompetence_viewType == "table")
                    @include("PkgCreationProjet::transfertCompetence._$transfertCompetence_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="transfertCompetence-data-container-out" >
        @if($transfertCompetence_viewType == "widgets")
        @include("PkgCreationProjet::transfertCompetence._$transfertCompetence_viewType")
        @endif
    </section>
    @show
</div>