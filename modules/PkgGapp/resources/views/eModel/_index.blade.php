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
        entity_name: 'eModel',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'eModel.index' }}', 
        filterFormSelector: '#eModel-crud-filter-form',
        crudSelector: '#eModel-crud',
        tableSelector: '#eModel-data-container',
        formSelector: '#eModelForm',
        indexUrl: '{{ route('eModels.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('eModels.create') }}',
        editUrl: '{{ route('eModels.edit',  ['eModel' => ':id']) }}',
        showUrl: '{{ route('eModels.show',  ['eModel' => ':id']) }}',
        getEntityUrl: '{{ route("eModels.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('eModels.store') }}', 
        updateAttributesUrl: '{{ route('eModels.updateAttributes') }}', 
        deleteUrl: '{{ route('eModels.destroy',  ['eModel' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-eModel')),
        calculationUrl:  '{{ route('eModels.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eModel.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eModel.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $eModel_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="eModel-crud" class="crud">
    @section('eModel-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eModel.singular");
    @endphp
    <x-crud-header 
        id="eModel-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $eModel_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eModel-crud-table')
    <section id="eModel-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eModel-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$eModels_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$eModel_instance"
                                    :createPermission="'create-eModel'"
                                    :createRoute="route('eModels.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-eModel'"
                                    :importRoute="route('eModels.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-eModel'"
                                    :exportXlsxRoute="route('eModels.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('eModels.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$eModel_viewTypes"
                                    :viewType="$eModel_viewType"
                                    :total="$eModels_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('eModel-crud-filters')
                @if(!empty($eModels_total) &&  $eModels_total > 5)
                <div class="card-header">
                    <form id="eModel-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eModels_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eModels_filters as $filter)
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
                        @section('eModel-crud-search-bar')
                        <div id="eModel-crud-search-bar"
                            class="{{ count($eModels_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eModels_search')"
                                name="eModels_search"
                                id="eModels_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="eModel-data-container" class="data-container">
                    @if($eModel_viewType != "widgets")
                    @include("PkgGapp::eModel._$eModel_viewType")
                    @endif
                </div>
                @section('eModel-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-eModel")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('eModels.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-eModel')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('eModels.bulkDelete') }}" 
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
     <section id="eModel-data-container-out" >
        @if($eModel_viewType == "widgets")
        @include("PkgGapp::eModel._$eModel_viewType")
        @endif
    </section>
    @show
</div>