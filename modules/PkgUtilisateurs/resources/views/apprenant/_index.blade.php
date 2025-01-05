{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}
@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'apprenant',
        filterFormSelector: '#apprenant-crud-filter-form',
        crudSelector: '#apprenant-crud',
        tableSelector: '#apprenant-data-container',
        formSelector: '#apprenantForm',
        modalSelector : '#apprenantModal',
        indexUrl: '{{ route('apprenants.index') }}', 
        createUrl: '{{ route('apprenants.create') }}',
        editUrl: '{{ route('apprenants.edit',  ['apprenant' => ':id']) }}',
        showUrl: '{{ route('apprenants.show',  ['apprenant' => ':id']) }}',
        storeUrl: '{{ route('apprenants.store') }}', 
        deleteUrl: '{{ route('apprenants.destroy',  ['apprenant' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::apprenant.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::apprenant.singular") }}',
    });
</script>
@endpush
<div id="apprenant-crud" class="crud">
    @section('crud-header')
    <x-crud-header 
        id="apprenant-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::apprenant.plural') }}"
        :breadcrumbs="[
            ['label' => 'Gestion Utilisateurs', 'url' => '#'],
            ['label' => 'Villes']
        ]"
    />
    @show
    @section('crud-table')
    <section id="apprenant-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$apprenants_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-apprenant'"
                            :createRoute="route('apprenants.create')"
                            :createText="__('Ajouter une apprenant')"
                            :importPermission="'import-apprenant'"
                            :importRoute="route('apprenants.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-apprenant'"
                            :exportRoute="route('apprenants.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <div class="row">
                        <form id="apprenant-crud-filter-form" method="GET" class="row mb-3">
                            <x-filter-group>
                                <!-- Filtres spécifiques -->
                                @foreach ($apprenants_filters as $filter)
                                    <x-filter-field 
                                        :type="$filter['type']" 
                                        :field="$filter['field']" 
                                        :options="$filter['options'] ?? []"
                                        :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                                @endforeach
                            </x-filter-group>
                            @section('crud-search-bar')
                            <div id="apprenant-crud-search-bar"
                                class="{{ count($apprenants_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                                <x-search-bar
                                    :search="request('apprenants_search')"
                                    name="apprenants_search"
                                    id="apprenants_search"
                                    placeholder="Recherche ..."
                                />
                            </div>
                            @show
                        </form>
                    </div>
                </div>
                @show
                <div id="apprenant-data-container" class="data-container">
                    @include('PkgUtilisateurs::apprenant._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="apprenantModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>