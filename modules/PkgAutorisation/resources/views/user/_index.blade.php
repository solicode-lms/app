{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'user',
        filterFormSelector: '#user-crud-filter-form',
        crudSelector: '#user-crud',
        tableSelector: '#user-data-container',
        formSelector: '#userForm',
        modalSelector : '#userModal',
        indexUrl: '{{ route('users.index') }}', 
        createUrl: '{{ route('users.create') }}',
        editUrl: '{{ route('users.edit',  ['user' => ':id']) }}',
        showUrl: '{{ route('users.show',  ['user' => ':id']) }}',
        storeUrl: '{{ route('users.store') }}', 
        deleteUrl: '{{ route('users.destroy',  ['user' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::user.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::user.singular") }}',
    });
</script>
@endpush
<div id="user-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgAutorisation::PkgAutorisation.name");
       $titre = __("PkgAutorisation::user.singular");
    @endphp
    <x-crud-header 
        id="user-crud-header" icon="fas fa-user-circle"  
        iconColor="text-info"
        title="{{ __('PkgAutorisation::user.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="user-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$users_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-user'"
                            :createRoute="route('users.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-user'"
                            :importRoute="route('users.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-user'"
                            :exportRoute="route('users.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="user-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($users_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
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
                    @include('PkgAutorisation::user._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="userModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>