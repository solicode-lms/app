{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'profile',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'profile.index' }}', 
        filterFormSelector: '#profile-crud-filter-form',
        crudSelector: '#profile-crud',
        tableSelector: '#profile-data-container',
        formSelector: '#profileForm',
        indexUrl: '{{ route('profiles.index') }}', 
        createUrl: '{{ route('profiles.create') }}',
        editUrl: '{{ route('profiles.edit',  ['profile' => ':id']) }}',
        showUrl: '{{ route('profiles.show',  ['profile' => ':id']) }}',
        storeUrl: '{{ route('profiles.store') }}', 
        updateAttributesUrl: '{{ route('profiles.updateAttributes') }}', 
        deleteUrl: '{{ route('profiles.destroy',  ['profile' => ':id']) }}', 
        calculationUrl:  '{{ route('profiles.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::profile.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::profile.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $profile_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="profile-crud" class="crud">
    @section('profile-crud-header')
    @php
        $package = __("PkgAutorisation::PkgAutorisation.name");
       $titre = __("PkgAutorisation::profile.singular");
    @endphp
    <x-crud-header 
        id="profile-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $profile_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('profile-crud-table')
    <section id="profile-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('profile-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$profiles_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$profile_instance"
                                :createPermission="'create-profile'"
                                :createRoute="route('profiles.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-profile'"
                                :importRoute="route('profiles.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-profile'"
                                :exportXlsxRoute="route('profiles.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('profiles.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$profile_viewTypes"
                                :viewType="$profile_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('profile-crud-filters')
                <div class="card-header">
                    <form id="profile-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($profiles_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($profiles_filters as $filter)
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
                        @section('profile-crud-search-bar')
                        <div id="profile-crud-search-bar"
                            class="{{ count($profiles_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('profiles_search')"
                                name="profiles_search"
                                id="profiles_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="profile-data-container" class="data-container">
                    @if($profile_viewType == "table")
                    @include("PkgAutorisation::profile._$profile_viewType")
                    @endif
                </div>
                @section('profile-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-profile")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('profiles.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-profile')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('profiles.bulkDelete') }}" 
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
     <section id="profile-data-container-out" >
        @if($profile_viewType == "widgets")
        @include("PkgAutorisation::profile._$profile_viewType")
        @endif
    </section>
    @show
</div>