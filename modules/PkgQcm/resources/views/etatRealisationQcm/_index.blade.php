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
        entity_name: 'etatRealisationQcm',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationQcm.index' }}', 
        filterFormSelector: '#etatRealisationQcm-crud-filter-form',
        crudSelector: '#etatRealisationQcm-crud',
        tableSelector: '#etatRealisationQcm-data-container',
        formSelector: '#etatRealisationQcmForm',
        indexUrl: '{{ route('etatRealisationQcms.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatRealisationQcms.create') }}',
        editUrl: '{{ route('etatRealisationQcms.edit',  ['etatRealisationQcm' => ':id']) }}',
        fieldMetaUrl: '{{ route('etatRealisationQcms.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('etatRealisationQcms.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('etatRealisationQcms.show',  ['etatRealisationQcm' => ':id']) }}',
        getEntityUrl: '{{ route("etatRealisationQcms.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatRealisationQcms.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationQcms.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationQcms.destroy',  ['etatRealisationQcm' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatRealisationQcm')),
        calculationUrl:  '{{ route('etatRealisationQcms.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::etatRealisationQcm.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::etatRealisationQcm.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationQcm_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationQcm-crud" class="crud">
    @section('etatRealisationQcm-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::etatRealisationQcm.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationQcm-crud-header" icon="fas fa-thermometer-half"  
        iconColor="text-info"
        title="{{ $etatRealisationQcm_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationQcm-crud-table')
    <section id="etatRealisationQcm-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationQcm-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationQcms_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatRealisationQcm_instance"
                                    :createPermission="'create-etatRealisationQcm'"
                                    :createRoute="route('etatRealisationQcms.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatRealisationQcm'"
                                    :importRoute="route('etatRealisationQcms.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatRealisationQcm'"
                                    :exportXlsxRoute="route('etatRealisationQcms.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatRealisationQcms.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatRealisationQcm_viewTypes"
                                    :viewType="$etatRealisationQcm_viewType"
                                    :total="$etatRealisationQcms_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationQcm-crud-filters')
                @if(!empty($etatRealisationQcms_total) &&  $etatRealisationQcms_total > 10)
                <div class="card-header">
                    <form id="etatRealisationQcm-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationQcms_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationQcms_filters as $filter)
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
                        @section('etatRealisationQcm-crud-search-bar')
                        <div id="etatRealisationQcm-crud-search-bar"
                            class="{{ count($etatRealisationQcms_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationQcms_search')"
                                name="etatRealisationQcms_search"
                                id="etatRealisationQcms_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatRealisationQcm-data-container" class="data-container">
                    @if($etatRealisationQcm_viewType != "widgets")
                    @include("PkgQcm::etatRealisationQcm._$etatRealisationQcm_viewType")
                    @endif
                </div>
                @section('etatRealisationQcm-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationQcm")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationQcms.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationQcm')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationQcms.bulkDelete') }}" 
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
     <section id="etatRealisationQcm-data-container-out" >
        @if($etatRealisationQcm_viewType == "widgets")
        @include("PkgQcm::etatRealisationQcm._$etatRealisationQcm_viewType")
        @endif
    </section>
    @show
</div>