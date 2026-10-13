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
        entity_name: 'logActiviteFormateur',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'logActiviteFormateur.index' }}', 
        filterFormSelector: '#logActiviteFormateur-crud-filter-form',
        crudSelector: '#logActiviteFormateur-crud',
        tableSelector: '#logActiviteFormateur-data-container',
        formSelector: '#logActiviteFormateurForm',
        indexUrl: '{{ route('logActiviteFormateurs.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('logActiviteFormateurs.create') }}',
        editUrl: '{{ route('logActiviteFormateurs.edit',  ['logActiviteFormateur' => ':id']) }}',
        fieldMetaUrl: '{{ route('logActiviteFormateurs.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('logActiviteFormateurs.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('logActiviteFormateurs.show',  ['logActiviteFormateur' => ':id']) }}',
        getEntityUrl: '{{ route("logActiviteFormateurs.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('logActiviteFormateurs.store') }}', 
        updateAttributesUrl: '{{ route('logActiviteFormateurs.updateAttributes') }}', 
        deleteUrl: '{{ route('logActiviteFormateurs.destroy',  ['logActiviteFormateur' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-logActiviteFormateur')),
        calculationUrl:  '{{ route('logActiviteFormateurs.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgTableauBord::logActiviteFormateur.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgTableauBord::logActiviteFormateur.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $logActiviteFormateur_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="logActiviteFormateur-crud" class="crud">
    @section('logActiviteFormateur-crud-header')
    @php
        $package = __("PkgTableauBord::PkgTableauBord.name");
       $titre = __("PkgTableauBord::logActiviteFormateur.singular");
    @endphp
    <x-crud-header 
        id="logActiviteFormateur-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $logActiviteFormateur_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('logActiviteFormateur-crud-table')
    <section id="logActiviteFormateur-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('logActiviteFormateur-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$logActiviteFormateurs_stats"
                            />
                            <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$logActiviteFormateur_instance"
                                    :createPermission="'create-logActiviteFormateur'"
                                    :createRoute="route('logActiviteFormateurs.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-logActiviteFormateur'"
                                    :importRoute="route('logActiviteFormateurs.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-logActiviteFormateur'"
                                    :exportXlsxRoute="route('logActiviteFormateurs.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('logActiviteFormateurs.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$logActiviteFormateur_viewTypes"
                                    :viewType="$logActiviteFormateur_viewType"
                                    :total="$logActiviteFormateurs_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('logActiviteFormateur-crud-filters')
                <div class="card-header">
                    <form id="logActiviteFormateur-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($logActiviteFormateurs_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($logActiviteFormateurs_filters as $filter)
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
                        @section('logActiviteFormateur-crud-search-bar')
                        <div id="logActiviteFormateur-crud-search-bar"
                            class="{{ count($logActiviteFormateurs_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('logActiviteFormateurs_search')"
                                name="logActiviteFormateurs_search"
                                id="logActiviteFormateurs_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="logActiviteFormateur-data-container" class="data-container">
                    @if($logActiviteFormateur_viewType != "widgets")
                    @include("PkgTableauBord::logActiviteFormateur._$logActiviteFormateur_viewType")
                    @endif
                </div>
                @section('logActiviteFormateur-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-logActiviteFormateur")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('logActiviteFormateurs.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-logActiviteFormateur')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('logActiviteFormateurs.bulkDelete') }}" 
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
     <section id="logActiviteFormateur-data-container-out" >
        @if($logActiviteFormateur_viewType == "widgets")
        @include("PkgTableauBord::logActiviteFormateur._$logActiviteFormateur_viewType")
        @endif
    </section>
    @show
</div>