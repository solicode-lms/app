{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'workflowProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'workflowProjet.index' }}', 
        filterFormSelector: '#workflowProjet-crud-filter-form',
        crudSelector: '#workflowProjet-crud',
        tableSelector: '#workflowProjet-data-container',
        formSelector: '#workflowProjetForm',
        indexUrl: '{{ route('workflowProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('workflowProjets.create') }}',
        editUrl: '{{ route('workflowProjets.edit',  ['workflowProjet' => ':id']) }}',
        showUrl: '{{ route('workflowProjets.show',  ['workflowProjet' => ':id']) }}',
        storeUrl: '{{ route('workflowProjets.store') }}', 
        updateAttributesUrl: '{{ route('workflowProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('workflowProjets.destroy',  ['workflowProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-workflowProjet')),
        calculationUrl:  '{{ route('workflowProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::workflowProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::workflowProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $workflowProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="workflowProjet-crud" class="crud">
    @section('workflowProjet-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::workflowProjet.singular");
    @endphp
    <x-crud-header 
        id="workflowProjet-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $workflowProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('workflowProjet-crud-table')
    <section id="workflowProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('workflowProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$workflowProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$workflowProjet_instance"
                                    :createPermission="'create-workflowProjet'"
                                    :createRoute="route('workflowProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-workflowProjet'"
                                    :importRoute="route('workflowProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-workflowProjet'"
                                    :exportXlsxRoute="route('workflowProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('workflowProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$workflowProjet_viewTypes"
                                    :viewType="$workflowProjet_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('workflowProjet-crud-filters')
                <div class="card-header">
                    <form id="workflowProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($workflowProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($workflowProjets_filters as $filter)
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
                        @section('workflowProjet-crud-search-bar')
                        <div id="workflowProjet-crud-search-bar"
                            class="{{ count($workflowProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('workflowProjets_search')"
                                name="workflowProjets_search"
                                id="workflowProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="workflowProjet-data-container" class="data-container">
                    @if($workflowProjet_viewType == "table")
                    @include("PkgRealisationProjets::workflowProjet._$workflowProjet_viewType")
                    @endif
                </div>
                @section('workflowProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-workflowProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('workflowProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-workflowProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('workflowProjets.bulkDelete') }}" 
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
     <section id="workflowProjet-data-container-out" >
        @if($workflowProjet_viewType == "widgets")
        @include("PkgRealisationProjets::workflowProjet._$workflowProjet_viewType")
        @endif
    </section>
    @show
</div>