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
        entity_name: 'nationalite',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'nationalite.index' }}', 
        filterFormSelector: '#nationalite-crud-filter-form',
        crudSelector: '#nationalite-crud',
        tableSelector: '#nationalite-data-container',
        formSelector: '#nationaliteForm',
        indexUrl: '{{ route('nationalites.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('nationalites.create') }}',
        editUrl: '{{ route('nationalites.edit',  ['nationalite' => ':id']) }}',
        showUrl: '{{ route('nationalites.show',  ['nationalite' => ':id']) }}',
        getEntityUrl: '{{ route("nationalites.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('nationalites.store') }}', 
        updateAttributesUrl: '{{ route('nationalites.updateAttributes') }}', 
        deleteUrl: '{{ route('nationalites.destroy',  ['nationalite' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-nationalite')),
        calculationUrl:  '{{ route('nationalites.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::nationalite.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::nationalite.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $nationalite_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="nationalite-crud" class="crud">
    @section('nationalite-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::nationalite.singular");
    @endphp
    <x-crud-header 
        id="nationalite-crud-header" icon="fas fa-map-marked-alt"  
        iconColor="text-info"
        title="{{ $nationalite_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('nationalite-crud-table')
    <section id="nationalite-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('nationalite-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$nationalites_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$nationalite_instance"
                                    :createPermission="'create-nationalite'"
                                    :createRoute="route('nationalites.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-nationalite'"
                                    :importRoute="route('nationalites.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-nationalite'"
                                    :exportXlsxRoute="route('nationalites.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('nationalites.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$nationalite_viewTypes"
                                    :viewType="$nationalite_viewType"
                                    :total="$nationalites_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('nationalite-crud-filters')
                @if(!empty($nationalites_total) &&  $nationalites_total > 5)
                <div class="card-header">
                    <form id="nationalite-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($nationalites_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($nationalites_filters as $filter)
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
                        @section('nationalite-crud-search-bar')
                        <div id="nationalite-crud-search-bar"
                            class="{{ count($nationalites_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('nationalites_search')"
                                name="nationalites_search"
                                id="nationalites_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="nationalite-data-container" class="data-container">
                    @if($nationalite_viewType != "widgets")
                    @include("PkgApprenants::nationalite._$nationalite_viewType")
                    @endif
                </div>
                @section('nationalite-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-nationalite")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('nationalites.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-nationalite')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('nationalites.bulkDelete') }}" 
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
     <section id="nationalite-data-container-out" >
        @if($nationalite_viewType == "widgets")
        @include("PkgApprenants::nationalite._$nationalite_viewType")
        @endif
    </section>
    @show
</div>