{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'role',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'role.index' }}', 
        filterFormSelector: '#role-crud-filter-form',
        crudSelector: '#role-crud',
        tableSelector: '#role-data-container',
        formSelector: '#roleForm',
        indexUrl: '{{ route('roles.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('roles.create') }}',
        editUrl: '{{ route('roles.edit',  ['role' => ':id']) }}',
        showUrl: '{{ route('roles.show',  ['role' => ':id']) }}',
        storeUrl: '{{ route('roles.store') }}', 
        updateAttributesUrl: '{{ route('roles.updateAttributes') }}', 
        deleteUrl: '{{ route('roles.destroy',  ['role' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-role')),
        calculationUrl:  '{{ route('roles.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::role.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::role.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $role_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="role-crud" class="crud">
    @section('role-crud-header')
    @php
        $package = __("PkgAutorisation::PkgAutorisation.name");
       $titre = __("PkgAutorisation::role.singular");
    @endphp
    <x-crud-header 
        id="role-crud-header" icon="fas fa-id-badge"  
        iconColor="text-info"
        title="{{ $role_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('role-crud-table')
    <section id="role-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('role-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$roles_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$role_instance"
                                    :createPermission="'create-role'"
                                    :createRoute="route('roles.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-role'"
                                    :importRoute="route('roles.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-role'"
                                    :exportXlsxRoute="route('roles.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('roles.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$role_viewTypes"
                                    :viewType="$role_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('role-crud-filters')
                <div class="card-header">
                    <form id="role-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($roles_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($roles_filters as $filter)
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
                        @section('role-crud-search-bar')
                        <div id="role-crud-search-bar"
                            class="{{ count($roles_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('roles_search')"
                                name="roles_search"
                                id="roles_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="role-data-container" class="data-container">
                    @if($role_viewType != "widgets")
                    @include("PkgAutorisation::role._$role_viewType")
                    @endif
                </div>
                @section('role-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-role")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('roles.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-role')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('roles.bulkDelete') }}" 
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
     <section id="role-data-container-out" >
        @if($role_viewType == "widgets")
        @include("PkgAutorisation::role._$role_viewType")
        @endif
    </section>
    @show
</div>