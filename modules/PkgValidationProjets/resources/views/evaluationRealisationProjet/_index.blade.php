{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'evaluationRealisationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'evaluationRealisationProjet.index' }}', 
        filterFormSelector: '#evaluationRealisationProjet-crud-filter-form',
        crudSelector: '#evaluationRealisationProjet-crud',
        tableSelector: '#evaluationRealisationProjet-data-container',
        formSelector: '#evaluationRealisationProjetForm',
        indexUrl: '{{ route('evaluationRealisationProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('evaluationRealisationProjets.create') }}',
        editUrl: '{{ route('evaluationRealisationProjets.edit',  ['evaluationRealisationProjet' => ':id']) }}',
        showUrl: '{{ route('evaluationRealisationProjets.show',  ['evaluationRealisationProjet' => ':id']) }}',
        storeUrl: '{{ route('evaluationRealisationProjets.store') }}', 
        updateAttributesUrl: '{{ route('evaluationRealisationProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('evaluationRealisationProjets.destroy',  ['evaluationRealisationProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-evaluationRealisationProjet')),
        calculationUrl:  '{{ route('evaluationRealisationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgValidationProjets::evaluationRealisationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgValidationProjets::evaluationRealisationProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $evaluationRealisationProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="evaluationRealisationProjet-crud" class="crud">
    @section('evaluationRealisationProjet-crud-header')
    @php
        $package = __("PkgValidationProjets::PkgValidationProjets.name");
       $titre = __("PkgValidationProjets::evaluationRealisationProjet.singular");
    @endphp
    <x-crud-header 
        id="evaluationRealisationProjet-crud-header" icon="fa-table"  
        iconColor="text-info"
        title="{{ $evaluationRealisationProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('evaluationRealisationProjet-crud-table')
    <section id="evaluationRealisationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('evaluationRealisationProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$evaluationRealisationProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$evaluationRealisationProjet_instance"
                                    :createPermission="'create-evaluationRealisationProjet'"
                                    :createRoute="route('evaluationRealisationProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-evaluationRealisationProjet'"
                                    :importRoute="route('evaluationRealisationProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-evaluationRealisationProjet'"
                                    :exportXlsxRoute="route('evaluationRealisationProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('evaluationRealisationProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$evaluationRealisationProjet_viewTypes"
                                    :viewType="$evaluationRealisationProjet_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('evaluationRealisationProjet-crud-filters')
                <div class="card-header">
                    <form id="evaluationRealisationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($evaluationRealisationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($evaluationRealisationProjets_filters as $filter)
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
                        @section('evaluationRealisationProjet-crud-search-bar')
                        <div id="evaluationRealisationProjet-crud-search-bar"
                            class="{{ count($evaluationRealisationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('evaluationRealisationProjets_search')"
                                name="evaluationRealisationProjets_search"
                                id="evaluationRealisationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="evaluationRealisationProjet-data-container" class="data-container">
                    @if($evaluationRealisationProjet_viewType != "widgets")
                    @include("PkgValidationProjets::evaluationRealisationProjet._$evaluationRealisationProjet_viewType")
                    @endif
                </div>
                @section('evaluationRealisationProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-evaluationRealisationProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('evaluationRealisationProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-evaluationRealisationProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('evaluationRealisationProjets.bulkDelete') }}" 
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
     <section id="evaluationRealisationProjet-data-container-out" >
        @if($evaluationRealisationProjet_viewType == "widgets")
        @include("PkgValidationProjets::evaluationRealisationProjet._$evaluationRealisationProjet_viewType")
        @endif
    </section>
    @show
</div>