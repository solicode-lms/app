{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : true,
        entity_name: 'realisationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationProjet.index' }}', 
        filterFormSelector: '#realisationProjet-crud-filter-form',
        crudSelector: '#realisationProjet-crud',
        tableSelector: '#realisationProjet-data-container',
        formSelector: '#realisationProjetForm',
        indexUrl: '{{ route('realisationProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationProjets.create') }}',
        editUrl: '{{ route('realisationProjets.edit',  ['realisationProjet' => ':id']) }}',
        showUrl: '{{ route('realisationProjets.show',  ['realisationProjet' => ':id']) }}',
        storeUrl: '{{ route('realisationProjets.store') }}', 
        updateAttributesUrl: '{{ route('realisationProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationProjets.destroy',  ['realisationProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationProjet')),
        calculationUrl:  '{{ route('realisationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::realisationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::realisationProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationProjet-crud" class="crud">
    @section('realisationProjet-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::realisationProjet.singular");
    @endphp
    <x-crud-header 
        id="realisationProjet-crud-header" icon="fas fa-laptop"  
        iconColor="text-info"
        title="{{ $realisationProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationProjet-crud-table')
    <section id="realisationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationProjet_instance"
                                    :createPermission="'create-realisationProjet'"
                                    :createRoute="route('realisationProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationProjet'"
                                    :importRoute="route('realisationProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationProjet'"
                                    :exportXlsxRoute="route('realisationProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationProjet_viewTypes"
                                    :viewType="$realisationProjet_viewType"
                                    :total="$realisationProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationProjet-crud-filters')
                @if(!empty($realisationProjets_total) &&  $realisationProjets_total > 10)
                <div class="card-header">
                    <form id="realisationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationProjets_filters as $filter)
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
                        @section('realisationProjet-crud-search-bar')
                        <div id="realisationProjet-crud-search-bar"
                            class="{{ count($realisationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationProjets_search')"
                                name="realisationProjets_search"
                                id="realisationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationProjet-data-container" class="data-container">
                    @if($realisationProjet_viewType != "widgets")
                    @include("PkgRealisationProjets::realisationProjet._$realisationProjet_viewType")
                    @endif
                </div>
                @section('realisationProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationProjets.bulkDelete') }}" 
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
     <section id="realisationProjet-data-container-out" >
        @if($realisationProjet_viewType == "widgets")
        @include("PkgRealisationProjets::realisationProjet._$realisationProjet_viewType")
        @endif
    </section>
    @show
</div>