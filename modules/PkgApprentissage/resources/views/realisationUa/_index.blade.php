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
        entity_name: 'realisationUa',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationUa.index' }}', 
        filterFormSelector: '#realisationUa-crud-filter-form',
        crudSelector: '#realisationUa-crud',
        tableSelector: '#realisationUa-data-container',
        formSelector: '#realisationUaForm',
        indexUrl: '{{ route('realisationUas.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationUas.create') }}',
        editUrl: '{{ route('realisationUas.edit',  ['realisationUa' => ':id']) }}',
        fieldMetaUrl: '{{ route('realisationUas.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('realisationUas.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('realisationUas.show',  ['realisationUa' => ':id']) }}',
        getEntityUrl: '{{ route("realisationUas.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('realisationUas.store') }}', 
        updateAttributesUrl: '{{ route('realisationUas.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationUas.destroy',  ['realisationUa' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationUa')),
        calculationUrl:  '{{ route('realisationUas.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::realisationUa.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationUa.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationUa_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationUa-crud" class="crud">
    @section('realisationUa-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::realisationUa.singular");
    @endphp
    <x-crud-header 
        id="realisationUa-crud-header" icon="fas fa-tools"  
        iconColor="text-info"
        title="{{ $realisationUa_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationUa-crud-table')
    <section id="realisationUa-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationUa-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationUas_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationUa_instance"
                                    :createPermission="'create-realisationUa'"
                                    :createRoute="route('realisationUas.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationUa'"
                                    :importRoute="route('realisationUas.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationUa'"
                                    :exportXlsxRoute="route('realisationUas.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationUas.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationUa_viewTypes"
                                    :viewType="$realisationUa_viewType"
                                    :total="$realisationUas_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationUa-crud-filters')
                @if(!empty($realisationUas_total) &&  $realisationUas_total > 50)
                <div class="card-header">
                    <form id="realisationUa-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationUas_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationUas_filters as $filter)
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
                        @section('realisationUa-crud-search-bar')
                        <div id="realisationUa-crud-search-bar"
                            class="{{ count($realisationUas_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationUas_search')"
                                name="realisationUas_search"
                                id="realisationUas_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationUa-data-container" class="data-container">
                    @if($realisationUa_viewType != "widgets")
                    @include("PkgApprentissage::realisationUa._$realisationUa_viewType")
                    @endif
                </div>
                @section('realisationUa-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationUa")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationUas.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationUa')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationUas.bulkDelete') }}" 
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
     <section id="realisationUa-data-container-out" >
        @if($realisationUa_viewType == "widgets")
        @include("PkgApprentissage::realisationUa._$realisationUa_viewType")
        @endif
    </section>
    @show
</div>