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
        entity_name: 'etatEvaluationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatEvaluationProjet.index' }}', 
        filterFormSelector: '#etatEvaluationProjet-crud-filter-form',
        crudSelector: '#etatEvaluationProjet-crud',
        tableSelector: '#etatEvaluationProjet-data-container',
        formSelector: '#etatEvaluationProjetForm',
        indexUrl: '{{ route('etatEvaluationProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatEvaluationProjets.create') }}',
        editUrl: '{{ route('etatEvaluationProjets.edit',  ['etatEvaluationProjet' => ':id']) }}',
        showUrl: '{{ route('etatEvaluationProjets.show',  ['etatEvaluationProjet' => ':id']) }}',
        getEntityUrl: '{{ route("etatEvaluationProjets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatEvaluationProjets.store') }}', 
        updateAttributesUrl: '{{ route('etatEvaluationProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('etatEvaluationProjets.destroy',  ['etatEvaluationProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatEvaluationProjet')),
        calculationUrl:  '{{ route('etatEvaluationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgEvaluateurs::etatEvaluationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgEvaluateurs::etatEvaluationProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatEvaluationProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatEvaluationProjet-crud" class="crud">
    @section('etatEvaluationProjet-crud-header')
    @php
        $package = __("PkgEvaluateurs::PkgEvaluateurs.name");
       $titre = __("PkgEvaluateurs::etatEvaluationProjet.singular");
    @endphp
    <x-crud-header 
        id="etatEvaluationProjet-crud-header" icon="fa-table"  
        iconColor="text-info"
        title="{{ $etatEvaluationProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatEvaluationProjet-crud-table')
    <section id="etatEvaluationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatEvaluationProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatEvaluationProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatEvaluationProjet_instance"
                                    :createPermission="'create-etatEvaluationProjet'"
                                    :createRoute="route('etatEvaluationProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatEvaluationProjet'"
                                    :importRoute="route('etatEvaluationProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatEvaluationProjet'"
                                    :exportXlsxRoute="route('etatEvaluationProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatEvaluationProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatEvaluationProjet_viewTypes"
                                    :viewType="$etatEvaluationProjet_viewType"
                                    :total="$etatEvaluationProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatEvaluationProjet-crud-filters')
                @if(!empty($etatEvaluationProjets_total) &&  $etatEvaluationProjets_total > 5)
                <div class="card-header">
                    <form id="etatEvaluationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatEvaluationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatEvaluationProjets_filters as $filter)
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
                        @section('etatEvaluationProjet-crud-search-bar')
                        <div id="etatEvaluationProjet-crud-search-bar"
                            class="{{ count($etatEvaluationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatEvaluationProjets_search')"
                                name="etatEvaluationProjets_search"
                                id="etatEvaluationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatEvaluationProjet-data-container" class="data-container">
                    @if($etatEvaluationProjet_viewType != "widgets")
                    @include("PkgEvaluateurs::etatEvaluationProjet._$etatEvaluationProjet_viewType")
                    @endif
                </div>
                @section('etatEvaluationProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatEvaluationProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatEvaluationProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatEvaluationProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatEvaluationProjets.bulkDelete') }}" 
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
     <section id="etatEvaluationProjet-data-container-out" >
        @if($etatEvaluationProjet_viewType == "widgets")
        @include("PkgEvaluateurs::etatEvaluationProjet._$etatEvaluationProjet_viewType")
        @endif
    </section>
    @show
</div>