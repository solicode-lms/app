{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'tache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'tache.index' }}', 
        filterFormSelector: '#tache-crud-filter-form',
        crudSelector: '#tache-crud',
        tableSelector: '#tache-data-container',
        formSelector: '#tacheForm',
        indexUrl: '{{ route('taches.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('taches.create') }}',
        editUrl: '{{ route('taches.edit',  ['tache' => ':id']) }}',
        fieldMetaUrl: '{{ route('taches.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('taches.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('taches.show',  ['tache' => ':id']) }}',
        getEntityUrl: '{{ route("taches.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('taches.store') }}', 
        updateAttributesUrl: '{{ route('taches.updateAttributes') }}', 
        deleteUrl: '{{ route('taches.destroy',  ['tache' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-tache')),
        calculationUrl:  '{{ route('taches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationTache::tache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationTache::tache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $tache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="tache-crud" class="crud">
    @section('tache-crud-header')
    @php
        $package = __("PkgCreationTache::PkgCreationTache.name");
       $titre = __("PkgCreationTache::tache.singular");
    @endphp
    <x-crud-header 
        id="tache-crud-header" icon="fas fa-tasks"  
        iconColor="text-info"
        title="{{ $tache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('tache-crud-table')
    <section id="tache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('tache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$taches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$tache_instance"
                                    :createPermission="'create-tache'"
                                    :createRoute="route('taches.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-tache'"
                                    :importRoute="route('taches.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-tache'"
                                    :exportXlsxRoute="route('taches.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('taches.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$tache_viewTypes"
                                    :viewType="$tache_viewType"
                                    :total="$taches_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('tache-crud-filters')
                @if(!empty($taches_total) &&  $taches_total > 10)
                <div class="card-header">
                    <form id="tache-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($taches_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($taches_filters as $filter)
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
                        @section('tache-crud-search-bar')
                        <div id="tache-crud-search-bar"
                            class="{{ count($taches_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('taches_search')"
                                name="taches_search"
                                id="taches_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="tache-data-container" class="data-container">
                    @if($tache_viewType != "widgets")
                    @include("PkgCreationTache::tache._$tache_viewType")
                    @endif
                </div>
                @section('tache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-tache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('taches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-tache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('taches.bulkDelete') }}" 
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
     <section id="tache-data-container-out" >
        @if($tache_viewType == "widgets")
        @include("PkgCreationTache::tache._$tache_viewType")
        @endif
    </section>
    @show
</div>