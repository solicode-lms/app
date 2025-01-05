{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'projet',
        filterFormSelector: '#projet-crud-filter-form',
        crudSelector: '#projet-crud',
        tableSelector: '#projet-data-container',
        formSelector: '#projetForm',
        modalSelector : '#projetModal',
        indexUrl: '{{ route('projets.index') }}', 
        createUrl: '{{ route('projets.create') }}',
        editUrl: '{{ route('projets.edit',  ['projet' => ':id']) }}',
        showUrl: '{{ route('projets.show',  ['projet' => ':id']) }}',
        storeUrl: '{{ route('projets.store') }}', 
        deleteUrl: '{{ route('projets.destroy',  ['projet' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::projet.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::projet.singular") }}',
    });
</script>
@endpush
<div id="projet-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::groupe.singular");
    @endphp
    <x-crud-header 
        id="projet-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::projet.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="projet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$projets_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-projet'"
                            :createRoute="route('projets.create')"
                            :createText="__('Ajouter une projet')"
                            :importPermission="'import-projet'"
                            :importRoute="route('projets.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-projet'"
                            :exportRoute="route('projets.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="projet-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($projets_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="projet-crud-search-bar"
                            class="{{ count($projets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('projets_search')"
                                name="projets_search"
                                id="projets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="projet-data-container" class="data-container">
                    @include('PkgCreationProjet::projet._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="projetModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>