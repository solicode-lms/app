{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sysController',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysController.index' }}', 
        filterFormSelector: '#sysController-crud-filter-form',
        crudSelector: '#sysController-crud',
        tableSelector: '#sysController-data-container',
        formSelector: '#sysControllerForm',
        indexUrl: '{{ route('sysControllers.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sysControllers.create') }}',
        editUrl: '{{ route('sysControllers.edit',  ['sysController' => ':id']) }}',
        showUrl: '{{ route('sysControllers.show',  ['sysController' => ':id']) }}',
        storeUrl: '{{ route('sysControllers.store') }}', 
        updateAttributesUrl: '{{ route('sysControllers.updateAttributes') }}', 
        deleteUrl: '{{ route('sysControllers.destroy',  ['sysController' => ':id']) }}', 
        calculationUrl:  '{{ route('sysControllers.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysController.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sysController_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sysController-crud" class="crud">
    @section('sysController-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysController.singular");
    @endphp
    <x-crud-header 
        id="sysController-crud-header" icon="fas fa-server"  
        iconColor="text-info"
        title="{{ $sysController_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysController-crud-table')
    <section id="sysController-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysController-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sysControllers_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sysController_instance"
                                    :createPermission="'create-sysController'"
                                    :createRoute="route('sysControllers.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sysController'"
                                    :importRoute="route('sysControllers.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sysController'"
                                    :exportXlsxRoute="route('sysControllers.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sysControllers.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sysController_viewTypes"
                                    :viewType="$sysController_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sysController-crud-filters')
                <div class="card-header">
                    <form id="sysController-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysControllers_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysControllers_filters as $filter)
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
                        @section('sysController-crud-search-bar')
                        <div id="sysController-crud-search-bar"
                            class="{{ count($sysControllers_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysControllers_search')"
                                name="sysControllers_search"
                                id="sysControllers_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysController-data-container" class="data-container">
                    @if($sysController_viewType == "table")
                    @include("Core::sysController._$sysController_viewType")
                    @endif
                </div>
                @section('sysController-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sysController")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sysControllers.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sysController')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sysControllers.bulkDelete') }}" 
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
     <section id="sysController-data-container-out" >
        @if($sysController_viewType == "widgets")
        @include("Core::sysController._$sysController_viewType")
        @endif
    </section>
    @show
</div>