{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'userModelFilter',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'userModelFilter.index' }}', 
        filterFormSelector: '#userModelFilter-crud-filter-form',
        crudSelector: '#userModelFilter-crud',
        tableSelector: '#userModelFilter-data-container',
        formSelector: '#userModelFilterForm',
        indexUrl: '{{ route('userModelFilters.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('userModelFilters.create') }}',
        editUrl: '{{ route('userModelFilters.edit',  ['userModelFilter' => ':id']) }}',
        showUrl: '{{ route('userModelFilters.show',  ['userModelFilter' => ':id']) }}',
        storeUrl: '{{ route('userModelFilters.store') }}', 
        updateAttributesUrl: '{{ route('userModelFilters.updateAttributes') }}', 
        deleteUrl: '{{ route('userModelFilters.destroy',  ['userModelFilter' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-userModelFilter')),
        calculationUrl:  '{{ route('userModelFilters.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::userModelFilter.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::userModelFilter.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $userModelFilter_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="userModelFilter-crud" class="crud">
    @section('userModelFilter-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::userModelFilter.singular");
    @endphp
    <x-crud-header 
        id="userModelFilter-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $userModelFilter_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('userModelFilter-crud-table')
    <section id="userModelFilter-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('userModelFilter-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$userModelFilters_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$userModelFilter_instance"
                                    :createPermission="'create-userModelFilter'"
                                    :createRoute="route('userModelFilters.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-userModelFilter'"
                                    :importRoute="route('userModelFilters.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-userModelFilter'"
                                    :exportXlsxRoute="route('userModelFilters.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('userModelFilters.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$userModelFilter_viewTypes"
                                    :viewType="$userModelFilter_viewType"
                                    :total="$userModelFilters_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('userModelFilter-crud-filters')
                @if(!empty($userModelFilters_total) &&  $userModelFilters_total > 5)
                <div class="card-header">
                    <form id="userModelFilter-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($userModelFilters_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($userModelFilters_filters as $filter)
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
                        @section('userModelFilter-crud-search-bar')
                        <div id="userModelFilter-crud-search-bar"
                            class="{{ count($userModelFilters_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('userModelFilters_search')"
                                name="userModelFilters_search"
                                id="userModelFilters_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="userModelFilter-data-container" class="data-container">
                    @if($userModelFilter_viewType != "widgets")
                    @include("Core::userModelFilter._$userModelFilter_viewType")
                    @endif
                </div>
                @section('userModelFilter-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-userModelFilter")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('userModelFilters.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-userModelFilter')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('userModelFilters.bulkDelete') }}" 
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
     <section id="userModelFilter-data-container-out" >
        @if($userModelFilter_viewType == "widgets")
        @include("Core::userModelFilter._$userModelFilter_viewType")
        @endif
    </section>
    @show
</div>