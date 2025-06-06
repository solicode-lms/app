{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'realisationFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationFormation.index' }}', 
        filterFormSelector: '#realisationFormation-crud-filter-form',
        crudSelector: '#realisationFormation-crud',
        tableSelector: '#realisationFormation-data-container',
        formSelector: '#realisationFormationForm',
        indexUrl: '{{ route('realisationFormations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationFormations.create') }}',
        editUrl: '{{ route('realisationFormations.edit',  ['realisationFormation' => ':id']) }}',
        showUrl: '{{ route('realisationFormations.show',  ['realisationFormation' => ':id']) }}',
        storeUrl: '{{ route('realisationFormations.store') }}', 
        updateAttributesUrl: '{{ route('realisationFormations.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationFormations.destroy',  ['realisationFormation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationFormation')),
        calculationUrl:  '{{ route('realisationFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::realisationFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::realisationFormation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationFormation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationFormation-crud" class="crud">
    @section('realisationFormation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::realisationFormation.singular");
    @endphp
    <x-crud-header 
        id="realisationFormation-crud-header" icon="fas fa-coffee"  
        iconColor="text-info"
        title="{{ $realisationFormation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationFormation-crud-table')
    <section id="realisationFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationFormation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationFormations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationFormation_instance"
                                    :createPermission="'create-realisationFormation'"
                                    :createRoute="route('realisationFormations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationFormation'"
                                    :importRoute="route('realisationFormations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationFormation'"
                                    :exportXlsxRoute="route('realisationFormations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationFormations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationFormation_viewTypes"
                                    :viewType="$realisationFormation_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationFormation-crud-filters')
                <div class="card-header">
                    <form id="realisationFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationFormations_filters as $filter)
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
                        @section('realisationFormation-crud-search-bar')
                        <div id="realisationFormation-crud-search-bar"
                            class="{{ count($realisationFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationFormations_search')"
                                name="realisationFormations_search"
                                id="realisationFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="realisationFormation-data-container" class="data-container">
                    @if($realisationFormation_viewType != "widgets")
                    @include("PkgAutoformation::realisationFormation._$realisationFormation_viewType")
                    @endif
                </div>
                @section('realisationFormation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationFormation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationFormations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationFormation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationFormations.bulkDelete') }}" 
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
     <section id="realisationFormation-data-container-out" >
        @if($realisationFormation_viewType == "widgets")
        @include("PkgAutoformation::realisationFormation._$realisationFormation_viewType")
        @endif
    </section>
    @show
</div>