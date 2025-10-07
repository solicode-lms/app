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
        entity_name: 'user',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'user.index' }}', 
        filterFormSelector: '#user-crud-filter-form',
        crudSelector: '#user-crud',
        tableSelector: '#user-data-container',
        formSelector: '#userForm',
        indexUrl: '{{ route('users.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('users.create') }}',
        editUrl: '{{ route('users.edit',  ['user' => ':id']) }}',
        fieldMetaUrl: '{{ route('users.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('users.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('users.show',  ['user' => ':id']) }}',
        getEntityUrl: '{{ route("users.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('users.store') }}', 
        updateAttributesUrl: '{{ route('users.updateAttributes') }}', 
        deleteUrl: '{{ route('users.destroy',  ['user' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-user')),
        calculationUrl:  '{{ route('users.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::user.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::user.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $user_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
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
        title="{{ $user_title }}"
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
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$users_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
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
                                    :total="$users_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('user-crud-filters')
                @if(!empty($users_total) &&  $users_total > 10)
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
                @endif
                @show
                <div id="user-data-container" class="data-container">
                    @if($user_viewType != "widgets")
                    @include("PkgAutorisation::user._$user_viewType")
                    @endif
                </div>
                @section('user-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-user")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('users.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-user')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('users.bulkDelete') }}" 
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
     <section id="user-data-container-out" >
        @if($user_viewType == "widgets")
        @include("PkgAutorisation::user._$user_viewType")
        @endif
    </section>
    @show
</div>