{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'user',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'user.index' }}', 
        filterFormSelector: '#user-crud-filter-form',
        crudSelector: '#user-crud',
        tableSelector: '#user-data-container',
        formSelector: '#userForm',
        indexUrl: '{{ route('users.index') }}', 
        createUrl: '{{ route('users.create') }}',
        editUrl: '{{ route('users.edit',  ['user' => ':id']) }}',
        showUrl: '{{ route('users.show',  ['user' => ':id']) }}',
        storeUrl: '{{ route('users.store') }}', 
        updateAttributesUrl: '{{ route('users.updateAttributes') }}', 
        deleteUrl: '{{ route('users.destroy',  ['user' => ':id']) }}', 
        calculationUrl:  '{{ route('users.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::user.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::user.singular") }}',
    });
</script>

<div id="user-crud" class="crud">
    @section('user-crud-header')
    @php
        $package = __("PkgAutorisation::PkgAutorisation.name");
       $titre = __("PkgAutorisation::user.singular");
    @endphp
    <x-crud-header 
        id="user-crud-header" icon="fas fa-user"  
        iconColor="text-info"
        title="{{ __('PkgAutorisation::user.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('user-crud-table')
    <section id="user-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('user-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$users_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$user_instance"
                            :createPermission="'create-user'"
                            :createRoute="route('users.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-user'"
                            :importRoute="route('users.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-user'"
                            :exportXlsxRoute="route('users.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('users.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$user_viewTypes"
                            :viewType="$user_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('user-crud-filters')
                <div class="card-header">
                    <form id="user-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($users_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($users_filters as $filter)
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
                        @section('user-crud-search-bar')
                        <div id="user-crud-search-bar"
                            class="{{ count($users_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('users_search')"
                                name="users_search"
                                id="users_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="user-data-container" class="data-container">
                    @if($user_viewType == "table")
                    @include("PkgAutorisation::user._$user_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="user-data-container-out" >
        @if($user_viewType == "widgets")
        @include("PkgAutorisation::user._$user_viewType")
        @endif
    </section>
    @show
</div>