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
        entity_name: 'livrableSession',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'livrableSession.index' }}', 
        filterFormSelector: '#livrableSession-crud-filter-form',
        crudSelector: '#livrableSession-crud',
        tableSelector: '#livrableSession-data-container',
        formSelector: '#livrableSessionForm',
        indexUrl: '{{ route('livrableSessions.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('livrableSessions.create') }}',
        editUrl: '{{ route('livrableSessions.edit',  ['livrableSession' => ':id']) }}',
        fieldMetaUrl: '{{ route('livrableSessions.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('livrableSessions.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('livrableSessions.show',  ['livrableSession' => ':id']) }}',
        getEntityUrl: '{{ route("livrableSessions.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('livrableSessions.store') }}', 
        updateAttributesUrl: '{{ route('livrableSessions.updateAttributes') }}', 
        deleteUrl: '{{ route('livrableSessions.destroy',  ['livrableSession' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-livrableSession')),
        calculationUrl:  '{{ route('livrableSessions.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgSessions::livrableSession.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgSessions::livrableSession.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $livrableSession_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="livrableSession-crud" class="crud">
    @section('livrableSession-crud-header')
    @php
        $package = __("PkgSessions::PkgSessions.name");
       $titre = __("PkgSessions::livrableSession.singular");
    @endphp
    <x-crud-header 
        id="livrableSession-crud-header" icon="fas fa-folder"  
        iconColor="text-info"
        title="{{ $livrableSession_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('livrableSession-crud-table')
    <section id="livrableSession-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('livrableSession-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$livrableSessions_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$livrableSession_instance"
                                    :createPermission="'create-livrableSession'"
                                    :createRoute="route('livrableSessions.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-livrableSession'"
                                    :importRoute="route('livrableSessions.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-livrableSession'"
                                    :exportXlsxRoute="route('livrableSessions.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('livrableSessions.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$livrableSession_viewTypes"
                                    :viewType="$livrableSession_viewType"
                                    :total="$livrableSessions_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('livrableSession-crud-filters')
                @if(!empty($livrableSessions_total) &&  $livrableSessions_total > 50)
                <div class="card-header">
                    <form id="livrableSession-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($livrableSessions_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($livrableSessions_filters as $filter)
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
                        @section('livrableSession-crud-search-bar')
                        <div id="livrableSession-crud-search-bar"
                            class="{{ count($livrableSessions_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('livrableSessions_search')"
                                name="livrableSessions_search"
                                id="livrableSessions_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="livrableSession-data-container" class="data-container">
                    @if($livrableSession_viewType != "widgets")
                    @include("PkgSessions::livrableSession._$livrableSession_viewType")
                    @endif
                </div>
                @section('livrableSession-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-livrableSession")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('livrableSessions.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-livrableSession')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('livrableSessions.bulkDelete') }}" 
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
     <section id="livrableSession-data-container-out" >
        @if($livrableSession_viewType == "widgets")
        @include("PkgSessions::livrableSession._$livrableSession_viewType")
        @endif
    </section>
    @show
</div>