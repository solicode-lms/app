{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'widgetUtilisateur',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'widgetUtilisateur.index' }}', 
        filterFormSelector: '#widgetUtilisateur-crud-filter-form',
        crudSelector: '#widgetUtilisateur-crud',
        tableSelector: '#widgetUtilisateur-data-container',
        formSelector: '#widgetUtilisateurForm',
        indexUrl: '{{ route('widgetUtilisateurs.index') }}', 
        createUrl: '{{ route('widgetUtilisateurs.create') }}',
        editUrl: '{{ route('widgetUtilisateurs.edit',  ['widgetUtilisateur' => ':id']) }}',
        showUrl: '{{ route('widgetUtilisateurs.show',  ['widgetUtilisateur' => ':id']) }}',
        storeUrl: '{{ route('widgetUtilisateurs.store') }}', 
        deleteUrl: '{{ route('widgetUtilisateurs.destroy',  ['widgetUtilisateur' => ':id']) }}', 
        calculationUrl:  '{{ route('widgetUtilisateurs.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetUtilisateur.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widgetUtilisateur.singular") }}',
    });
</script>

<div id="widgetUtilisateur-crud" class="crud">
    @section('widgetUtilisateur-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widgetUtilisateur.singular");
    @endphp
    <x-crud-header 
        id="widgetUtilisateur-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgWidgets::widgetUtilisateur.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widgetUtilisateur-crud-table')
    <section id="widgetUtilisateur-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widgetUtilisateur-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$widgetUtilisateurs_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-widgetUtilisateur','import-widgetUtilisateur','export-widgetUtilisateur'])
                        <x-crud-actions
                            :instanceItem="$widgetUtilisateur_instance"
                            :createPermission="'create-widgetUtilisateur'"
                            :createRoute="route('widgetUtilisateurs.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-widgetUtilisateur'"
                            :importRoute="route('widgetUtilisateurs.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-widgetUtilisateur'"
                            :exportXlsxRoute="route('widgetUtilisateurs.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('widgetUtilisateurs.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('widgetUtilisateur-crud-filters')
                <div class="card-header">
                    <form id="widgetUtilisateur-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($widgetUtilisateurs_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($widgetUtilisateurs_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" 
                                    :targetDynamicDropdown="isset($filter['targetDynamicDropdown']) ? $filter['targetDynamicDropdown'] : null"
                                    :targetDynamicDropdownApiUrl="isset($filter['targetDynamicDropdownApiUrl']) ? $filter['targetDynamicDropdownApiUrl'] : null" 
                                    :targetDynamicDropdownFilter="isset($filter['targetDynamicDropdownFilter']) ? $filter['targetDynamicDropdownFilter'] : null" />
                            @endforeach
                        </x-filter-group>
                        @section('widgetUtilisateur-crud-search-bar')
                        <div id="widgetUtilisateur-crud-search-bar"
                            class="{{ count($widgetUtilisateurs_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgetUtilisateurs_search')"
                                name="widgetUtilisateurs_search"
                                id="widgetUtilisateurs_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="widgetUtilisateur-data-container" class="data-container">
                    @include('PkgWidgets::widgetUtilisateur._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>