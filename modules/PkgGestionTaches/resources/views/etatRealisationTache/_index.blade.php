{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'etatRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationTache.index' }}', 
        filterFormSelector: '#etatRealisationTache-crud-filter-form',
        crudSelector: '#etatRealisationTache-crud',
        tableSelector: '#etatRealisationTache-data-container',
        formSelector: '#etatRealisationTacheForm',
        indexUrl: '{{ route('etatRealisationTaches.index') }}', 
        createUrl: '{{ route('etatRealisationTaches.create') }}',
        editUrl: '{{ route('etatRealisationTaches.edit',  ['etatRealisationTache' => ':id']) }}',
        showUrl: '{{ route('etatRealisationTaches.show',  ['etatRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('etatRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationTaches.destroy',  ['etatRealisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('etatRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::etatRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::etatRealisationTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationTache-crud" class="crud">
    @section('etatRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::etatRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationTache-crud-header" icon="fas fa-check"  
        iconColor="text-info"
        title="{{ $etatRealisationTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationTache-crud-table')
    <section id="etatRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$etatRealisationTache_instance"
                                :createPermission="'create-etatRealisationTache'"
                                :createRoute="route('etatRealisationTaches.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-etatRealisationTache'"
                                :importRoute="route('etatRealisationTaches.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-etatRealisationTache'"
                                :exportXlsxRoute="route('etatRealisationTaches.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('etatRealisationTaches.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$etatRealisationTache_viewTypes"
                                :viewType="$etatRealisationTache_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationTache-crud-filters')
                <div class="card-header">
                    <form id="etatRealisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationTaches_filters as $filter)
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
                        @section('etatRealisationTache-crud-search-bar')
                        <div id="etatRealisationTache-crud-search-bar"
                            class="{{ count($etatRealisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationTaches_search')"
                                name="etatRealisationTaches_search"
                                id="etatRealisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="etatRealisationTache-data-container" class="data-container">
                    @if($etatRealisationTache_viewType == "table")
                    @include("PkgGestionTaches::etatRealisationTache._$etatRealisationTache_viewType")
                    @endif
                </div>
                @section('etatRealisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationTaches.bulkDelete') }}" 
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
     <section id="etatRealisationTache-data-container-out" >
        @if($etatRealisationTache_viewType == "widgets")
        @include("PkgGestionTaches::etatRealisationTache._$etatRealisationTache_viewType")
        @endif
    </section>
    @show
</div>