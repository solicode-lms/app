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
        entity_name: 'questionQcm',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'questionQcm.index' }}', 
        filterFormSelector: '#questionQcm-crud-filter-form',
        crudSelector: '#questionQcm-crud',
        tableSelector: '#questionQcm-data-container',
        formSelector: '#questionQcmForm',
        indexUrl: '{{ route('questionQcms.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('questionQcms.create') }}',
        editUrl: '{{ route('questionQcms.edit',  ['questionQcm' => ':id']) }}',
        fieldMetaUrl: '{{ route('questionQcms.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('questionQcms.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('questionQcms.show',  ['questionQcm' => ':id']) }}',
        getEntityUrl: '{{ route("questionQcms.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('questionQcms.store') }}', 
        updateAttributesUrl: '{{ route('questionQcms.updateAttributes') }}', 
        deleteUrl: '{{ route('questionQcms.destroy',  ['questionQcm' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-questionQcm')),
        calculationUrl:  '{{ route('questionQcms.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::questionQcm.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::questionQcm.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $questionQcm_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="questionQcm-crud" class="crud">
    @section('questionQcm-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::questionQcm.singular");
    @endphp
    <x-crud-header 
        id="questionQcm-crud-header" icon="fas fa-chalkboard"  
        iconColor="text-info"
        title="{{ $questionQcm_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('questionQcm-crud-table')
    <section id="questionQcm-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('questionQcm-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$questionQcms_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$questionQcm_instance"
                                    :createPermission="'create-questionQcm'"
                                    :createRoute="route('questionQcms.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-questionQcm'"
                                    :importRoute="route('questionQcms.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-questionQcm'"
                                    :exportXlsxRoute="route('questionQcms.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('questionQcms.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$questionQcm_viewTypes"
                                    :viewType="$questionQcm_viewType"
                                    :total="$questionQcms_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('questionQcm-crud-filters')
                @if(!empty($questionQcms_total) &&  $questionQcms_total > 10)
                <div class="card-header">
                    <form id="questionQcm-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($questionQcms_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($questionQcms_filters as $filter)
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
                        @section('questionQcm-crud-search-bar')
                        <div id="questionQcm-crud-search-bar"
                            class="{{ count($questionQcms_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('questionQcms_search')"
                                name="questionQcms_search"
                                id="questionQcms_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="questionQcm-data-container" class="data-container">
                    @if($questionQcm_viewType != "widgets")
                    @include("PkgQcm::questionQcm._$questionQcm_viewType")
                    @endif
                </div>
                @section('questionQcm-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-questionQcm")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('questionQcms.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-questionQcm')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('questionQcms.bulkDelete') }}" 
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
     <section id="questionQcm-data-container-out" >
        @if($questionQcm_viewType == "widgets")
        @include("PkgQcm::questionQcm._$questionQcm_viewType")
        @endif
    </section>
    @show
</div>