{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'commentaireRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'commentaireRealisationTache.index' }}', 
        filterFormSelector: '#commentaireRealisationTache-crud-filter-form',
        crudSelector: '#commentaireRealisationTache-crud',
        tableSelector: '#commentaireRealisationTache-data-container',
        formSelector: '#commentaireRealisationTacheForm',
        indexUrl: '{{ route('commentaireRealisationTaches.index') }}', 
        createUrl: '{{ route('commentaireRealisationTaches.create') }}',
        editUrl: '{{ route('commentaireRealisationTaches.edit',  ['commentaireRealisationTache' => ':id']) }}',
        showUrl: '{{ route('commentaireRealisationTaches.show',  ['commentaireRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('commentaireRealisationTaches.store') }}', 
        deleteUrl: '{{ route('commentaireRealisationTaches.destroy',  ['commentaireRealisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('commentaireRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::commentaireRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::commentaireRealisationTache.singular") }}',
    });
</script>

<div id="commentaireRealisationTache-crud" class="crud">
    @section('commentaireRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::commentaireRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="commentaireRealisationTache-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGestionTaches::commentaireRealisationTache.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('commentaireRealisationTache-crud-table')
    <section id="commentaireRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('commentaireRealisationTache-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$commentaireRealisationTaches_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-commentaireRealisationTache','import-commentaireRealisationTache','export-commentaireRealisationTache'])
                        <x-crud-actions
                            :instanceItem="$commentaireRealisationTache_instance"
                            :createPermission="'create-commentaireRealisationTache'"
                            :createRoute="route('commentaireRealisationTaches.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-commentaireRealisationTache'"
                            :importRoute="route('commentaireRealisationTaches.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-commentaireRealisationTache'"
                            :exportXlsxRoute="route('commentaireRealisationTaches.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('commentaireRealisationTaches.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('commentaireRealisationTache-crud-filters')
                <div class="card-header">
                    <form id="commentaireRealisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($commentaireRealisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($commentaireRealisationTaches_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('commentaireRealisationTache-crud-search-bar')
                        <div id="commentaireRealisationTache-crud-search-bar"
                            class="{{ count($commentaireRealisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('commentaireRealisationTaches_search')"
                                name="commentaireRealisationTaches_search"
                                id="commentaireRealisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="commentaireRealisationTache-data-container" class="data-container">
                    @include('PkgGestionTaches::commentaireRealisationTache._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>