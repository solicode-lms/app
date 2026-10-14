{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'revisionTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'revisionTache.index' }}', 
        filterFormSelector: '#revisionTache-crud-filter-form',
        crudSelector: '#revisionTache-crud',
        tableSelector: '#revisionTache-data-container',
        formSelector: '#revisionTacheForm',
        indexUrl: '{{ route('revisionTaches.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('revisionTaches.create') }}',
        editUrl: '{{ route('revisionTaches.edit',  ['revisionTache' => ':id']) }}',
        fieldMetaUrl: '{{ route('revisionTaches.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('revisionTaches.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('revisionTaches.show',  ['revisionTache' => ':id']) }}',
        getEntityUrl: '{{ route("revisionTaches.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('revisionTaches.store') }}', 
        updateAttributesUrl: '{{ route('revisionTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('revisionTaches.destroy',  ['revisionTache' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-revisionTache')),
        calculationUrl:  '{{ route('revisionTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationTache::revisionTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationTache::revisionTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $revisionTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="revisionTache-crud" class="crud">
    @section('revisionTache-crud-header')
    @php
        $package = __("PkgRealisationTache::PkgRealisationTache.name");
       $titre = __("PkgRealisationTache::revisionTache.singular");
    @endphp
    <x-crud-header 
        id="revisionTache-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $revisionTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('revisionTache-crud-table')
    <section id="revisionTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('revisionTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$revisionTaches_stats"
                            />
                            <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$revisionTache_instance"
                                    :createPermission="'create-revisionTache'"
                                    :createRoute="route('revisionTaches.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-revisionTache'"
                                    :importRoute="route('revisionTaches.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-revisionTache'"
                                    :exportXlsxRoute="route('revisionTaches.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('revisionTaches.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$revisionTache_viewTypes"
                                    :viewType="$revisionTache_viewType"
                                    :total="$revisionTaches_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('revisionTache-crud-filters')
                <div class="card-header">
                    <form id="revisionTache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($revisionTaches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($revisionTaches_filters as $filter)
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
                        @section('revisionTache-crud-search-bar')
                        <div id="revisionTache-crud-search-bar"
                            class="{{ count($revisionTaches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('revisionTaches_search')"
                                name="revisionTaches_search"
                                id="revisionTaches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="revisionTache-data-container" class="data-container">
                    @if($revisionTache_viewType != "widgets")
                    @include("PkgRealisationTache::revisionTache._$revisionTache_viewType")
                    @endif
                </div>
                @section('revisionTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-revisionTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('revisionTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-revisionTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('revisionTaches.bulkDelete') }}" 
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
     <section id="revisionTache-data-container-out" >
        @if($revisionTache_viewType == "widgets")
        @include("PkgRealisationTache::revisionTache._$revisionTache_viewType")
        @endif
    </section>
    @show
</div>