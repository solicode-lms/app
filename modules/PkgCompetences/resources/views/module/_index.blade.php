{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'module',
        filterFormSelector: '#module-crud-filter-form',
        crudSelector: '#module-crud',
        tableSelector: '#module-data-container',
        formSelector: '#moduleForm',
        modalSelector : '#moduleModal',
        indexUrl: '{{ route('modules.index') }}', 
        createUrl: '{{ route('modules.create') }}',
        editUrl: '{{ route('modules.edit',  ['module' => ':id']) }}',
        showUrl: '{{ route('modules.show',  ['module' => ':id']) }}',
        storeUrl: '{{ route('modules.store') }}', 
        deleteUrl: '{{ route('modules.destroy',  ['module' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::module.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::module.singular") }}',
    });
</script>
@endpush
<div id="module-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::groupe.singular");
    @endphp
    <x-crud-header 
        id="module-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::module.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="module-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$modules_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-module'"
                            :createRoute="route('modules.create')"
                            :createText="__('Ajouter une module')"
                            :importPermission="'import-module'"
                            :importRoute="route('modules.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-module'"
                            :exportRoute="route('modules.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="module-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($modules_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="module-crud-search-bar"
                            class="{{ count($modules_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('modules_search')"
                                name="modules_search"
                                id="modules_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="module-data-container" class="data-container">
                    @include('PkgCompetences::module._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="moduleModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>