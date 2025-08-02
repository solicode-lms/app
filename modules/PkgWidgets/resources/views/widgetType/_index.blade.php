{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'widgetType',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'widgetType.index' }}', 
        filterFormSelector: '#widgetType-crud-filter-form',
        crudSelector: '#widgetType-crud',
        tableSelector: '#widgetType-data-container',
        formSelector: '#widgetTypeForm',
        indexUrl: '{{ route('widgetTypes.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('widgetTypes.create') }}',
        editUrl: '{{ route('widgetTypes.edit',  ['widgetType' => ':id']) }}',
        showUrl: '{{ route('widgetTypes.show',  ['widgetType' => ':id']) }}',
        storeUrl: '{{ route('widgetTypes.store') }}', 
        updateAttributesUrl: '{{ route('widgetTypes.updateAttributes') }}', 
        deleteUrl: '{{ route('widgetTypes.destroy',  ['widgetType' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-widgetType')),
        calculationUrl:  '{{ route('widgetTypes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widgetType.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $widgetType_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="widgetType-crud" class="crud">
    @section('widgetType-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widgetType.singular");
    @endphp
    <x-crud-header 
        id="widgetType-crud-header" icon="fas fa-cube"  
        iconColor="text-info"
        title="{{ $widgetType_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widgetType-crud-table')
    <section id="widgetType-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widgetType-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$widgetTypes_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$widgetType_instance"
                                    :createPermission="'create-widgetType'"
                                    :createRoute="route('widgetTypes.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-widgetType'"
                                    :importRoute="route('widgetTypes.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-widgetType'"
                                    :exportXlsxRoute="route('widgetTypes.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('widgetTypes.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$widgetType_viewTypes"
                                    :viewType="$widgetType_viewType"
                                    :total="$widgetTypes_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('widgetType-crud-filters')
                @if(!empty($widgetTypes_total) &&  $widgetTypes_total > 5)
                <div class="card-header">
                    <form id="widgetType-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($widgetTypes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($widgetTypes_filters as $filter)
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
                        @section('widgetType-crud-search-bar')
                        <div id="widgetType-crud-search-bar"
                            class="{{ count($widgetTypes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgetTypes_search')"
                                name="widgetTypes_search"
                                id="widgetTypes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="widgetType-data-container" class="data-container">
                    @if($widgetType_viewType != "widgets")
                    @include("PkgWidgets::widgetType._$widgetType_viewType")
                    @endif
                </div>
                @section('widgetType-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-widgetType")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('widgetTypes.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-widgetType')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('widgetTypes.bulkDelete') }}" 
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
     <section id="widgetType-data-container-out" >
        @if($widgetType_viewType == "widgets")
        @include("PkgWidgets::widgetType._$widgetType_viewType")
        @endif
    </section>
    @show
</div>