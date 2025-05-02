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
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('commentaireRealisationTaches.create') }}',
        editUrl: '{{ route('commentaireRealisationTaches.edit',  ['commentaireRealisationTache' => ':id']) }}',
        showUrl: '{{ route('commentaireRealisationTaches.show',  ['commentaireRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('commentaireRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('commentaireRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('commentaireRealisationTaches.destroy',  ['commentaireRealisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('commentaireRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::commentaireRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::commentaireRealisationTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $commentaireRealisationTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="commentaireRealisationTache-crud" class="crud">
    @section('commentaireRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::commentaireRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="commentaireRealisationTache-crud-header" icon="fas fa-comments"  
        iconColor="text-info"
        title="{{ $commentaireRealisationTache_title }}"
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
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$commentaireRealisationTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
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
                                    :viewTypes="$commentaireRealisationTache_viewTypes"
                                    :viewType="$commentaireRealisationTache_viewType"
                                />
                            </div>


                        
                        </div>
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
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" 
                                    :targetDynamicDropdown="isset($filter['targetDynamicDropdown']) ? $filter['targetDynamicDropdown'] : null"
                                    :targetDynamicDropdownApiUrl="isset($filter['targetDynamicDropdownApiUrl']) ? $filter['targetDynamicDropdownApiUrl'] : null" 
                                    :targetDynamicDropdownFilter="isset($filter['targetDynamicDropdownFilter']) ? $filter['targetDynamicDropdownFilter'] : null" />
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
                    @if($commentaireRealisationTache_viewType == "table")
                    @include("PkgGestionTaches::commentaireRealisationTache._$commentaireRealisationTache_viewType")
                    @endif
                </div>
                @section('commentaireRealisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-commentaireRealisationTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('commentaireRealisationTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-commentaireRealisationTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('commentaireRealisationTaches.bulkDelete') }}" 
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
     <section id="commentaireRealisationTache-data-container-out" >
        @if($commentaireRealisationTache_viewType == "widgets")
        @include("PkgGestionTaches::commentaireRealisationTache._$commentaireRealisationTache_viewType")
        @endif
    </section>
    @show
</div>