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
        entity_name: 'etatsRealisationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatsRealisationProjet.index' }}', 
        filterFormSelector: '#etatsRealisationProjet-crud-filter-form',
        crudSelector: '#etatsRealisationProjet-crud',
        tableSelector: '#etatsRealisationProjet-data-container',
        formSelector: '#etatsRealisationProjetForm',
        indexUrl: '{{ route('etatsRealisationProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatsRealisationProjets.create') }}',
        editUrl: '{{ route('etatsRealisationProjets.edit',  ['etatsRealisationProjet' => ':id']) }}',
        showUrl: '{{ route('etatsRealisationProjets.show',  ['etatsRealisationProjet' => ':id']) }}',
        getEntityUrl: '{{ route("etatsRealisationProjets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatsRealisationProjets.store') }}', 
        updateAttributesUrl: '{{ route('etatsRealisationProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('etatsRealisationProjets.destroy',  ['etatsRealisationProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatsRealisationProjet')),
        calculationUrl:  '{{ route('etatsRealisationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::etatsRealisationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::etatsRealisationProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatsRealisationProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatsRealisationProjet-crud" class="crud">
    @section('etatsRealisationProjet-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::etatsRealisationProjet.singular");
    @endphp
    <x-crud-header 
        id="etatsRealisationProjet-crud-header" icon="fas fa-check"  
        iconColor="text-info"
        title="{{ $etatsRealisationProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatsRealisationProjet-crud-table')
    <section id="etatsRealisationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatsRealisationProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatsRealisationProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatsRealisationProjet_instance"
                                    :createPermission="'create-etatsRealisationProjet'"
                                    :createRoute="route('etatsRealisationProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatsRealisationProjet'"
                                    :importRoute="route('etatsRealisationProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatsRealisationProjet'"
                                    :exportXlsxRoute="route('etatsRealisationProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatsRealisationProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatsRealisationProjet_viewTypes"
                                    :viewType="$etatsRealisationProjet_viewType"
                                    :total="$etatsRealisationProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatsRealisationProjet-crud-filters')
                @if(!empty($etatsRealisationProjets_total) &&  $etatsRealisationProjets_total > 5)
                <div class="card-header">
                    <form id="etatsRealisationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatsRealisationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatsRealisationProjets_filters as $filter)
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
                        @section('etatsRealisationProjet-crud-search-bar')
                        <div id="etatsRealisationProjet-crud-search-bar"
                            class="{{ count($etatsRealisationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatsRealisationProjets_search')"
                                name="etatsRealisationProjets_search"
                                id="etatsRealisationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatsRealisationProjet-data-container" class="data-container">
                    @if($etatsRealisationProjet_viewType != "widgets")
                    @include("PkgRealisationProjets::etatsRealisationProjet._$etatsRealisationProjet_viewType")
                    @endif
                </div>
                @section('etatsRealisationProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatsRealisationProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatsRealisationProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatsRealisationProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatsRealisationProjets.bulkDelete') }}" 
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
     <section id="etatsRealisationProjet-data-container-out" >
        @if($etatsRealisationProjet_viewType == "widgets")
        @include("PkgRealisationProjets::etatsRealisationProjet._$etatsRealisationProjet_viewType")
        @endif
    </section>
    @show
</div>