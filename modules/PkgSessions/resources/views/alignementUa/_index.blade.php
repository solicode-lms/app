{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'alignementUa',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'alignementUa.index' }}', 
        filterFormSelector: '#alignementUa-crud-filter-form',
        crudSelector: '#alignementUa-crud',
        tableSelector: '#alignementUa-data-container',
        formSelector: '#alignementUaForm',
        indexUrl: '{{ route('alignementUas.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('alignementUas.create') }}',
        editUrl: '{{ route('alignementUas.edit',  ['alignementUa' => ':id']) }}',
        showUrl: '{{ route('alignementUas.show',  ['alignementUa' => ':id']) }}',
        storeUrl: '{{ route('alignementUas.store') }}', 
        updateAttributesUrl: '{{ route('alignementUas.updateAttributes') }}', 
        deleteUrl: '{{ route('alignementUas.destroy',  ['alignementUa' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-alignementUa')),
        calculationUrl:  '{{ route('alignementUas.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgSessions::alignementUa.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgSessions::alignementUa.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $alignementUa_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="alignementUa-crud" class="crud">
    @section('alignementUa-crud-header')
    @php
        $package = __("PkgSessions::PkgSessions.name");
       $titre = __("PkgSessions::alignementUa.singular");
    @endphp
    <x-crud-header 
        id="alignementUa-crud-header" icon="fas fa-road"  
        iconColor="text-info"
        title="{{ $alignementUa_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('alignementUa-crud-table')
    <section id="alignementUa-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('alignementUa-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$alignementUas_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$alignementUa_instance"
                                    :createPermission="'create-alignementUa'"
                                    :createRoute="route('alignementUas.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-alignementUa'"
                                    :importRoute="route('alignementUas.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-alignementUa'"
                                    :exportXlsxRoute="route('alignementUas.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('alignementUas.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$alignementUa_viewTypes"
                                    :viewType="$alignementUa_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('alignementUa-crud-filters')
                <div class="card-header">
                    <form id="alignementUa-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($alignementUas_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($alignementUas_filters as $filter)
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
                        @section('alignementUa-crud-search-bar')
                        <div id="alignementUa-crud-search-bar"
                            class="{{ count($alignementUas_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('alignementUas_search')"
                                name="alignementUas_search"
                                id="alignementUas_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="alignementUa-data-container" class="data-container">
                    @if($alignementUa_viewType != "widgets")
                    @include("PkgSessions::alignementUa._$alignementUa_viewType")
                    @endif
                </div>
                @section('alignementUa-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-alignementUa")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('alignementUas.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-alignementUa')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('alignementUas.bulkDelete') }}" 
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
     <section id="alignementUa-data-container-out" >
        @if($alignementUa_viewType == "widgets")
        @include("PkgSessions::alignementUa._$alignementUa_viewType")
        @endif
    </section>
    @show
</div>