{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'resource',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'resource.index' }}', 
        filterFormSelector: '#resource-crud-filter-form',
        crudSelector: '#resource-crud',
        tableSelector: '#resource-data-container',
        formSelector: '#resourceForm',
        indexUrl: '{{ route('resources.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('resources.create') }}',
        editUrl: '{{ route('resources.edit',  ['resource' => ':id']) }}',
        showUrl: '{{ route('resources.show',  ['resource' => ':id']) }}',
        storeUrl: '{{ route('resources.store') }}', 
        updateAttributesUrl: '{{ route('resources.updateAttributes') }}', 
        deleteUrl: '{{ route('resources.destroy',  ['resource' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-resource')),
        calculationUrl:  '{{ route('resources.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::resource.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $resource_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="resource-crud" class="crud">
    @section('resource-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::resource.singular");
    @endphp
    <x-crud-header 
        id="resource-crud-header" icon="fas fa-book"  
        iconColor="text-info"
        title="{{ $resource_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('resource-crud-table')
    <section id="resource-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('resource-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$resources_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$resource_instance"
                                    :createPermission="'create-resource'"
                                    :createRoute="route('resources.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-resource'"
                                    :importRoute="route('resources.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-resource'"
                                    :exportXlsxRoute="route('resources.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('resources.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$resource_viewTypes"
                                    :viewType="$resource_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('resource-crud-filters')
                <div class="card-header">
                    <form id="resource-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($resources_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($resources_filters as $filter)
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
                        @section('resource-crud-search-bar')
                        <div id="resource-crud-search-bar"
                            class="{{ count($resources_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('resources_search')"
                                name="resources_search"
                                id="resources_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="resource-data-container" class="data-container">
                    @if($resource_viewType != "widgets")
                    @include("PkgCreationProjet::resource._$resource_viewType")
                    @endif
                </div>
                @section('resource-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-resource")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('resources.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-resource')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('resources.bulkDelete') }}" 
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
     <section id="resource-data-container-out" >
        @if($resource_viewType == "widgets")
        @include("PkgCreationProjet::resource._$resource_viewType")
        @endif
    </section>
    @show
</div>