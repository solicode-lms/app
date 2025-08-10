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
        entity_name: 'formateur',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'formateur.index' }}', 
        filterFormSelector: '#formateur-crud-filter-form',
        crudSelector: '#formateur-crud',
        tableSelector: '#formateur-data-container',
        formSelector: '#formateurForm',
        indexUrl: '{{ route('formateurs.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('formateurs.create') }}',
        editUrl: '{{ route('formateurs.edit',  ['formateur' => ':id']) }}',
        showUrl: '{{ route('formateurs.show',  ['formateur' => ':id']) }}',
        getEntityUrl: '{{ route("formateurs.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('formateurs.store') }}', 
        updateAttributesUrl: '{{ route('formateurs.updateAttributes') }}', 
        deleteUrl: '{{ route('formateurs.destroy',  ['formateur' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-formateur')),
        calculationUrl:  '{{ route('formateurs.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::formateur.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::formateur.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $formateur_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="formateur-crud" class="crud">
    @section('formateur-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::formateur.singular");
    @endphp
    <x-crud-header 
        id="formateur-crud-header" icon="fas fa-user-tie"  
        iconColor="text-info"
        title="{{ $formateur_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('formateur-crud-table')
    <section id="formateur-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('formateur-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$formateurs_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$formateur_instance"
                                    :createPermission="'create-formateur'"
                                    :createRoute="route('formateurs.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-formateur'"
                                    :importRoute="route('formateurs.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-formateur'"
                                    :exportXlsxRoute="route('formateurs.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('formateurs.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$formateur_viewTypes"
                                    :viewType="$formateur_viewType"
                                    :total="$formateurs_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('formateur-crud-filters')
                @if(!empty($formateurs_total) &&  $formateurs_total > 5)
                <div class="card-header">
                    <form id="formateur-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($formateurs_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($formateurs_filters as $filter)
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
                        @section('formateur-crud-search-bar')
                        <div id="formateur-crud-search-bar"
                            class="{{ count($formateurs_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('formateurs_search')"
                                name="formateurs_search"
                                id="formateurs_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="formateur-data-container" class="data-container">
                    @if($formateur_viewType != "widgets")
                    @include("PkgFormation::formateur._$formateur_viewType")
                    @endif
                </div>
                @section('formateur-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-formateur")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('formateurs.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-formateur')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('formateurs.bulkDelete') }}" 
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
     <section id="formateur-data-container-out" >
        @if($formateur_viewType == "widgets")
        @include("PkgFormation::formateur._$formateur_viewType")
        @endif
    </section>
    @show
</div>