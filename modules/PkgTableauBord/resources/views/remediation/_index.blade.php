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
        entity_name: 'remediation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'remediation.index' }}', 
        filterFormSelector: '#remediation-crud-filter-form',
        crudSelector: '#remediation-crud',
        tableSelector: '#remediation-data-container',
        formSelector: '#remediationForm',
        indexUrl: '{{ route('remediations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('remediations.create') }}',
        editUrl: '{{ route('remediations.edit',  ['remediation' => ':id']) }}',
        fieldMetaUrl: '{{ route('remediations.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('remediations.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('remediations.show',  ['remediation' => ':id']) }}',
        getEntityUrl: '{{ route("remediations.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('remediations.store') }}', 
        updateAttributesUrl: '{{ route('remediations.updateAttributes') }}', 
        deleteUrl: '{{ route('remediations.destroy',  ['remediation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-remediation')),
        calculationUrl:  '{{ route('remediations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgTableauBord::remediation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgTableauBord::remediation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $remediation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="remediation-crud" class="crud">
    @section('remediation-crud-header')
    @php
        $package = __("PkgTableauBord::PkgTableauBord.name");
       $titre = __("PkgTableauBord::remediation.singular");
    @endphp
    <x-crud-header 
        id="remediation-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $remediation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('remediation-crud-table')
    <section id="remediation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('remediation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$remediations_stats"
                            />
                            <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$remediation_instance"
                                    :createPermission="'create-remediation'"
                                    :createRoute="route('remediations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-remediation'"
                                    :importRoute="route('remediations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-remediation'"
                                    :exportXlsxRoute="route('remediations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('remediations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$remediation_viewTypes"
                                    :viewType="$remediation_viewType"
                                    :total="$remediations_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('remediation-crud-filters')
                <div class="card-header">
                    <form id="remediation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($remediations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($remediations_filters as $filter)
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
                        @section('remediation-crud-search-bar')
                        <div id="remediation-crud-search-bar"
                            class="{{ count($remediations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('remediations_search')"
                                name="remediations_search"
                                id="remediations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="remediation-data-container" class="data-container">
                    @if($remediation_viewType != "widgets")
                    @include("PkgTableauBord::remediation._$remediation_viewType")
                    @endif
                </div>
                @section('remediation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-remediation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('remediations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-remediation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('remediations.bulkDelete') }}" 
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
     <section id="remediation-data-container-out" >
        @if($remediation_viewType == "widgets")
        @include("PkgTableauBord::remediation._$remediation_viewType")
        @endif
    </section>
    @show
</div>