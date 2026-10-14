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
        entity_name: 'liveCoding',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'liveCoding.index' }}', 
        filterFormSelector: '#liveCoding-crud-filter-form',
        crudSelector: '#liveCoding-crud',
        tableSelector: '#liveCoding-data-container',
        formSelector: '#liveCodingForm',
        indexUrl: '{{ route('liveCodings.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('liveCodings.create') }}',
        editUrl: '{{ route('liveCodings.edit',  ['liveCoding' => ':id']) }}',
        fieldMetaUrl: '{{ route('liveCodings.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('liveCodings.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('liveCodings.show',  ['liveCoding' => ':id']) }}',
        getEntityUrl: '{{ route("liveCodings.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('liveCodings.store') }}', 
        updateAttributesUrl: '{{ route('liveCodings.updateAttributes') }}', 
        deleteUrl: '{{ route('liveCodings.destroy',  ['liveCoding' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-liveCoding')),
        calculationUrl:  '{{ route('liveCodings.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationTache::liveCoding.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationTache::liveCoding.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $liveCoding_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="liveCoding-crud" class="crud">
    @section('liveCoding-crud-header')
    @php
        $package = __("PkgRealisationTache::PkgRealisationTache.name");
       $titre = __("PkgRealisationTache::liveCoding.singular");
    @endphp
    <x-crud-header 
        id="liveCoding-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $liveCoding_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('liveCoding-crud-table')
    <section id="liveCoding-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('liveCoding-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$liveCodings_stats"
                            />
                            <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$liveCoding_instance"
                                    :createPermission="'create-liveCoding'"
                                    :createRoute="route('liveCodings.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-liveCoding'"
                                    :importRoute="route('liveCodings.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-liveCoding'"
                                    :exportXlsxRoute="route('liveCodings.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('liveCodings.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$liveCoding_viewTypes"
                                    :viewType="$liveCoding_viewType"
                                    :total="$liveCodings_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('liveCoding-crud-filters')
                <div class="card-header">
                    <form id="liveCoding-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($liveCodings_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($liveCodings_filters as $filter)
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
                        @section('liveCoding-crud-search-bar')
                        <div id="liveCoding-crud-search-bar"
                            class="{{ count($liveCodings_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('liveCodings_search')"
                                name="liveCodings_search"
                                id="liveCodings_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="liveCoding-data-container" class="data-container">
                    @if($liveCoding_viewType != "widgets")
                    @include("PkgRealisationTache::liveCoding._$liveCoding_viewType")
                    @endif
                </div>
                @section('liveCoding-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-liveCoding")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('liveCodings.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-liveCoding')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('liveCodings.bulkDelete') }}" 
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
     <section id="liveCoding-data-container-out" >
        @if($liveCoding_viewType == "widgets")
        @include("PkgRealisationTache::liveCoding._$liveCoding_viewType")
        @endif
    </section>
    @show
</div>