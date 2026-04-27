{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'labelProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'labelProjet.index' }}', 
        filterFormSelector: '#labelProjet-crud-filter-form',
        crudSelector: '#labelProjet-crud',
        tableSelector: '#labelProjet-data-container',
        formSelector: '#labelProjetForm',
        indexUrl: '{{ route('labelProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('labelProjets.create') }}',
        editUrl: '{{ route('labelProjets.edit',  ['labelProjet' => ':id']) }}',
        fieldMetaUrl: '{{ route('labelProjets.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('labelProjets.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('labelProjets.show',  ['labelProjet' => ':id']) }}',
        getEntityUrl: '{{ route("labelProjets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('labelProjets.store') }}', 
        updateAttributesUrl: '{{ route('labelProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('labelProjets.destroy',  ['labelProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-labelProjet')),
        calculationUrl:  '{{ route('labelProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::labelProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::labelProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $labelProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="labelProjet-crud" class="crud">
    @section('labelProjet-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::labelProjet.singular");
    @endphp
    <x-crud-header 
        id="labelProjet-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $labelProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('labelProjet-crud-table')
    <section id="labelProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('labelProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$labelProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$labelProjet_instance"
                                    :createPermission="'create-labelProjet'"
                                    :createRoute="route('labelProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-labelProjet'"
                                    :importRoute="route('labelProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-labelProjet'"
                                    :exportXlsxRoute="route('labelProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('labelProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$labelProjet_viewTypes"
                                    :viewType="$labelProjet_viewType"
                                    :total="$labelProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('labelProjet-crud-filters')
                @if(!empty($labelProjets_total) &&  $labelProjets_total > 10)
                <div class="card-header">
                    <form id="labelProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($labelProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($labelProjets_filters as $filter)
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
                        @section('labelProjet-crud-search-bar')
                        <div id="labelProjet-crud-search-bar"
                            class="{{ count($labelProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('labelProjets_search')"
                                name="labelProjets_search"
                                id="labelProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="labelProjet-data-container" class="data-container">
                    @if($labelProjet_viewType != "widgets")
                    @include("PkgCreationProjet::labelProjet._$labelProjet_viewType")
                    @endif
                </div>
                @section('labelProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-labelProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('labelProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-labelProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('labelProjets.bulkDelete') }}" 
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
     <section id="labelProjet-data-container-out" >
        @if($labelProjet_viewType == "widgets")
        @include("PkgCreationProjet::labelProjet._$labelProjet_viewType")
        @endif
    </section>
    @show
</div>