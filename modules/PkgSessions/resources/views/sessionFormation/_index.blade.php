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
        entity_name: 'sessionFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sessionFormation.index' }}', 
        filterFormSelector: '#sessionFormation-crud-filter-form',
        crudSelector: '#sessionFormation-crud',
        tableSelector: '#sessionFormation-data-container',
        formSelector: '#sessionFormationForm',
        indexUrl: '{{ route('sessionFormations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sessionFormations.create') }}',
        editUrl: '{{ route('sessionFormations.edit',  ['sessionFormation' => ':id']) }}',
        showUrl: '{{ route('sessionFormations.show',  ['sessionFormation' => ':id']) }}',
        getEntityUrl: '{{ route("sessionFormations.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('sessionFormations.store') }}', 
        updateAttributesUrl: '{{ route('sessionFormations.updateAttributes') }}', 
        deleteUrl: '{{ route('sessionFormations.destroy',  ['sessionFormation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-sessionFormation')),
        calculationUrl:  '{{ route('sessionFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgSessions::sessionFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgSessions::sessionFormation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sessionFormation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sessionFormation-crud" class="crud">
    @section('sessionFormation-crud-header')
    @php
        $package = __("PkgSessions::PkgSessions.name");
       $titre = __("PkgSessions::sessionFormation.singular");
    @endphp
    <x-crud-header 
        id="sessionFormation-crud-header" icon="fas fa-map"  
        iconColor="text-info"
        title="{{ $sessionFormation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sessionFormation-crud-table')
    <section id="sessionFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sessionFormation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sessionFormations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sessionFormation_instance"
                                    :createPermission="'create-sessionFormation'"
                                    :createRoute="route('sessionFormations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sessionFormation'"
                                    :importRoute="route('sessionFormations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sessionFormation'"
                                    :exportXlsxRoute="route('sessionFormations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sessionFormations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sessionFormation_viewTypes"
                                    :viewType="$sessionFormation_viewType"
                                    :total="$sessionFormations_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sessionFormation-crud-filters')
                @if(!empty($sessionFormations_total) &&  $sessionFormations_total > 5)
                <div class="card-header">
                    <form id="sessionFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sessionFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sessionFormations_filters as $filter)
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
                        @section('sessionFormation-crud-search-bar')
                        <div id="sessionFormation-crud-search-bar"
                            class="{{ count($sessionFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sessionFormations_search')"
                                name="sessionFormations_search"
                                id="sessionFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="sessionFormation-data-container" class="data-container">
                    @if($sessionFormation_viewType != "widgets")
                    @include("PkgSessions::sessionFormation._$sessionFormation_viewType")
                    @endif
                </div>
                @section('sessionFormation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sessionFormation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sessionFormations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sessionFormation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sessionFormations.bulkDelete') }}" 
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
     <section id="sessionFormation-data-container-out" >
        @if($sessionFormation_viewType == "widgets")
        @include("PkgSessions::sessionFormation._$sessionFormation_viewType")
        @endif
    </section>
    @show
</div>