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
        createUrl: '{{ route('roles.create') }}',
        editUrl: '{{ route('roles.edit',  ['role' => ':id']) }}',
        showUrl: '{{ route('roles.show',  ['role' => ':id']) }}',
        storeUrl: '{{ route('roles.store') }}', 
        updateAttributesUrl: '{{ route('roles.updateAttributes') }}', 
        deleteUrl: '{{ route('roles.destroy',  ['role' => ':id']) }}', 
        calculationUrl:  '{{ route('roles.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::role.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::role.singular") }}',
    });
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
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$roles_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
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
                    @if($role_viewType == "table")
                    @include("PkgAutorisation::role._$role_viewType")
                    @endif
                </div>
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