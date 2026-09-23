{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'question',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'question.index' }}', 
        filterFormSelector: '#question-crud-filter-form',
        crudSelector: '#question-crud',
        tableSelector: '#question-data-container',
        formSelector: '#questionForm',
        indexUrl: '{{ route('questions.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('questions.create') }}',
        editUrl: '{{ route('questions.edit',  ['question' => ':id']) }}',
        fieldMetaUrl: '{{ route('questions.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('questions.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('questions.show',  ['question' => ':id']) }}',
        getEntityUrl: '{{ route("questions.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('questions.store') }}', 
        updateAttributesUrl: '{{ route('questions.updateAttributes') }}', 
        deleteUrl: '{{ route('questions.destroy',  ['question' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-question')),
        calculationUrl:  '{{ route('questions.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::question.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::question.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $question_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="question-crud" class="crud">
    @section('question-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::question.singular");
    @endphp
    <x-crud-header 
        id="question-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $question_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('question-crud-table')
    <section id="question-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('question-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$questions_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$question_instance"
                                    :createPermission="'create-question'"
                                    :createRoute="route('questions.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-question'"
                                    :importRoute="route('questions.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-question'"
                                    :exportXlsxRoute="route('questions.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('questions.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$question_viewTypes"
                                    :viewType="$question_viewType"
                                    :total="$questions_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('question-crud-filters')
                @if(!empty($questions_total) &&  $questions_total > 10)
                <div class="card-header">
                    <form id="question-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($questions_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($questions_filters as $filter)
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
                        @section('question-crud-search-bar')
                        <div id="question-crud-search-bar"
                            class="{{ count($questions_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('questions_search')"
                                name="questions_search"
                                id="questions_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="question-data-container" class="data-container">
                    @if($question_viewType != "widgets")
                    @include("PkgQcm::question._$question_viewType")
                    @endif
                </div>
                @section('question-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-question")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('questions.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-question')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('questions.bulkDelete') }}" 
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
     <section id="question-data-container-out" >
        @if($question_viewType == "widgets")
        @include("PkgQcm::question._$question_viewType")
        @endif
    </section>
    @show
</div>