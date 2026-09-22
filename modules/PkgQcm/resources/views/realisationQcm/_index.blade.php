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
        entity_name: 'realisationQcm',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationQcm.index' }}', 
        filterFormSelector: '#realisationQcm-crud-filter-form',
        crudSelector: '#realisationQcm-crud',
        tableSelector: '#realisationQcm-data-container',
        formSelector: '#realisationQcmForm',
        indexUrl: '{{ route('realisationQcms.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationQcms.create') }}',
        editUrl: '{{ route('realisationQcms.edit',  ['realisationQcm' => ':id']) }}',
        fieldMetaUrl: '{{ route('realisationQcms.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('realisationQcms.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('realisationQcms.show',  ['realisationQcm' => ':id']) }}',
        getEntityUrl: '{{ route("realisationQcms.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('realisationQcms.store') }}', 
        updateAttributesUrl: '{{ route('realisationQcms.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationQcms.destroy',  ['realisationQcm' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationQcm')),
        calculationUrl:  '{{ route('realisationQcms.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::realisationQcm.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::realisationQcm.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationQcm_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationQcm-crud" class="crud">
    @section('realisationQcm-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::realisationQcm.singular");
    @endphp
    <x-crud-header 
        id="realisationQcm-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $realisationQcm_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationQcm-crud-table')
    <section id="realisationQcm-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationQcm-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationQcms_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationQcm_instance"
                                    :createPermission="'create-realisationQcm'"
                                    :createRoute="route('realisationQcms.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationQcm'"
                                    :importRoute="route('realisationQcms.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationQcm'"
                                    :exportXlsxRoute="route('realisationQcms.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationQcms.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationQcm_viewTypes"
                                    :viewType="$realisationQcm_viewType"
                                    :total="$realisationQcms_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationQcm-crud-filters')
                @if(!empty($realisationQcms_total) &&  $realisationQcms_total > 10)
                <div class="card-header">
                    <form id="realisationQcm-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationQcms_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationQcms_filters as $filter)
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
                        @section('realisationQcm-crud-search-bar')
                        <div id="realisationQcm-crud-search-bar"
                            class="{{ count($realisationQcms_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationQcms_search')"
                                name="realisationQcms_search"
                                id="realisationQcms_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationQcm-data-container" class="data-container">
                    @if($realisationQcm_viewType != "widgets")
                    @include("PkgQcm::realisationQcm._$realisationQcm_viewType")
                    @endif
                </div>
                @section('realisationQcm-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationQcm")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationQcms.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationQcm')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationQcms.bulkDelete') }}" 
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
     <section id="realisationQcm-data-container-out" >
        @if($realisationQcm_viewType == "widgets")
        @include("PkgQcm::realisationQcm._$realisationQcm_viewType")
        @endif
    </section>
    @show
</div>