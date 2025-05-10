{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'labelRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'labelRealisationTache.index' }}', 
        filterFormSelector: '#labelRealisationTache-crud-filter-form',
        crudSelector: '#labelRealisationTache-crud',
        tableSelector: '#labelRealisationTache-data-container',
        formSelector: '#labelRealisationTacheForm',
        indexUrl: '{{ route('labelRealisationTaches.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('labelRealisationTaches.create') }}',
        editUrl: '{{ route('labelRealisationTaches.edit',  ['labelRealisationTache' => ':id']) }}',
        showUrl: '{{ route('labelRealisationTaches.show',  ['labelRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('labelRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('labelRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('labelRealisationTaches.destroy',  ['labelRealisationTache' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-labelRealisationTache')),
        calculationUrl:  '{{ route('labelRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::labelRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::labelRealisationTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $labelRealisationTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="labelRealisationTache-crud" class="crud">
    @section('labelRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::labelRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="labelRealisationTache-crud-header" icon="fas fa-tag"  
        iconColor="text-info"
        title="{{ $labelRealisationTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('labelRealisationTache-crud-table')
    <section id="labelRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('labelRealisationTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$labelRealisationTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$labelRealisationTache_instance"
                                    :createPermission="'create-labelRealisationTache'"
                                    :createRoute="route('labelRealisationTaches.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-labelRealisationTache'"
                                    :importRoute="route('labelRealisationTaches.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-labelRealisationTache'"
                                    :exportXlsxRoute="route('labelRealisationTaches.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('labelRealisationTaches.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$labelRealisationTache_viewTypes"
                                    :viewType="$labelRealisationTache_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('labelRealisationTache-crud-filters')
                <div class="card-header">
                    <form id="labelRealisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($labelRealisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($labelRealisationTaches_filters as $filter)
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
                        @section('labelRealisationTache-crud-search-bar')
                        <div id="labelRealisationTache-crud-search-bar"
                            class="{{ count($labelRealisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('labelRealisationTaches_search')"
                                name="labelRealisationTaches_search"
                                id="labelRealisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="labelRealisationTache-data-container" class="data-container">
                    @if($labelRealisationTache_viewType == "table")
                    @include("PkgGestionTaches::labelRealisationTache._$labelRealisationTache_viewType")
                    @endif
                </div>
                @section('labelRealisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-labelRealisationTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('labelRealisationTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-labelRealisationTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('labelRealisationTaches.bulkDelete') }}" 
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
     <section id="labelRealisationTache-data-container-out" >
        @if($labelRealisationTache_viewType == "widgets")
        @include("PkgGestionTaches::labelRealisationTache._$labelRealisationTache_viewType")
        @endif
    </section>
    @show
</div>