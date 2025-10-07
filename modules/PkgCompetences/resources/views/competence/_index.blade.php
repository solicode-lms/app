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
        entity_name: 'competence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'competence.index' }}', 
        filterFormSelector: '#competence-crud-filter-form',
        crudSelector: '#competence-crud',
        tableSelector: '#competence-data-container',
        formSelector: '#competenceForm',
        indexUrl: '{{ route('competences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit',  ['competence' => ':id']) }}',
        fieldMetaUrl: '{{ route('competences.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('competences.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('competences.show',  ['competence' => ':id']) }}',
        getEntityUrl: '{{ route("competences.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('competences.store') }}', 
        updateAttributesUrl: '{{ route('competences.updateAttributes') }}', 
        deleteUrl: '{{ route('competences.destroy',  ['competence' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-competence')),
        calculationUrl:  '{{ route('competences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::competence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $competence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="competence-crud" class="crud">
    @section('competence-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::competence.singular");
    @endphp
    <x-crud-header 
        id="competence-crud-header" icon="fas fa-user-graduate"  
        iconColor="text-info"
        title="{{ $competence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('competence-crud-table')
    <section id="competence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('competence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$competences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$competence_instance"
                                    :createPermission="'create-competence'"
                                    :createRoute="route('competences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-competence'"
                                    :importRoute="route('competences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-competence'"
                                    :exportXlsxRoute="route('competences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('competences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$competence_viewTypes"
                                    :viewType="$competence_viewType"
                                    :total="$competences_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('competence-crud-filters')
                @if(!empty($competences_total) &&  $competences_total > 10)
                <div class="card-header">
                    <form id="competence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($competences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($competences_filters as $filter)
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
                        @section('competence-crud-search-bar')
                        <div id="competence-crud-search-bar"
                            class="{{ count($competences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('competences_search')"
                                name="competences_search"
                                id="competences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="competence-data-container" class="data-container">
                    @if($competence_viewType != "widgets")
                    @include("PkgCompetences::competence._$competence_viewType")
                    @endif
                </div>
                @section('competence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-competence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('competences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-competence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('competences.bulkDelete') }}" 
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
     <section id="competence-data-container-out" >
        @if($competence_viewType == "widgets")
        @include("PkgCompetences::competence._$competence_viewType")
        @endif
    </section>
    @show
</div>