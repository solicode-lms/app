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
        entity_name: 'niveauxScolaire',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'niveauxScolaire.index' }}', 
        filterFormSelector: '#niveauxScolaire-crud-filter-form',
        crudSelector: '#niveauxScolaire-crud',
        tableSelector: '#niveauxScolaire-data-container',
        formSelector: '#niveauxScolaireForm',
        indexUrl: '{{ route('niveauxScolaires.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('niveauxScolaires.create') }}',
        editUrl: '{{ route('niveauxScolaires.edit',  ['niveauxScolaire' => ':id']) }}',
        showUrl: '{{ route('niveauxScolaires.show',  ['niveauxScolaire' => ':id']) }}',
        getEntityUrl: '{{ route("niveauxScolaires.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('niveauxScolaires.store') }}', 
        updateAttributesUrl: '{{ route('niveauxScolaires.updateAttributes') }}', 
        deleteUrl: '{{ route('niveauxScolaires.destroy',  ['niveauxScolaire' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-niveauxScolaire')),
        calculationUrl:  '{{ route('niveauxScolaires.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::niveauxScolaire.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::niveauxScolaire.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $niveauxScolaire_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="niveauxScolaire-crud" class="crud">
    @section('niveauxScolaire-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::niveauxScolaire.singular");
    @endphp
    <x-crud-header 
        id="niveauxScolaire-crud-header" icon="fas fa-award"  
        iconColor="text-info"
        title="{{ $niveauxScolaire_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauxScolaire-crud-table')
    <section id="niveauxScolaire-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauxScolaire-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$niveauxScolaires_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$niveauxScolaire_instance"
                                    :createPermission="'create-niveauxScolaire'"
                                    :createRoute="route('niveauxScolaires.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-niveauxScolaire'"
                                    :importRoute="route('niveauxScolaires.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-niveauxScolaire'"
                                    :exportXlsxRoute="route('niveauxScolaires.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('niveauxScolaires.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$niveauxScolaire_viewTypes"
                                    :viewType="$niveauxScolaire_viewType"
                                    :total="$niveauxScolaires_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('niveauxScolaire-crud-filters')
                @if(!empty($niveauxScolaires_total) &&  $niveauxScolaires_total > 5)
                <div class="card-header">
                    <form id="niveauxScolaire-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauxScolaires_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauxScolaires_filters as $filter)
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
                        @section('niveauxScolaire-crud-search-bar')
                        <div id="niveauxScolaire-crud-search-bar"
                            class="{{ count($niveauxScolaires_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauxScolaires_search')"
                                name="niveauxScolaires_search"
                                id="niveauxScolaires_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="niveauxScolaire-data-container" class="data-container">
                    @if($niveauxScolaire_viewType != "widgets")
                    @include("PkgApprenants::niveauxScolaire._$niveauxScolaire_viewType")
                    @endif
                </div>
                @section('niveauxScolaire-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-niveauxScolaire")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('niveauxScolaires.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-niveauxScolaire')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('niveauxScolaires.bulkDelete') }}" 
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
     <section id="niveauxScolaire-data-container-out" >
        @if($niveauxScolaire_viewType == "widgets")
        @include("PkgApprenants::niveauxScolaire._$niveauxScolaire_viewType")
        @endif
    </section>
    @show
</div>