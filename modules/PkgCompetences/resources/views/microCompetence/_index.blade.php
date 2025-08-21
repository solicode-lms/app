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
        entity_name: 'microCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'microCompetence.index' }}', 
        filterFormSelector: '#microCompetence-crud-filter-form',
        crudSelector: '#microCompetence-crud',
        tableSelector: '#microCompetence-data-container',
        formSelector: '#microCompetenceForm',
        indexUrl: '{{ route('microCompetences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('microCompetences.create') }}',
        editUrl: '{{ route('microCompetences.edit',  ['microCompetence' => ':id']) }}',
        fieldMetaUrl: '{{ route('microCompetences.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('microCompetences.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('microCompetences.show',  ['microCompetence' => ':id']) }}',
        getEntityUrl: '{{ route("microCompetences.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('microCompetences.store') }}', 
        updateAttributesUrl: '{{ route('microCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('microCompetences.destroy',  ['microCompetence' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-microCompetence')),
        calculationUrl:  '{{ route('microCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::microCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::microCompetence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $microCompetence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="microCompetence-crud" class="crud">
    @section('microCompetence-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::microCompetence.singular");
    @endphp
    <x-crud-header 
        id="microCompetence-crud-header" icon="fas fa-book"  
        iconColor="text-info"
        title="{{ $microCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('microCompetence-crud-table')
    <section id="microCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('microCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$microCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$microCompetence_instance"
                                    :createPermission="'create-microCompetence'"
                                    :createRoute="route('microCompetences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-microCompetence'"
                                    :importRoute="route('microCompetences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-microCompetence'"
                                    :exportXlsxRoute="route('microCompetences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('microCompetences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$microCompetence_viewTypes"
                                    :viewType="$microCompetence_viewType"
                                    :total="$microCompetences_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('microCompetence-crud-filters')
                @if(!empty($microCompetences_total) &&  $microCompetences_total > 5)
                <div class="card-header">
                    <form id="microCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($microCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($microCompetences_filters as $filter)
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
                        @section('microCompetence-crud-search-bar')
                        <div id="microCompetence-crud-search-bar"
                            class="{{ count($microCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('microCompetences_search')"
                                name="microCompetences_search"
                                id="microCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="microCompetence-data-container" class="data-container">
                    @if($microCompetence_viewType != "widgets")
                    @include("PkgCompetences::microCompetence._$microCompetence_viewType")
                    @endif
                </div>
                @section('microCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-microCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('microCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-microCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('microCompetences.bulkDelete') }}" 
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
     <section id="microCompetence-data-container-out" >
        @if($microCompetence_viewType == "widgets")
        @include("PkgCompetences::microCompetence._$microCompetence_viewType")
        @endif
    </section>
    @show
</div>