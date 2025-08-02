{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'phaseEvaluation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'phaseEvaluation.index' }}', 
        filterFormSelector: '#phaseEvaluation-crud-filter-form',
        crudSelector: '#phaseEvaluation-crud',
        tableSelector: '#phaseEvaluation-data-container',
        formSelector: '#phaseEvaluationForm',
        indexUrl: '{{ route('phaseEvaluations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('phaseEvaluations.create') }}',
        editUrl: '{{ route('phaseEvaluations.edit',  ['phaseEvaluation' => ':id']) }}',
        showUrl: '{{ route('phaseEvaluations.show',  ['phaseEvaluation' => ':id']) }}',
        storeUrl: '{{ route('phaseEvaluations.store') }}', 
        updateAttributesUrl: '{{ route('phaseEvaluations.updateAttributes') }}', 
        deleteUrl: '{{ route('phaseEvaluations.destroy',  ['phaseEvaluation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-phaseEvaluation')),
        calculationUrl:  '{{ route('phaseEvaluations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::phaseEvaluation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::phaseEvaluation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $phaseEvaluation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="phaseEvaluation-crud" class="crud">
    @section('phaseEvaluation-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::phaseEvaluation.singular");
    @endphp
    <x-crud-header 
        id="phaseEvaluation-crud-header" icon="fas fa-battery-three-quarters"  
        iconColor="text-info"
        title="{{ $phaseEvaluation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('phaseEvaluation-crud-table')
    <section id="phaseEvaluation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('phaseEvaluation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$phaseEvaluations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$phaseEvaluation_instance"
                                    :createPermission="'create-phaseEvaluation'"
                                    :createRoute="route('phaseEvaluations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-phaseEvaluation'"
                                    :importRoute="route('phaseEvaluations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-phaseEvaluation'"
                                    :exportXlsxRoute="route('phaseEvaluations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('phaseEvaluations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$phaseEvaluation_viewTypes"
                                    :viewType="$phaseEvaluation_viewType"
                                    :total="$phaseEvaluations_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('phaseEvaluation-crud-filters')
                @if(!empty($phaseEvaluations_total) &&  $phaseEvaluations_total > 5)
                <div class="card-header">
                    <form id="phaseEvaluation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($phaseEvaluations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($phaseEvaluations_filters as $filter)
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
                        @section('phaseEvaluation-crud-search-bar')
                        <div id="phaseEvaluation-crud-search-bar"
                            class="{{ count($phaseEvaluations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('phaseEvaluations_search')"
                                name="phaseEvaluations_search"
                                id="phaseEvaluations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="phaseEvaluation-data-container" class="data-container">
                    @if($phaseEvaluation_viewType != "widgets")
                    @include("PkgCompetences::phaseEvaluation._$phaseEvaluation_viewType")
                    @endif
                </div>
                @section('phaseEvaluation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-phaseEvaluation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('phaseEvaluations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-phaseEvaluation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('phaseEvaluations.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    @endcan
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="phaseEvaluation-data-container-out" >
        @if($phaseEvaluation_viewType == "widgets")
        @include("PkgCompetences::phaseEvaluation._$phaseEvaluation_viewType")
        @endif
    </section>
    @show
</div>