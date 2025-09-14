{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : true,
        entity_name: 'realisationCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationCompetence.index' }}', 
        filterFormSelector: '#realisationCompetence-crud-filter-form',
        crudSelector: '#realisationCompetence-crud',
        tableSelector: '#realisationCompetence-data-container',
        formSelector: '#realisationCompetenceForm',
        indexUrl: '{{ route('realisationCompetences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationCompetences.create') }}',
        editUrl: '{{ route('realisationCompetences.edit',  ['realisationCompetence' => ':id']) }}',
        fieldMetaUrl: '{{ route('realisationCompetences.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('realisationCompetences.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('realisationCompetences.show',  ['realisationCompetence' => ':id']) }}',
        getEntityUrl: '{{ route("realisationCompetences.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('realisationCompetences.store') }}', 
        updateAttributesUrl: '{{ route('realisationCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationCompetences.destroy',  ['realisationCompetence' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationCompetence')),
        calculationUrl:  '{{ route('realisationCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::realisationCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationCompetence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationCompetence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationCompetence-crud" class="crud">
    @section('realisationCompetence-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::realisationCompetence.singular");
    @endphp
    <x-crud-header 
        id="realisationCompetence-crud-header" icon="fas fa-award"  
        iconColor="text-info"
        title="{{ $realisationCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationCompetence-crud-table')
    <section id="realisationCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationCompetence_instance"
                                    :createPermission="'create-realisationCompetence'"
                                    :createRoute="route('realisationCompetences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationCompetence'"
                                    :importRoute="route('realisationCompetences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationCompetence'"
                                    :exportXlsxRoute="route('realisationCompetences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationCompetences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationCompetence_viewTypes"
                                    :viewType="$realisationCompetence_viewType"
                                    :total="$realisationCompetences_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationCompetence-crud-filters')
                @if(!empty($realisationCompetences_total) &&  $realisationCompetences_total > 5)
                <div class="card-header">
                    <form id="realisationCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationCompetences_filters as $filter)
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
                        @section('realisationCompetence-crud-search-bar')
                        <div id="realisationCompetence-crud-search-bar"
                            class="{{ count($realisationCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationCompetences_search')"
                                name="realisationCompetences_search"
                                id="realisationCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationCompetence-data-container" class="data-container">
                    @if($realisationCompetence_viewType != "widgets")
                    @include("PkgApprentissage::realisationCompetence._$realisationCompetence_viewType")
                    @endif
                </div>
                @section('realisationCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationCompetences.bulkDelete') }}" 
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
     <section id="realisationCompetence-data-container-out" >
        @if($realisationCompetence_viewType == "widgets")
        @include("PkgApprentissage::realisationCompetence._$realisationCompetence_viewType")
        @endif
    </section>
    @show
</div>