{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : true,
        entity_name: 'realisationMicroCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationMicroCompetence.index' }}', 
        filterFormSelector: '#realisationMicroCompetence-crud-filter-form',
        crudSelector: '#realisationMicroCompetence-crud',
        tableSelector: '#realisationMicroCompetence-data-container',
        formSelector: '#realisationMicroCompetenceForm',
        indexUrl: '{{ route('realisationMicroCompetences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationMicroCompetences.create') }}',
        editUrl: '{{ route('realisationMicroCompetences.edit',  ['realisationMicroCompetence' => ':id']) }}',
        showUrl: '{{ route('realisationMicroCompetences.show',  ['realisationMicroCompetence' => ':id']) }}',
        getEntityUrl: '{{ route("realisationMicroCompetences.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('realisationMicroCompetences.store') }}', 
        updateAttributesUrl: '{{ route('realisationMicroCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationMicroCompetences.destroy',  ['realisationMicroCompetence' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationMicroCompetence')),
        calculationUrl:  '{{ route('realisationMicroCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::realisationMicroCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationMicroCompetence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationMicroCompetence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationMicroCompetence-crud" class="crud">
    @section('realisationMicroCompetence-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::realisationMicroCompetence.singular");
    @endphp
    <x-crud-header 
        id="realisationMicroCompetence-crud-header" icon="fas fa-certificate"  
        iconColor="text-info"
        title="{{ $realisationMicroCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationMicroCompetence-crud-table')
    <section id="realisationMicroCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationMicroCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationMicroCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationMicroCompetence_instance"
                                    :createPermission="'create-realisationMicroCompetence'"
                                    :createRoute="route('realisationMicroCompetences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationMicroCompetence'"
                                    :importRoute="route('realisationMicroCompetences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationMicroCompetence'"
                                    :exportXlsxRoute="route('realisationMicroCompetences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationMicroCompetences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationMicroCompetence_viewTypes"
                                    :viewType="$realisationMicroCompetence_viewType"
                                    :total="$realisationMicroCompetences_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationMicroCompetence-crud-filters')
                @if(!empty($realisationMicroCompetences_total) &&  $realisationMicroCompetences_total > 5)
                <div class="card-header">
                    <form id="realisationMicroCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationMicroCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationMicroCompetences_filters as $filter)
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
                        @section('realisationMicroCompetence-crud-search-bar')
                        <div id="realisationMicroCompetence-crud-search-bar"
                            class="{{ count($realisationMicroCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationMicroCompetences_search')"
                                name="realisationMicroCompetences_search"
                                id="realisationMicroCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationMicroCompetence-data-container" class="data-container">
                    @if($realisationMicroCompetence_viewType != "widgets")
                    @include("PkgApprentissage::realisationMicroCompetence._$realisationMicroCompetence_viewType")
                    @endif
                </div>
                @section('realisationMicroCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationMicroCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationMicroCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationMicroCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationMicroCompetences.bulkDelete') }}" 
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
     <section id="realisationMicroCompetence-data-container-out" >
        @if($realisationMicroCompetence_viewType == "widgets")
        @include("PkgApprentissage::realisationMicroCompetence._$realisationMicroCompetence_viewType")
        @endif
    </section>
    @show
</div>