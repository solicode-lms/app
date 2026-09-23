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
        entity_name: 'qcm',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'qcm.index' }}', 
        filterFormSelector: '#qcm-crud-filter-form',
        crudSelector: '#qcm-crud',
        tableSelector: '#qcm-data-container',
        formSelector: '#qcmForm',
        indexUrl: '{{ route('qcms.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('qcms.create') }}',
        editUrl: '{{ route('qcms.edit',  ['qcm' => ':id']) }}',
        fieldMetaUrl: '{{ route('qcms.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('qcms.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('qcms.show',  ['qcm' => ':id']) }}',
        getEntityUrl: '{{ route("qcms.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('qcms.store') }}', 
        updateAttributesUrl: '{{ route('qcms.updateAttributes') }}', 
        deleteUrl: '{{ route('qcms.destroy',  ['qcm' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-qcm')),
        calculationUrl:  '{{ route('qcms.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::qcm.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::qcm.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $qcm_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="qcm-crud" class="crud">
    @section('qcm-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::qcm.singular");
    @endphp
    <x-crud-header 
        id="qcm-crud-header" icon="fas f"  
        iconColor="text-info"
        title="{{ $qcm_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('qcm-crud-table')
    <section id="qcm-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('qcm-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$qcms_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$qcm_instance"
                                    :createPermission="'create-qcm'"
                                    :createRoute="route('qcms.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-qcm'"
                                    :importRoute="route('qcms.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-qcm'"
                                    :exportXlsxRoute="route('qcms.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('qcms.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$qcm_viewTypes"
                                    :viewType="$qcm_viewType"
                                    :total="$qcms_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('qcm-crud-filters')
                @if(!empty($qcms_total) &&  $qcms_total > 10)
                <div class="card-header">
                    <form id="qcm-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($qcms_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($qcms_filters as $filter)
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
                        @section('qcm-crud-search-bar')
                        <div id="qcm-crud-search-bar"
                            class="{{ count($qcms_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('qcms_search')"
                                name="qcms_search"
                                id="qcms_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="qcm-data-container" class="data-container">
                    @if($qcm_viewType != "widgets")
                    @include("PkgQcm::qcm._$qcm_viewType")
                    @endif
                </div>
                @section('qcm-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-qcm")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('qcms.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-qcm')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('qcms.bulkDelete') }}" 
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
     <section id="qcm-data-container-out" >
        @if($qcm_viewType == "widgets")
        @include("PkgQcm::qcm._$qcm_viewType")
        @endif
    </section>
    @show
</div>