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
        entity_name: 'sectionWidget',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sectionWidget.index' }}', 
        filterFormSelector: '#sectionWidget-crud-filter-form',
        crudSelector: '#sectionWidget-crud',
        tableSelector: '#sectionWidget-data-container',
        formSelector: '#sectionWidgetForm',
        indexUrl: '{{ route('sectionWidgets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sectionWidgets.create') }}',
        editUrl: '{{ route('sectionWidgets.edit',  ['sectionWidget' => ':id']) }}',
        fieldMetaUrl: '{{ route('sectionWidgets.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('sectionWidgets.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('sectionWidgets.show',  ['sectionWidget' => ':id']) }}',
        getEntityUrl: '{{ route("sectionWidgets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('sectionWidgets.store') }}', 
        updateAttributesUrl: '{{ route('sectionWidgets.updateAttributes') }}', 
        deleteUrl: '{{ route('sectionWidgets.destroy',  ['sectionWidget' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-sectionWidget')),
        calculationUrl:  '{{ route('sectionWidgets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::sectionWidget.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::sectionWidget.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sectionWidget_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sectionWidget-crud" class="crud">
    @section('sectionWidget-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::sectionWidget.singular");
    @endphp
    <x-crud-header 
        id="sectionWidget-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $sectionWidget_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sectionWidget-crud-table')
    <section id="sectionWidget-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sectionWidget-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sectionWidgets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sectionWidget_instance"
                                    :createPermission="'create-sectionWidget'"
                                    :createRoute="route('sectionWidgets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sectionWidget'"
                                    :importRoute="route('sectionWidgets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sectionWidget'"
                                    :exportXlsxRoute="route('sectionWidgets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sectionWidgets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sectionWidget_viewTypes"
                                    :viewType="$sectionWidget_viewType"
                                    :total="$sectionWidgets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sectionWidget-crud-filters')
                @if(!empty($sectionWidgets_total) &&  $sectionWidgets_total > 5)
                <div class="card-header">
                    <form id="sectionWidget-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sectionWidgets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sectionWidgets_filters as $filter)
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
                        @section('sectionWidget-crud-search-bar')
                        <div id="sectionWidget-crud-search-bar"
                            class="{{ count($sectionWidgets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sectionWidgets_search')"
                                name="sectionWidgets_search"
                                id="sectionWidgets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="sectionWidget-data-container" class="data-container">
                    @if($sectionWidget_viewType != "widgets")
                    @include("PkgWidgets::sectionWidget._$sectionWidget_viewType")
                    @endif
                </div>
                @section('sectionWidget-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sectionWidget")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sectionWidgets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sectionWidget')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sectionWidgets.bulkDelete') }}" 
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
     <section id="sectionWidget-data-container-out" >
        @if($sectionWidget_viewType == "widgets")
        @include("PkgWidgets::sectionWidget._$sectionWidget_viewType")
        @endif
    </section>
    @show
</div>