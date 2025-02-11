{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : true,
        entity_name: 'projet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'projet.index' }}', 
        filterFormSelector: '#projet-crud-filter-form',
        crudSelector: '#projet-crud',
        tableSelector: '#projet-data-container',
        formSelector: '#projetForm',
        indexUrl: '{{ route('projets.index') }}', 
        createUrl: '{{ route('projets.create') }}',
        editUrl: '{{ route('projets.edit',  ['projet' => ':id']) }}',
        showUrl: '{{ route('projets.show',  ['projet' => ':id']) }}',
        storeUrl: '{{ route('projets.store') }}', 
        deleteUrl: '{{ route('projets.destroy',  ['projet' => ':id']) }}', 
        calculationUrl:  '{{ route('projets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::projet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::projet.singular") }}',
    });
</script>

<div id="projet-crud" class="crud">
    @section('projet-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::projet.singular");
    @endphp
    <x-crud-header 
        id="projet-crud-header" icon="fas fa-calendar-alt"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::projet.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('projet-crud-table')
    <section id="projet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('projet-crud-stats-bar')
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
                            :createText="__('Ajouter')"
                            :importPermission="'import-projet'"
                            :importRoute="route('projets.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-projet'"
                            :exportXlsxRoute="route('projets.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('projets.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('projet-crud-filters')
                <div class="card-header">
                    <form id="projet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($projets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($projets_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('projet-crud-search-bar')
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
    </section>
    @show
</div>