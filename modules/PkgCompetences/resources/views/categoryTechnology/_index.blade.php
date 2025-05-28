{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'categoryTechnology',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'categoryTechnology.index' }}', 
        filterFormSelector: '#categoryTechnology-crud-filter-form',
        crudSelector: '#categoryTechnology-crud',
        tableSelector: '#categoryTechnology-data-container',
        formSelector: '#categoryTechnologyForm',
        indexUrl: '{{ route('categoryTechnologies.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('categoryTechnologies.create') }}',
        editUrl: '{{ route('categoryTechnologies.edit',  ['categoryTechnology' => ':id']) }}',
        showUrl: '{{ route('categoryTechnologies.show',  ['categoryTechnology' => ':id']) }}',
        storeUrl: '{{ route('categoryTechnologies.store') }}', 
        updateAttributesUrl: '{{ route('categoryTechnologies.updateAttributes') }}', 
        deleteUrl: '{{ route('categoryTechnologies.destroy',  ['categoryTechnology' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-categoryTechnology')),
        calculationUrl:  '{{ route('categoryTechnologies.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $categoryTechnology_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="categoryTechnology-crud" class="crud">
    @section('categoryTechnology-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::categoryTechnology.singular");
    @endphp
    <x-crud-header 
        id="categoryTechnology-crud-header" icon="fas fa-tags"  
        iconColor="text-info"
        title="{{ $categoryTechnology_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('categoryTechnology-crud-table')
    <section id="categoryTechnology-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('categoryTechnology-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$categoryTechnologies_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$categoryTechnology_instance"
                                    :createPermission="'create-categoryTechnology'"
                                    :createRoute="route('categoryTechnologies.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-categoryTechnology'"
                                    :importRoute="route('categoryTechnologies.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-categoryTechnology'"
                                    :exportXlsxRoute="route('categoryTechnologies.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('categoryTechnologies.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$categoryTechnology_viewTypes"
                                    :viewType="$categoryTechnology_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('categoryTechnology-crud-filters')
                <div class="card-header">
                    <form id="categoryTechnology-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($categoryTechnologies_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($categoryTechnologies_filters as $filter)
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
                        @section('categoryTechnology-crud-search-bar')
                        <div id="categoryTechnology-crud-search-bar"
                            class="{{ count($categoryTechnologies_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('categoryTechnologies_search')"
                                name="categoryTechnologies_search"
                                id="categoryTechnologies_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="categoryTechnology-data-container" class="data-container">
                    @if($categoryTechnology_viewType != "widgets")
                    @include("PkgCompetences::categoryTechnology._$categoryTechnology_viewType")
                    @endif
                </div>
                @section('categoryTechnology-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-categoryTechnology")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('categoryTechnologies.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-categoryTechnology')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('categoryTechnologies.bulkDelete') }}" 
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
     <section id="categoryTechnology-data-container-out" >
        @if($categoryTechnology_viewType == "widgets")
        @include("PkgCompetences::categoryTechnology._$categoryTechnology_viewType")
        @endif
    </section>
    @show
</div>