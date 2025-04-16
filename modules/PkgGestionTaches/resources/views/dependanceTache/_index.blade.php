{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'dependanceTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'dependanceTache.index' }}', 
        filterFormSelector: '#dependanceTache-crud-filter-form',
        crudSelector: '#dependanceTache-crud',
        tableSelector: '#dependanceTache-data-container',
        formSelector: '#dependanceTacheForm',
        indexUrl: '{{ route('dependanceTaches.index') }}', 
        createUrl: '{{ route('dependanceTaches.create') }}',
        editUrl: '{{ route('dependanceTaches.edit',  ['dependanceTache' => ':id']) }}',
        showUrl: '{{ route('dependanceTaches.show',  ['dependanceTache' => ':id']) }}',
        storeUrl: '{{ route('dependanceTaches.store') }}', 
        updateAttributesUrl: '{{ route('dependanceTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('dependanceTaches.destroy',  ['dependanceTache' => ':id']) }}', 
        calculationUrl:  '{{ route('dependanceTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::dependanceTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::dependanceTache.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $dependanceTache_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="dependanceTache-crud" class="crud">
    @section('dependanceTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::dependanceTache.singular");
    @endphp
    <x-crud-header 
        id="dependanceTache-crud-header" icon="fas fa-link"  
        iconColor="text-info"
        title="{{ $dependanceTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('dependanceTache-crud-table')
    <section id="dependanceTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('dependanceTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$dependanceTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$dependanceTache_instance"
                                :createPermission="'create-dependanceTache'"
                                :createRoute="route('dependanceTaches.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-dependanceTache'"
                                :importRoute="route('dependanceTaches.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-dependanceTache'"
                                :exportXlsxRoute="route('dependanceTaches.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('dependanceTaches.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$dependanceTache_viewTypes"
                                :viewType="$dependanceTache_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('dependanceTache-crud-filters')
                <div class="card-header">
                    <form id="dependanceTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($dependanceTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($dependanceTaches_filters as $filter)
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
                        @section('dependanceTache-crud-search-bar')
                        <div id="dependanceTache-crud-search-bar"
                            class="{{ count($dependanceTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('dependanceTaches_search')"
                                name="dependanceTaches_search"
                                id="dependanceTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="dependanceTache-data-container" class="data-container">
                    @if($dependanceTache_viewType == "table")
                    @include("PkgGestionTaches::dependanceTache._$dependanceTache_viewType")
                    @endif
                </div>
                @section('dependanceTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-dependanceTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('dependanceTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-dependanceTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('dependanceTaches.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    @endcan
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="dependanceTache-data-container-out" >
        @if($dependanceTache_viewType == "widgets")
        @include("PkgGestionTaches::dependanceTache._$dependanceTache_viewType")
        @endif
    </section>
    @show
</div>