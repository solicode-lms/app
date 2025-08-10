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
        entity_name: 'tacheAffectation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'tacheAffectation.index' }}', 
        filterFormSelector: '#tacheAffectation-crud-filter-form',
        crudSelector: '#tacheAffectation-crud',
        tableSelector: '#tacheAffectation-data-container',
        formSelector: '#tacheAffectationForm',
        indexUrl: '{{ route('tacheAffectations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('tacheAffectations.create') }}',
        editUrl: '{{ route('tacheAffectations.edit',  ['tacheAffectation' => ':id']) }}',
        showUrl: '{{ route('tacheAffectations.show',  ['tacheAffectation' => ':id']) }}',
        getEntityUrl: '{{ route("tacheAffectations.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('tacheAffectations.store') }}', 
        updateAttributesUrl: '{{ route('tacheAffectations.updateAttributes') }}', 
        deleteUrl: '{{ route('tacheAffectations.destroy',  ['tacheAffectation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-tacheAffectation')),
        calculationUrl:  '{{ route('tacheAffectations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationTache::tacheAffectation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationTache::tacheAffectation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $tacheAffectation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="tacheAffectation-crud" class="crud">
    @section('tacheAffectation-crud-header')
    @php
        $package = __("PkgRealisationTache::PkgRealisationTache.name");
       $titre = __("PkgRealisationTache::tacheAffectation.singular");
    @endphp
    <x-crud-header 
        id="tacheAffectation-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $tacheAffectation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('tacheAffectation-crud-table')
    <section id="tacheAffectation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('tacheAffectation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$tacheAffectations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$tacheAffectation_instance"
                                    :createPermission="'create-tacheAffectation'"
                                    :createRoute="route('tacheAffectations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-tacheAffectation'"
                                    :importRoute="route('tacheAffectations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-tacheAffectation'"
                                    :exportXlsxRoute="route('tacheAffectations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('tacheAffectations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$tacheAffectation_viewTypes"
                                    :viewType="$tacheAffectation_viewType"
                                    :total="$tacheAffectations_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('tacheAffectation-crud-filters')
                @if(!empty($tacheAffectations_total) &&  $tacheAffectations_total > 5)
                <div class="card-header">
                    <form id="tacheAffectation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($tacheAffectations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($tacheAffectations_filters as $filter)
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
                        @section('tacheAffectation-crud-search-bar')
                        <div id="tacheAffectation-crud-search-bar"
                            class="{{ count($tacheAffectations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('tacheAffectations_search')"
                                name="tacheAffectations_search"
                                id="tacheAffectations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="tacheAffectation-data-container" class="data-container">
                    @if($tacheAffectation_viewType != "widgets")
                    @include("PkgRealisationTache::tacheAffectation._$tacheAffectation_viewType")
                    @endif
                </div>
                @section('tacheAffectation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-tacheAffectation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('tacheAffectations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-tacheAffectation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('tacheAffectations.bulkDelete') }}" 
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
     <section id="tacheAffectation-data-container-out" >
        @if($tacheAffectation_viewType == "widgets")
        @include("PkgRealisationTache::tacheAffectation._$tacheAffectation_viewType")
        @endif
    </section>
    @show
</div>