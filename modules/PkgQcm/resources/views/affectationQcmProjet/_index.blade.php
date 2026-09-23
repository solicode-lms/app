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
        entity_name: 'affectationQcmProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'affectationQcmProjet.index' }}', 
        filterFormSelector: '#affectationQcmProjet-crud-filter-form',
        crudSelector: '#affectationQcmProjet-crud',
        tableSelector: '#affectationQcmProjet-data-container',
        formSelector: '#affectationQcmProjetForm',
        indexUrl: '{{ route('affectationQcmProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('affectationQcmProjets.create') }}',
        editUrl: '{{ route('affectationQcmProjets.edit',  ['affectationQcmProjet' => ':id']) }}',
        fieldMetaUrl: '{{ route('affectationQcmProjets.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('affectationQcmProjets.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('affectationQcmProjets.show',  ['affectationQcmProjet' => ':id']) }}',
        getEntityUrl: '{{ route("affectationQcmProjets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('affectationQcmProjets.store') }}', 
        updateAttributesUrl: '{{ route('affectationQcmProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('affectationQcmProjets.destroy',  ['affectationQcmProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-affectationQcmProjet')),
        calculationUrl:  '{{ route('affectationQcmProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::affectationQcmProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::affectationQcmProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $affectationQcmProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="affectationQcmProjet-crud" class="crud">
    @section('affectationQcmProjet-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::affectationQcmProjet.singular");
    @endphp
    <x-crud-header 
        id="affectationQcmProjet-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $affectationQcmProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('affectationQcmProjet-crud-table')
    <section id="affectationQcmProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('affectationQcmProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$affectationQcmProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$affectationQcmProjet_instance"
                                    :createPermission="'create-affectationQcmProjet'"
                                    :createRoute="route('affectationQcmProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-affectationQcmProjet'"
                                    :importRoute="route('affectationQcmProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-affectationQcmProjet'"
                                    :exportXlsxRoute="route('affectationQcmProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('affectationQcmProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$affectationQcmProjet_viewTypes"
                                    :viewType="$affectationQcmProjet_viewType"
                                    :total="$affectationQcmProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('affectationQcmProjet-crud-filters')
                @if(!empty($affectationQcmProjets_total) &&  $affectationQcmProjets_total > 10)
                <div class="card-header">
                    <form id="affectationQcmProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($affectationQcmProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($affectationQcmProjets_filters as $filter)
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
                        @section('affectationQcmProjet-crud-search-bar')
                        <div id="affectationQcmProjet-crud-search-bar"
                            class="{{ count($affectationQcmProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('affectationQcmProjets_search')"
                                name="affectationQcmProjets_search"
                                id="affectationQcmProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="affectationQcmProjet-data-container" class="data-container">
                    @if($affectationQcmProjet_viewType != "widgets")
                    @include("PkgQcm::affectationQcmProjet._$affectationQcmProjet_viewType")
                    @endif
                </div>
                @section('affectationQcmProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-affectationQcmProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('affectationQcmProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-affectationQcmProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('affectationQcmProjets.bulkDelete') }}" 
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
     <section id="affectationQcmProjet-data-container-out" >
        @if($affectationQcmProjet_viewType == "widgets")
        @include("PkgQcm::affectationQcmProjet._$affectationQcmProjet_viewType")
        @endif
    </section>
    @show
</div>