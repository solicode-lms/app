{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'workflowChapitre',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'workflowChapitre.index' }}', 
        filterFormSelector: '#workflowChapitre-crud-filter-form',
        crudSelector: '#workflowChapitre-crud',
        tableSelector: '#workflowChapitre-data-container',
        formSelector: '#workflowChapitreForm',
        indexUrl: '{{ route('workflowChapitres.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('workflowChapitres.create') }}',
        editUrl: '{{ route('workflowChapitres.edit',  ['workflowChapitre' => ':id']) }}',
        showUrl: '{{ route('workflowChapitres.show',  ['workflowChapitre' => ':id']) }}',
        storeUrl: '{{ route('workflowChapitres.store') }}', 
        updateAttributesUrl: '{{ route('workflowChapitres.updateAttributes') }}', 
        deleteUrl: '{{ route('workflowChapitres.destroy',  ['workflowChapitre' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-workflowChapitre')),
        calculationUrl:  '{{ route('workflowChapitres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::workflowChapitre.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::workflowChapitre.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $workflowChapitre_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="workflowChapitre-crud" class="crud">
    @section('workflowChapitre-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::workflowChapitre.singular");
    @endphp
    <x-crud-header 
        id="workflowChapitre-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $workflowChapitre_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('workflowChapitre-crud-table')
    <section id="workflowChapitre-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('workflowChapitre-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$workflowChapitres_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$workflowChapitre_instance"
                                    :createPermission="'create-workflowChapitre'"
                                    :createRoute="route('workflowChapitres.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-workflowChapitre'"
                                    :importRoute="route('workflowChapitres.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-workflowChapitre'"
                                    :exportXlsxRoute="route('workflowChapitres.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('workflowChapitres.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$workflowChapitre_viewTypes"
                                    :viewType="$workflowChapitre_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('workflowChapitre-crud-filters')
                <div class="card-header">
                    <form id="workflowChapitre-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($workflowChapitres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($workflowChapitres_filters as $filter)
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
                        @section('workflowChapitre-crud-search-bar')
                        <div id="workflowChapitre-crud-search-bar"
                            class="{{ count($workflowChapitres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('workflowChapitres_search')"
                                name="workflowChapitres_search"
                                id="workflowChapitres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="workflowChapitre-data-container" class="data-container">
                    @if($workflowChapitre_viewType != "widgets")
                    @include("PkgAutoformation::workflowChapitre._$workflowChapitre_viewType")
                    @endif
                </div>
                @section('workflowChapitre-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-workflowChapitre")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('workflowChapitres.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-workflowChapitre')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('workflowChapitres.bulkDelete') }}" 
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
     <section id="workflowChapitre-data-container-out" >
        @if($workflowChapitre_viewType == "widgets")
        @include("PkgAutoformation::workflowChapitre._$workflowChapitre_viewType")
        @endif
    </section>
    @show
</div>