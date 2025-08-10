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
        entity_name: 'widgetOperation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'widgetOperation.index' }}', 
        filterFormSelector: '#widgetOperation-crud-filter-form',
        crudSelector: '#widgetOperation-crud',
        tableSelector: '#widgetOperation-data-container',
        formSelector: '#widgetOperationForm',
        indexUrl: '{{ route('widgetOperations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('widgetOperations.create') }}',
        editUrl: '{{ route('widgetOperations.edit',  ['widgetOperation' => ':id']) }}',
        showUrl: '{{ route('widgetOperations.show',  ['widgetOperation' => ':id']) }}',
        getEntityUrl: '{{ route("widgetOperations.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('widgetOperations.store') }}', 
        updateAttributesUrl: '{{ route('widgetOperations.updateAttributes') }}', 
        deleteUrl: '{{ route('widgetOperations.destroy',  ['widgetOperation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-widgetOperation')),
        calculationUrl:  '{{ route('widgetOperations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $widgetOperation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="widgetOperation-crud" class="crud">
    @section('widgetOperation-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widgetOperation.singular");
    @endphp
    <x-crud-header 
        id="widgetOperation-crud-header" icon="fas fa-calculator"  
        iconColor="text-info"
        title="{{ $widgetOperation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widgetOperation-crud-table')
    <section id="widgetOperation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widgetOperation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$widgetOperations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$widgetOperation_instance"
                                    :createPermission="'create-widgetOperation'"
                                    :createRoute="route('widgetOperations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-widgetOperation'"
                                    :importRoute="route('widgetOperations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-widgetOperation'"
                                    :exportXlsxRoute="route('widgetOperations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('widgetOperations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$widgetOperation_viewTypes"
                                    :viewType="$widgetOperation_viewType"
                                    :total="$widgetOperations_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('widgetOperation-crud-filters')
                @if(!empty($widgetOperations_total) &&  $widgetOperations_total > 5)
                <div class="card-header">
                    <form id="widgetOperation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($widgetOperations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($widgetOperations_filters as $filter)
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
                        @section('widgetOperation-crud-search-bar')
                        <div id="widgetOperation-crud-search-bar"
                            class="{{ count($widgetOperations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgetOperations_search')"
                                name="widgetOperations_search"
                                id="widgetOperations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="widgetOperation-data-container" class="data-container">
                    @if($widgetOperation_viewType != "widgets")
                    @include("PkgWidgets::widgetOperation._$widgetOperation_viewType")
                    @endif
                </div>
                @section('widgetOperation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-widgetOperation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('widgetOperations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-widgetOperation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('widgetOperations.bulkDelete') }}" 
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
     <section id="widgetOperation-data-container-out" >
        @if($widgetOperation_viewType == "widgets")
        @include("PkgWidgets::widgetOperation._$widgetOperation_viewType")
        @endif
    </section>
    @show
</div>