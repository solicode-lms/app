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
        entity_name: 'propositionReponse',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'propositionReponse.index' }}', 
        filterFormSelector: '#propositionReponse-crud-filter-form',
        crudSelector: '#propositionReponse-crud',
        tableSelector: '#propositionReponse-data-container',
        formSelector: '#propositionReponseForm',
        indexUrl: '{{ route('propositionReponses.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('propositionReponses.create') }}',
        editUrl: '{{ route('propositionReponses.edit',  ['propositionReponse' => ':id']) }}',
        fieldMetaUrl: '{{ route('propositionReponses.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('propositionReponses.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('propositionReponses.show',  ['propositionReponse' => ':id']) }}',
        getEntityUrl: '{{ route("propositionReponses.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('propositionReponses.store') }}', 
        updateAttributesUrl: '{{ route('propositionReponses.updateAttributes') }}', 
        deleteUrl: '{{ route('propositionReponses.destroy',  ['propositionReponse' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-propositionReponse')),
        calculationUrl:  '{{ route('propositionReponses.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgQcm::propositionReponse.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::propositionReponse.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $propositionReponse_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="propositionReponse-crud" class="crud">
    @section('propositionReponse-crud-header')
    @php
        $package = __("PkgQcm::PkgQcm.name");
       $titre = __("PkgQcm::propositionReponse.singular");
    @endphp
    <x-crud-header 
        id="propositionReponse-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $propositionReponse_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('propositionReponse-crud-table')
    <section id="propositionReponse-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('propositionReponse-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$propositionReponses_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$propositionReponse_instance"
                                    :createPermission="'create-propositionReponse'"
                                    :createRoute="route('propositionReponses.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-propositionReponse'"
                                    :importRoute="route('propositionReponses.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-propositionReponse'"
                                    :exportXlsxRoute="route('propositionReponses.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('propositionReponses.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$propositionReponse_viewTypes"
                                    :viewType="$propositionReponse_viewType"
                                    :total="$propositionReponses_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('propositionReponse-crud-filters')
                @if(!empty($propositionReponses_total) &&  $propositionReponses_total > 10)
                <div class="card-header">
                    <form id="propositionReponse-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($propositionReponses_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($propositionReponses_filters as $filter)
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
                        @section('propositionReponse-crud-search-bar')
                        <div id="propositionReponse-crud-search-bar"
                            class="{{ count($propositionReponses_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('propositionReponses_search')"
                                name="propositionReponses_search"
                                id="propositionReponses_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="propositionReponse-data-container" class="data-container">
                    @if($propositionReponse_viewType != "widgets")
                    @include("PkgQcm::propositionReponse._$propositionReponse_viewType")
                    @endif
                </div>
                @section('propositionReponse-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-propositionReponse")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('propositionReponses.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-propositionReponse')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('propositionReponses.bulkDelete') }}" 
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
     <section id="propositionReponse-data-container-out" >
        @if($propositionReponse_viewType == "widgets")
        @include("PkgQcm::propositionReponse._$propositionReponse_viewType")
        @endif
    </section>
    @show
</div>