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
        deleteUrl: '{{ route('profiles.destroy',  ['profile' => ':id']) }}', 
        calculationUrl:  '{{ route('profiles.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::profile.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::profile.singular") }}',
    });
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
        title="{{ __('PkgAutorisation::profile.plural') }}"
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
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$profiles_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-profile','import-profile','export-profile'])
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
                        />
                        @endcan
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
                    @include('PkgAutorisation::profile._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>