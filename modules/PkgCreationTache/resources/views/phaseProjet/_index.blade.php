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
        entity_name: 'phaseProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'phaseProjet.index' }}', 
        filterFormSelector: '#phaseProjet-crud-filter-form',
        crudSelector: '#phaseProjet-crud',
        tableSelector: '#phaseProjet-data-container',
        formSelector: '#phaseProjetForm',
        indexUrl: '{{ route('phaseProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('phaseProjets.create') }}',
        editUrl: '{{ route('phaseProjets.edit',  ['phaseProjet' => ':id']) }}',
        fieldMetaUrl: '{{ route('phaseProjets.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('phaseProjets.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('phaseProjets.show',  ['phaseProjet' => ':id']) }}',
        getEntityUrl: '{{ route("phaseProjets.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('phaseProjets.store') }}', 
        updateAttributesUrl: '{{ route('phaseProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('phaseProjets.destroy',  ['phaseProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-phaseProjet')),
        calculationUrl:  '{{ route('phaseProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationTache::phaseProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationTache::phaseProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $phaseProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="phaseProjet-crud" class="crud">
    @section('phaseProjet-crud-header')
    @php
        $package = __("PkgCreationTache::PkgCreationTache.name");
       $titre = __("PkgCreationTache::phaseProjet.singular");
    @endphp
    <x-crud-header 
        id="phaseProjet-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $phaseProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('phaseProjet-crud-table')
    <section id="phaseProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('phaseProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$phaseProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$phaseProjet_instance"
                                    :createPermission="'create-phaseProjet'"
                                    :createRoute="route('phaseProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-phaseProjet'"
                                    :importRoute="route('phaseProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-phaseProjet'"
                                    :exportXlsxRoute="route('phaseProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('phaseProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$phaseProjet_viewTypes"
                                    :viewType="$phaseProjet_viewType"
                                    :total="$phaseProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('phaseProjet-crud-filters')
                @if(!empty($phaseProjets_total) &&  $phaseProjets_total > 10)
                <div class="card-header">
                    <form id="phaseProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($phaseProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($phaseProjets_filters as $filter)
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
                        @section('phaseProjet-crud-search-bar')
                        <div id="phaseProjet-crud-search-bar"
                            class="{{ count($phaseProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('phaseProjets_search')"
                                name="phaseProjets_search"
                                id="phaseProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="phaseProjet-data-container" class="data-container">
                    @if($phaseProjet_viewType != "widgets")
                    @include("PkgCreationTache::phaseProjet._$phaseProjet_viewType")
                    @endif
                </div>
                @section('phaseProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-phaseProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('phaseProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-phaseProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('phaseProjets.bulkDelete') }}" 
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
     <section id="phaseProjet-data-container-out" >
        @if($phaseProjet_viewType == "widgets")
        @include("PkgCreationTache::phaseProjet._$phaseProjet_viewType")
        @endif
    </section>
    @show
</div>