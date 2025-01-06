{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'sysController',
        filterFormSelector: '#sysController-crud-filter-form',
        crudSelector: '#sysController-crud',
        tableSelector: '#sysController-data-container',
        formSelector: '#sysControllerForm',
        modalSelector : '#sysControllerModal',
        indexUrl: '{{ route('sysControllers.index') }}', 
        createUrl: '{{ route('sysControllers.create') }}',
        editUrl: '{{ route('sysControllers.edit',  ['sysController' => ':id']) }}',
        showUrl: '{{ route('sysControllers.show',  ['sysController' => ':id']) }}',
        storeUrl: '{{ route('sysControllers.store') }}', 
        deleteUrl: '{{ route('sysControllers.destroy',  ['sysController' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
    });
</script>
@endpush
<div id="sysController-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::groupe.singular");
    @endphp
    <x-crud-header 
        id="sysController-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('Core::sysController.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="sysController-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$sysControllers_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-sysController'"
                            :createRoute="route('sysControllers.create')"
                            :createText="__('Ajouter une sysController')"
                            :importPermission="'import-sysController'"
                            :importRoute="route('sysControllers.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-sysController'"
                            :exportRoute="route('sysControllers.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="sysController-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($sysControllers_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="sysController-crud-search-bar"
                            class="{{ count($sysControllers_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysControllers_search')"
                                name="sysControllers_search"
                                id="sysControllers_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysController-data-container" class="data-container">
                    @include('Core::sysController._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="sysControllerModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>