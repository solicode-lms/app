{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'permission',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'permission.index' }}', 
        filterFormSelector: '#permission-crud-filter-form',
        crudSelector: '#permission-crud',
        tableSelector: '#permission-data-container',
        formSelector: '#permissionForm',
        indexUrl: '{{ route('permissions.index') }}', 
        createUrl: '{{ route('permissions.create') }}',
        editUrl: '{{ route('permissions.edit',  ['permission' => ':id']) }}',
        showUrl: '{{ route('permissions.show',  ['permission' => ':id']) }}',
        storeUrl: '{{ route('permissions.store') }}', 
        updateAttributesUrl: '{{ route('permissions.updateAttributes') }}', 
        deleteUrl: '{{ route('permissions.destroy',  ['permission' => ':id']) }}', 
        calculationUrl:  '{{ route('permissions.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::permission.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::permission.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $permission_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="permission-crud" class="crud">
    @section('permission-crud-header')
    @php
        $package = __("PkgAutorisation::PkgAutorisation.name");
       $titre = __("PkgAutorisation::permission.singular");
    @endphp
    <x-crud-header 
        id="permission-crud-header" icon="fas fa-lock-open"  
        iconColor="text-info"
        title="{{ $permission_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('permission-crud-table')
    <section id="permission-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('permission-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$permissions_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$permission_instance"
                                :createPermission="'create-permission'"
                                :createRoute="route('permissions.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-permission'"
                                :importRoute="route('permissions.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-permission'"
                                :exportXlsxRoute="route('permissions.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('permissions.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$permission_viewTypes"
                                :viewType="$permission_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('permission-crud-filters')
                <div class="card-header">
                    <form id="permission-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($permissions_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($permissions_filters as $filter)
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
                        @section('permission-crud-search-bar')
                        <div id="permission-crud-search-bar"
                            class="{{ count($permissions_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('permissions_search')"
                                name="permissions_search"
                                id="permissions_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="permission-data-container" class="data-container">
                    @if($permission_viewType == "table")
                    @include("PkgAutorisation::permission._$permission_viewType")
                    @endif
                </div>
                @section('permission-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-permission")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('permissions.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-permission')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('permissions.bulkDelete') }}" 
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
     <section id="permission-data-container-out" >
        @if($permission_viewType == "widgets")
        @include("PkgAutorisation::permission._$permission_viewType")
        @endif
    </section>
    @show
</div>