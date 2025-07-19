{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'uniteApprentissage',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'uniteApprentissage.index' }}', 
        filterFormSelector: '#uniteApprentissage-crud-filter-form',
        crudSelector: '#uniteApprentissage-crud',
        tableSelector: '#uniteApprentissage-data-container',
        formSelector: '#uniteApprentissageForm',
        indexUrl: '{{ route('uniteApprentissages.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('uniteApprentissages.create') }}',
        editUrl: '{{ route('uniteApprentissages.edit',  ['uniteApprentissage' => ':id']) }}',
        showUrl: '{{ route('uniteApprentissages.show',  ['uniteApprentissage' => ':id']) }}',
        storeUrl: '{{ route('uniteApprentissages.store') }}', 
        updateAttributesUrl: '{{ route('uniteApprentissages.updateAttributes') }}', 
        deleteUrl: '{{ route('uniteApprentissages.destroy',  ['uniteApprentissage' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-uniteApprentissage')),
        calculationUrl:  '{{ route('uniteApprentissages.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::uniteApprentissage.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::uniteApprentissage.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $uniteApprentissage_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="uniteApprentissage-crud" class="crud">
    @section('uniteApprentissage-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::uniteApprentissage.singular");
    @endphp
    <x-crud-header 
        id="uniteApprentissage-crud-header" icon="fas fa-puzzle-piece"  
        iconColor="text-info"
        title="{{ $uniteApprentissage_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('uniteApprentissage-crud-table')
    <section id="uniteApprentissage-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('uniteApprentissage-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$uniteApprentissages_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$uniteApprentissage_instance"
                                    :createPermission="'create-uniteApprentissage'"
                                    :createRoute="route('uniteApprentissages.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-uniteApprentissage'"
                                    :importRoute="route('uniteApprentissages.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-uniteApprentissage'"
                                    :exportXlsxRoute="route('uniteApprentissages.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('uniteApprentissages.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$uniteApprentissage_viewTypes"
                                    :viewType="$uniteApprentissage_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('uniteApprentissage-crud-filters')
                <div class="card-header">
                    <form id="uniteApprentissage-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($uniteApprentissages_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($uniteApprentissages_filters as $filter)
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
                        @section('uniteApprentissage-crud-search-bar')
                        <div id="uniteApprentissage-crud-search-bar"
                            class="{{ count($uniteApprentissages_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('uniteApprentissages_search')"
                                name="uniteApprentissages_search"
                                id="uniteApprentissages_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="uniteApprentissage-data-container" class="data-container">
                    @if($uniteApprentissage_viewType != "widgets")
                    @include("PkgCompetences::uniteApprentissage._$uniteApprentissage_viewType")
                    @endif
                </div>
                @section('uniteApprentissage-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-uniteApprentissage")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('uniteApprentissages.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-uniteApprentissage')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('uniteApprentissages.bulkDelete') }}" 
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
     <section id="uniteApprentissage-data-container-out" >
        @if($uniteApprentissage_viewType == "widgets")
        @include("PkgCompetences::uniteApprentissage._$uniteApprentissage_viewType")
        @endif
    </section>
    @show
</div>