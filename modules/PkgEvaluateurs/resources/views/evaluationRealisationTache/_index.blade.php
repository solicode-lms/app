{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'evaluationRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'evaluationRealisationTache.index' }}', 
        filterFormSelector: '#evaluationRealisationTache-crud-filter-form',
        crudSelector: '#evaluationRealisationTache-crud',
        tableSelector: '#evaluationRealisationTache-data-container',
        formSelector: '#evaluationRealisationTacheForm',
        indexUrl: '{{ route('evaluationRealisationTaches.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('evaluationRealisationTaches.create') }}',
        editUrl: '{{ route('evaluationRealisationTaches.edit',  ['evaluationRealisationTache' => ':id']) }}',
        showUrl: '{{ route('evaluationRealisationTaches.show',  ['evaluationRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('evaluationRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('evaluationRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('evaluationRealisationTaches.destroy',  ['evaluationRealisationTache' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-evaluationRealisationTache')),
        calculationUrl:  '{{ route('evaluationRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgEvaluateurs::evaluationRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgEvaluateurs::evaluationRealisationTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $evaluationRealisationTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="evaluationRealisationTache-crud" class="crud">
    @section('evaluationRealisationTache-crud-header')
    @php
        $package = __("PkgEvaluateurs::PkgEvaluateurs.name");
       $titre = __("PkgEvaluateurs::evaluationRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="evaluationRealisationTache-crud-header" icon="fas fa-clipboard-list"  
        iconColor="text-info"
        title="{{ $evaluationRealisationTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('evaluationRealisationTache-crud-table')
    <section id="evaluationRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('evaluationRealisationTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$evaluationRealisationTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$evaluationRealisationTache_instance"
                                    :createPermission="'create-evaluationRealisationTache'"
                                    :createRoute="route('evaluationRealisationTaches.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-evaluationRealisationTache'"
                                    :importRoute="route('evaluationRealisationTaches.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-evaluationRealisationTache'"
                                    :exportXlsxRoute="route('evaluationRealisationTaches.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('evaluationRealisationTaches.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$evaluationRealisationTache_viewTypes"
                                    :viewType="$evaluationRealisationTache_viewType"
                                    :total="$evaluationRealisationTaches_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('evaluationRealisationTache-crud-filters')
                @if(!empty($evaluationRealisationTaches_total) &&  $evaluationRealisationTaches_total > 5)
                <div class="card-header">
                    <form id="evaluationRealisationTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($evaluationRealisationTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($evaluationRealisationTaches_filters as $filter)
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
                        @section('evaluationRealisationTache-crud-search-bar')
                        <div id="evaluationRealisationTache-crud-search-bar"
                            class="{{ count($evaluationRealisationTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('evaluationRealisationTaches_search')"
                                name="evaluationRealisationTaches_search"
                                id="evaluationRealisationTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="evaluationRealisationTache-data-container" class="data-container">
                    @if($evaluationRealisationTache_viewType != "widgets")
                    @include("PkgEvaluateurs::evaluationRealisationTache._$evaluationRealisationTache_viewType")
                    @endif
                </div>
                @section('evaluationRealisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-evaluationRealisationTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('evaluationRealisationTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-evaluationRealisationTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('evaluationRealisationTaches.bulkDelete') }}" 
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
     <section id="evaluationRealisationTache-data-container-out" >
        @if($evaluationRealisationTache_viewType == "widgets")
        @include("PkgEvaluateurs::evaluationRealisationTache._$evaluationRealisationTache_viewType")
        @endif
    </section>
    @show
</div>