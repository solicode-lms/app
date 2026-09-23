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
        entity_name: 'questionLib',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'questionLib.index' }}', 
        filterFormSelector: '#questionLib-crud-filter-form',
        crudSelector: '#questionLib-crud',
        tableSelector: '#questionLib-data-container',
        formSelector: '#questionLibForm',
        indexUrl: '{{ route('questionLibs.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('questionLibs.create') }}',
        editUrl: '{{ route('questionLibs.edit',  ['questionLib' => ':id']) }}',
        fieldMetaUrl: '{{ route('questionLibs.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('questionLibs.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('questionLibs.show',  ['questionLib' => ':id']) }}',
        getEntityUrl: '{{ route("questionLibs.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('questionLibs.store') }}', 
        updateAttributesUrl: '{{ route('questionLibs.updateAttributes') }}', 
        deleteUrl: '{{ route('questionLibs.destroy',  ['questionLib' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-questionLib')),
        calculationUrl:  '{{ route('questionLibs.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::questionLib.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::questionLib.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $questionLib_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="questionLib-crud" class="crud">
    @section('questionLib-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::questionLib.singular");
    @endphp
    <x-crud-header 
        id="questionLib-crud-header" icon="fas fa-cube"  
        iconColor="text-info"
        title="{{ $questionLib_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('questionLib-crud-table')
    <section id="questionLib-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('questionLib-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$questionLibs_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$questionLib_instance"
                                    :createPermission="'create-questionLib'"
                                    :createRoute="route('questionLibs.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-questionLib'"
                                    :importRoute="route('questionLibs.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-questionLib'"
                                    :exportXlsxRoute="route('questionLibs.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('questionLibs.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$questionLib_viewTypes"
                                    :viewType="$questionLib_viewType"
                                    :total="$questionLibs_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('questionLib-crud-filters')
                @if(!empty($questionLibs_total) &&  $questionLibs_total > 10)
                <div class="card-header">
                    <form id="questionLib-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($questionLibs_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($questionLibs_filters as $filter)
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
                        @section('questionLib-crud-search-bar')
                        <div id="questionLib-crud-search-bar"
                            class="{{ count($questionLibs_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('questionLibs_search')"
                                name="questionLibs_search"
                                id="questionLibs_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="questionLib-data-container" class="data-container">
                    @if($questionLib_viewType != "widgets")
                    @include("PkgQcm::questionLib._$questionLib_viewType")
                    @endif
                </div>
                @section('questionLib-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-questionLib")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('questionLibs.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-questionLib')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('questionLibs.bulkDelete') }}" 
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
     <section id="questionLib-data-container-out" >
        @if($questionLib_viewType == "widgets")
        @include("PkgQcm::questionLib._$questionLib_viewType")
        @endif
    </section>
    @show
</div>