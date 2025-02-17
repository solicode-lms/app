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
        deleteUrl: '{{ route('permissions.destroy',  ['permission' => ':id']) }}', 
        calculationUrl:  '{{ route('permissions.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::permission.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutorisation::permission.singular") }}',
    });
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
        title="{{ __('PkgAutorisation::permission.plural') }}"
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
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$permissions_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @can('create', $permission_instance)
                        <x-crud-actions
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
                        />
                        @endcan
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
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
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
                    @include('PkgAutorisation::permission._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>