{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'nationalite',
        filterFormSelector: '#nationalite-crud-filter-form',
        crudSelector: '#nationalite-crud',
        tableSelector: '#nationalite-data-container',
        formSelector: '#nationaliteForm',
        modalSelector : '#nationaliteModal',
        indexUrl: '{{ route('nationalites.index') }}', 
        createUrl: '{{ route('nationalites.create') }}',
        editUrl: '{{ route('nationalites.edit',  ['nationalite' => ':id']) }}',
        showUrl: '{{ route('nationalites.show',  ['nationalite' => ':id']) }}',
        storeUrl: '{{ route('nationalites.store') }}', 
        deleteUrl: '{{ route('nationalites.destroy',  ['nationalite' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::nationalite.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::nationalite.singular") }}',
    });
</script>
@endpush
<div id="nationalite-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::nationalite.singular");
    @endphp
    <x-crud-header 
        id="nationalite-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::nationalite.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="nationalite-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$nationalites_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-nationalite'"
                            :createRoute="route('nationalites.create')"
                            :createText="__('Ajouter une nationalite')"
                            :importPermission="'import-nationalite'"
                            :importRoute="route('nationalites.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-nationalite'"
                            :exportRoute="route('nationalites.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="nationalite-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($nationalites_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="nationalite-crud-search-bar"
                            class="{{ count($nationalites_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('nationalites_search')"
                                name="nationalites_search"
                                id="nationalites_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="nationalite-data-container" class="data-container">
                    @include('PkgUtilisateurs::nationalite._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="nationaliteModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>