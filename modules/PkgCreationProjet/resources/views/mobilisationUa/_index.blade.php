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
        entity_name: 'mobilisationUa',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'mobilisationUa.index' }}', 
        filterFormSelector: '#mobilisationUa-crud-filter-form',
        crudSelector: '#mobilisationUa-crud',
        tableSelector: '#mobilisationUa-data-container',
        formSelector: '#mobilisationUaForm',
        indexUrl: '{{ route('mobilisationUas.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('mobilisationUas.create') }}',
        editUrl: '{{ route('mobilisationUas.edit',  ['mobilisationUa' => ':id']) }}',
        fieldMetaUrl: '{{ route('mobilisationUas.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('mobilisationUas.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('mobilisationUas.show',  ['mobilisationUa' => ':id']) }}',
        getEntityUrl: '{{ route("mobilisationUas.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('mobilisationUas.store') }}', 
        updateAttributesUrl: '{{ route('mobilisationUas.updateAttributes') }}', 
        deleteUrl: '{{ route('mobilisationUas.destroy',  ['mobilisationUa' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-mobilisationUa')),
        calculationUrl:  '{{ route('mobilisationUas.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::mobilisationUa.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::mobilisationUa.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $mobilisationUa_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="mobilisationUa-crud" class="crud">
    @section('mobilisationUa-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::mobilisationUa.singular");
    @endphp
    <x-crud-header 
        id="mobilisationUa-crud-header" icon="fas  fa-seedling"  
        iconColor="text-info"
        title="{{ $mobilisationUa_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('mobilisationUa-crud-table')
    <section id="mobilisationUa-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('mobilisationUa-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$mobilisationUas_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$mobilisationUa_instance"
                                    :createPermission="'create-mobilisationUa'"
                                    :createRoute="route('mobilisationUas.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-mobilisationUa'"
                                    :importRoute="route('mobilisationUas.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-mobilisationUa'"
                                    :exportXlsxRoute="route('mobilisationUas.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('mobilisationUas.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$mobilisationUa_viewTypes"
                                    :viewType="$mobilisationUa_viewType"
                                    :total="$mobilisationUas_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('mobilisationUa-crud-filters')
                @if(!empty($mobilisationUas_total) &&  $mobilisationUas_total > 50)
                <div class="card-header">
                    <form id="mobilisationUa-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($mobilisationUas_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($mobilisationUas_filters as $filter)
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
                        @section('mobilisationUa-crud-search-bar')
                        <div id="mobilisationUa-crud-search-bar"
                            class="{{ count($mobilisationUas_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('mobilisationUas_search')"
                                name="mobilisationUas_search"
                                id="mobilisationUas_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="mobilisationUa-data-container" class="data-container">
                    @if($mobilisationUa_viewType != "widgets")
                    @include("PkgCreationProjet::mobilisationUa._$mobilisationUa_viewType")
                    @endif
                </div>
                @section('mobilisationUa-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-mobilisationUa")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('mobilisationUas.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-mobilisationUa')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('mobilisationUas.bulkDelete') }}" 
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
     <section id="mobilisationUa-data-container-out" >
        @if($mobilisationUa_viewType == "widgets")
        @include("PkgCreationProjet::mobilisationUa._$mobilisationUa_viewType")
        @endif
    </section>
    @show
</div>