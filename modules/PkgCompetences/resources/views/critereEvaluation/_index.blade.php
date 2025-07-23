{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'critereEvaluation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'critereEvaluation.index' }}', 
        filterFormSelector: '#critereEvaluation-crud-filter-form',
        crudSelector: '#critereEvaluation-crud',
        tableSelector: '#critereEvaluation-data-container',
        formSelector: '#critereEvaluationForm',
        indexUrl: '{{ route('critereEvaluations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('critereEvaluations.create') }}',
        editUrl: '{{ route('critereEvaluations.edit',  ['critereEvaluation' => ':id']) }}',
        showUrl: '{{ route('critereEvaluations.show',  ['critereEvaluation' => ':id']) }}',
        storeUrl: '{{ route('critereEvaluations.store') }}', 
        updateAttributesUrl: '{{ route('critereEvaluations.updateAttributes') }}', 
        deleteUrl: '{{ route('critereEvaluations.destroy',  ['critereEvaluation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-critereEvaluation')),
        calculationUrl:  '{{ route('critereEvaluations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::critereEvaluation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::critereEvaluation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $critereEvaluation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="critereEvaluation-crud" class="crud">
    @section('critereEvaluation-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::critereEvaluation.singular");
    @endphp
    <x-crud-header 
        id="critereEvaluation-crud-header" icon="fa-table"  
        iconColor="text-info"
        title="{{ $critereEvaluation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('critereEvaluation-crud-table')
    <section id="critereEvaluation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('critereEvaluation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$critereEvaluations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$critereEvaluation_instance"
                                    :createPermission="'create-critereEvaluation'"
                                    :createRoute="route('critereEvaluations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-critereEvaluation'"
                                    :importRoute="route('critereEvaluations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-critereEvaluation'"
                                    :exportXlsxRoute="route('critereEvaluations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('critereEvaluations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$critereEvaluation_viewTypes"
                                    :viewType="$critereEvaluation_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('critereEvaluation-crud-filters')
                <div class="card-header">
                    <form id="critereEvaluation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($critereEvaluations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($critereEvaluations_filters as $filter)
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
                        @section('critereEvaluation-crud-search-bar')
                        <div id="critereEvaluation-crud-search-bar"
                            class="{{ count($critereEvaluations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('critereEvaluations_search')"
                                name="critereEvaluations_search"
                                id="critereEvaluations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="critereEvaluation-data-container" class="data-container">
                    @if($critereEvaluation_viewType != "widgets")
                    @include("PkgCompetences::critereEvaluation._$critereEvaluation_viewType")
                    @endif
                </div>
                @section('critereEvaluation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-critereEvaluation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('critereEvaluations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-critereEvaluation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('critereEvaluations.bulkDelete') }}" 
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
     <section id="critereEvaluation-data-container-out" >
        @if($critereEvaluation_viewType == "widgets")
        @include("PkgCompetences::critereEvaluation._$critereEvaluation_viewType")
        @endif
    </section>
    @show
</div>