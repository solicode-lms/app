{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'transfertCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'transfertCompetence.index' }}', 
        filterFormSelector: '#transfertCompetence-crud-filter-form',
        crudSelector: '#transfertCompetence-crud',
        tableSelector: '#transfertCompetence-data-container',
        formSelector: '#transfertCompetenceForm',
        indexUrl: '{{ route('transfertCompetences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('transfertCompetences.create') }}',
        editUrl: '{{ route('transfertCompetences.edit',  ['transfertCompetence' => ':id']) }}',
        showUrl: '{{ route('transfertCompetences.show',  ['transfertCompetence' => ':id']) }}',
        storeUrl: '{{ route('transfertCompetences.store') }}', 
        updateAttributesUrl: '{{ route('transfertCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('transfertCompetences.destroy',  ['transfertCompetence' => ':id']) }}', 
        calculationUrl:  '{{ route('transfertCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $transfertCompetence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="transfertCompetence-crud" class="crud">
    @section('transfertCompetence-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::transfertCompetence.singular");
    @endphp
    <x-crud-header 
        id="transfertCompetence-crud-header" icon="fas fa-book-open"  
        iconColor="text-info"
        title="{{ $transfertCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('transfertCompetence-crud-table')
    <section id="transfertCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('transfertCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$transfertCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$transfertCompetence_instance"
                                    :createPermission="'create-transfertCompetence'"
                                    :createRoute="route('transfertCompetences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-transfertCompetence'"
                                    :importRoute="route('transfertCompetences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-transfertCompetence'"
                                    :exportXlsxRoute="route('transfertCompetences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('transfertCompetences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$transfertCompetence_viewTypes"
                                    :viewType="$transfertCompetence_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('transfertCompetence-crud-filters')
                <div class="card-header">
                    <form id="transfertCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($transfertCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($transfertCompetences_filters as $filter)
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
                        @section('transfertCompetence-crud-search-bar')
                        <div id="transfertCompetence-crud-search-bar"
                            class="{{ count($transfertCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('transfertCompetences_search')"
                                name="transfertCompetences_search"
                                id="transfertCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="transfertCompetence-data-container" class="data-container">
                    @if($transfertCompetence_viewType == "table")
                    @include("PkgCreationProjet::transfertCompetence._$transfertCompetence_viewType")
                    @endif
                </div>
                @section('transfertCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-transfertCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('transfertCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-transfertCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('transfertCompetences.bulkDelete') }}" 
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
     <section id="transfertCompetence-data-container-out" >
        @if($transfertCompetence_viewType == "widgets")
        @include("PkgCreationProjet::transfertCompetence._$transfertCompetence_viewType")
        @endif
    </section>
    @show
</div>