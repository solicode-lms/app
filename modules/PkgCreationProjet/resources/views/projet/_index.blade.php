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
        entity_name: 'projet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'projet.index' }}', 
        filterFormSelector: '#projet-crud-filter-form',
        crudSelector: '#projet-crud',
        tableSelector: '#projet-data-container',
        formSelector: '#projetForm',
        indexUrl: '{{ route('projets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('projets.create') }}',
        editUrl: '{{ route('projets.edit',  ['projet' => ':id']) }}',
        showUrl: '{{ route('projets.show',  ['projet' => ':id']) }}',
        getEntityUrl: '{{ route("projets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('projets.store') }}', 
        updateAttributesUrl: '{{ route('projets.updateAttributes') }}', 
        deleteUrl: '{{ route('projets.destroy',  ['projet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-projet')),
        calculationUrl:  '{{ route('projets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::projet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::projet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $projet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="projet-crud" class="crud">
    @section('projet-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::projet.singular");
    @endphp
    <x-crud-header 
        id="projet-crud-header" icon="fas fa-rocket"  
        iconColor="text-info"
        title="{{ $projet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('projet-crud-table')
    <section id="projet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('projet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$projets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$projet_instance"
                                    :createPermission="'create-projet'"
                                    :createRoute="route('projets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-projet'"
                                    :importRoute="route('projets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-projet'"
                                    :exportXlsxRoute="route('projets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('projets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$projet_viewTypes"
                                    :viewType="$projet_viewType"
                                    :total="$projets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('projet-crud-filters')
                @if(!empty($projets_total) &&  $projets_total > 5)
                <div class="card-header">
                    <form id="projet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($projets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($projets_filters as $filter)
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
                        @section('projet-crud-search-bar')
                        <div id="projet-crud-search-bar"
                            class="{{ count($projets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('projets_search')"
                                name="projets_search"
                                id="projets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="projet-data-container" class="data-container">
                    @if($projet_viewType != "widgets")
                    @include("PkgCreationProjet::projet._$projet_viewType")
                    @endif
                </div>
                @section('projet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-projet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('projets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-projet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('projets.bulkDelete') }}" 
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
     <section id="projet-data-container-out" >
        @if($projet_viewType == "widgets")
        @include("PkgCreationProjet::projet._$projet_viewType")
        @endif
    </section>
    @show
</div>