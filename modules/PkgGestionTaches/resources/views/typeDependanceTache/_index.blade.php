{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'typeDependanceTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'typeDependanceTache.index' }}', 
        filterFormSelector: '#typeDependanceTache-crud-filter-form',
        crudSelector: '#typeDependanceTache-crud',
        tableSelector: '#typeDependanceTache-data-container',
        formSelector: '#typeDependanceTacheForm',
        indexUrl: '{{ route('typeDependanceTaches.index') }}', 
        createUrl: '{{ route('typeDependanceTaches.create') }}',
        editUrl: '{{ route('typeDependanceTaches.edit',  ['typeDependanceTache' => ':id']) }}',
        showUrl: '{{ route('typeDependanceTaches.show',  ['typeDependanceTache' => ':id']) }}',
        storeUrl: '{{ route('typeDependanceTaches.store') }}', 
        updateAttributesUrl: '{{ route('typeDependanceTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('typeDependanceTaches.destroy',  ['typeDependanceTache' => ':id']) }}', 
        calculationUrl:  '{{ route('typeDependanceTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::typeDependanceTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::typeDependanceTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $typeDependanceTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="typeDependanceTache-crud" class="crud">
    @section('typeDependanceTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::typeDependanceTache.singular");
    @endphp
    <x-crud-header 
        id="typeDependanceTache-crud-header" icon="fas fa-random"  
        iconColor="text-info"
        title="{{ $typeDependanceTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('typeDependanceTache-crud-table')
    <section id="typeDependanceTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('typeDependanceTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$typeDependanceTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$typeDependanceTache_instance"
                                :createPermission="'create-typeDependanceTache'"
                                :createRoute="route('typeDependanceTaches.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-typeDependanceTache'"
                                :importRoute="route('typeDependanceTaches.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-typeDependanceTache'"
                                :exportXlsxRoute="route('typeDependanceTaches.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('typeDependanceTaches.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$typeDependanceTache_viewTypes"
                                :viewType="$typeDependanceTache_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('typeDependanceTache-crud-filters')
                <div class="card-header">
                    <form id="typeDependanceTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($typeDependanceTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($typeDependanceTaches_filters as $filter)
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
                        @section('typeDependanceTache-crud-search-bar')
                        <div id="typeDependanceTache-crud-search-bar"
                            class="{{ count($typeDependanceTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('typeDependanceTaches_search')"
                                name="typeDependanceTaches_search"
                                id="typeDependanceTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="typeDependanceTache-data-container" class="data-container">
                    @if($typeDependanceTache_viewType == "table")
                    @include("PkgGestionTaches::typeDependanceTache._$typeDependanceTache_viewType")
                    @endif
                </div>
                @section('typeDependanceTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-typeDependanceTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('typeDependanceTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-typeDependanceTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('typeDependanceTaches.bulkDelete') }}" 
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
     <section id="typeDependanceTache-data-container-out" >
        @if($typeDependanceTache_viewType == "widgets")
        @include("PkgGestionTaches::typeDependanceTache._$typeDependanceTache_viewType")
        @endif
    </section>
    @show
</div>