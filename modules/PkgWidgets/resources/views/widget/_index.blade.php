{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'widget',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'widget.index' }}', 
        filterFormSelector: '#widget-crud-filter-form',
        crudSelector: '#widget-crud',
        tableSelector: '#widget-data-container',
        formSelector: '#widgetForm',
        indexUrl: '{{ route('widgets.index') }}', 
        createUrl: '{{ route('widgets.create') }}',
        editUrl: '{{ route('widgets.edit',  ['widget' => ':id']) }}',
        showUrl: '{{ route('widgets.show',  ['widget' => ':id']) }}',
        storeUrl: '{{ route('widgets.store') }}', 
        updateAttributesUrl: '{{ route('widgets.updateAttributes') }}', 
        deleteUrl: '{{ route('widgets.destroy',  ['widget' => ':id']) }}', 
        calculationUrl:  '{{ route('widgets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widget.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widget.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $widget_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="widget-crud" class="crud">
    @section('widget-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widget.singular");
    @endphp
    <x-crud-header 
        id="widget-crud-header" icon="fas fa-chart-bar"  
        iconColor="text-info"
        title="{{ $widget_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widget-crud-table')
    <section id="widget-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widget-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$widgets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$widget_instance"
                                :createPermission="'create-widget'"
                                :createRoute="route('widgets.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-widget'"
                                :importRoute="route('widgets.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-widget'"
                                :exportXlsxRoute="route('widgets.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('widgets.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$widget_viewTypes"
                                :viewType="$widget_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('widget-crud-filters')
                <div class="card-header">
                    <form id="widget-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($widgets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($widgets_filters as $filter)
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
                        @section('widget-crud-search-bar')
                        <div id="widget-crud-search-bar"
                            class="{{ count($widgets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgets_search')"
                                name="widgets_search"
                                id="widgets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="widget-data-container" class="data-container">
                    @if($widget_viewType == "table")
                    @include("PkgWidgets::widget._$widget_viewType")
                    @endif
                </div>
                @section('realisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('widgets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('widgets.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="widget-data-container-out" >
        @if($widget_viewType == "widgets")
        @include("PkgWidgets::widget._$widget_viewType")
        @endif
    </section>
    @show
</div>