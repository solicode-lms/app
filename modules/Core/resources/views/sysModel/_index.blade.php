{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sysModel',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysModel.index' }}', 
        filterFormSelector: '#sysModel-crud-filter-form',
        crudSelector: '#sysModel-crud',
        tableSelector: '#sysModel-data-container',
        formSelector: '#sysModelForm',
        indexUrl: '{{ route('sysModels.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sysModels.create') }}',
        editUrl: '{{ route('sysModels.edit',  ['sysModel' => ':id']) }}',
        showUrl: '{{ route('sysModels.show',  ['sysModel' => ':id']) }}',
        storeUrl: '{{ route('sysModels.store') }}', 
        updateAttributesUrl: '{{ route('sysModels.updateAttributes') }}', 
        deleteUrl: '{{ route('sysModels.destroy',  ['sysModel' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-sysModel')),
        calculationUrl:  '{{ route('sysModels.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysModel.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sysModel_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sysModel-crud" class="crud">
    @section('sysModel-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysModel.singular");
    @endphp
    <x-crud-header 
        id="sysModel-crud-header" icon="fas fa-cubes"  
        iconColor="text-info"
        title="{{ $sysModel_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysModel-crud-table')
    <section id="sysModel-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysModel-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sysModels_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sysModel_instance"
                                    :createPermission="'create-sysModel'"
                                    :createRoute="route('sysModels.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sysModel'"
                                    :importRoute="route('sysModels.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sysModel'"
                                    :exportXlsxRoute="route('sysModels.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sysModels.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sysModel_viewTypes"
                                    :viewType="$sysModel_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sysModel-crud-filters')
                <div class="card-header">
                    <form id="sysModel-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysModels_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysModels_filters as $filter)
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
                        @section('sysModel-crud-search-bar')
                        <div id="sysModel-crud-search-bar"
                            class="{{ count($sysModels_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysModels_search')"
                                name="sysModels_search"
                                id="sysModels_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysModel-data-container" class="data-container">
                    @if($sysModel_viewType != "widgets")
                    @include("Core::sysModel._$sysModel_viewType")
                    @endif
                </div>
                @section('sysModel-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sysModel")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sysModels.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sysModel')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sysModels.bulkDelete') }}" 
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
     <section id="sysModel-data-container-out" >
        @if($sysModel_viewType == "widgets")
        @include("Core::sysModel._$sysModel_viewType")
        @endif
    </section>
    @show
</div>