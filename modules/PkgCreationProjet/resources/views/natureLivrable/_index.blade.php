{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'natureLivrable',
        filterFormSelector: '#natureLivrable-crud-filter-form',
        crudSelector: '#natureLivrable-crud',
        tableSelector: '#natureLivrable-data-container',
        formSelector: '#natureLivrableForm',
        indexUrl: '{{ route('natureLivrables.index') }}', 
        createUrl: '{{ route('natureLivrables.create') }}',
        editUrl: '{{ route('natureLivrables.edit',  ['natureLivrable' => ':id']) }}',
        showUrl: '{{ route('natureLivrables.show',  ['natureLivrable' => ':id']) }}',
        storeUrl: '{{ route('natureLivrables.store') }}', 
        deleteUrl: '{{ route('natureLivrables.destroy',  ['natureLivrable' => ':id']) }}', 
        calculationUrl:  '{{ route('natureLivrables.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
    });
</script>

<div id="natureLivrable-crud" class="crud">
    @section('natureLivrable-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::natureLivrable.singular");
    @endphp
    <x-crud-header 
        id="natureLivrable-crud-header" icon="fas fa-file-archive"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::natureLivrable.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('natureLivrable-crud-table')
    <section id="natureLivrable-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('natureLivrable-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$natureLivrables_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-natureLivrable'"
                            :createRoute="route('natureLivrables.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-natureLivrable'"
                            :importRoute="route('natureLivrables.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-natureLivrable'"
                            :exportRoute="route('natureLivrables.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('natureLivrable-crud-filters')
                <div class="card-header">
                    <form id="natureLivrable-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($natureLivrables_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($natureLivrables_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('natureLivrable-crud-search-bar')
                        <div id="natureLivrable-crud-search-bar"
                            class="{{ count($natureLivrables_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('natureLivrables_search')"
                                name="natureLivrables_search"
                                id="natureLivrables_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="natureLivrable-data-container" class="data-container">
                    @include('PkgCreationProjet::natureLivrable._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>