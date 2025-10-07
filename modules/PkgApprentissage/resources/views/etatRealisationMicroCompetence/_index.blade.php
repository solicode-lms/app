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
        entity_name: 'etatRealisationMicroCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationMicroCompetence.index' }}', 
        filterFormSelector: '#etatRealisationMicroCompetence-crud-filter-form',
        crudSelector: '#etatRealisationMicroCompetence-crud',
        tableSelector: '#etatRealisationMicroCompetence-data-container',
        formSelector: '#etatRealisationMicroCompetenceForm',
        indexUrl: '{{ route('etatRealisationMicroCompetences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatRealisationMicroCompetences.create') }}',
        editUrl: '{{ route('etatRealisationMicroCompetences.edit',  ['etatRealisationMicroCompetence' => ':id']) }}',
        fieldMetaUrl: '{{ route('etatRealisationMicroCompetences.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('etatRealisationMicroCompetences.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('etatRealisationMicroCompetences.show',  ['etatRealisationMicroCompetence' => ':id']) }}',
        getEntityUrl: '{{ route("etatRealisationMicroCompetences.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatRealisationMicroCompetences.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationMicroCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationMicroCompetences.destroy',  ['etatRealisationMicroCompetence' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatRealisationMicroCompetence')),
        calculationUrl:  '{{ route('etatRealisationMicroCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::etatRealisationMicroCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationMicroCompetence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationMicroCompetence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationMicroCompetence-crud" class="crud">
    @section('etatRealisationMicroCompetence-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::etatRealisationMicroCompetence.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationMicroCompetence-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $etatRealisationMicroCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationMicroCompetence-crud-table')
    <section id="etatRealisationMicroCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationMicroCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationMicroCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatRealisationMicroCompetence_instance"
                                    :createPermission="'create-etatRealisationMicroCompetence'"
                                    :createRoute="route('etatRealisationMicroCompetences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatRealisationMicroCompetence'"
                                    :importRoute="route('etatRealisationMicroCompetences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatRealisationMicroCompetence'"
                                    :exportXlsxRoute="route('etatRealisationMicroCompetences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatRealisationMicroCompetences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatRealisationMicroCompetence_viewTypes"
                                    :viewType="$etatRealisationMicroCompetence_viewType"
                                    :total="$etatRealisationMicroCompetences_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationMicroCompetence-crud-filters')
                @if(!empty($etatRealisationMicroCompetences_total) &&  $etatRealisationMicroCompetences_total > 10)
                <div class="card-header">
                    <form id="etatRealisationMicroCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationMicroCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationMicroCompetences_filters as $filter)
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
                        @section('etatRealisationMicroCompetence-crud-search-bar')
                        <div id="etatRealisationMicroCompetence-crud-search-bar"
                            class="{{ count($etatRealisationMicroCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationMicroCompetences_search')"
                                name="etatRealisationMicroCompetences_search"
                                id="etatRealisationMicroCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatRealisationMicroCompetence-data-container" class="data-container">
                    @if($etatRealisationMicroCompetence_viewType != "widgets")
                    @include("PkgApprentissage::etatRealisationMicroCompetence._$etatRealisationMicroCompetence_viewType")
                    @endif
                </div>
                @section('etatRealisationMicroCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationMicroCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationMicroCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationMicroCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationMicroCompetences.bulkDelete') }}" 
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
     <section id="etatRealisationMicroCompetence-data-container-out" >
        @if($etatRealisationMicroCompetence_viewType == "widgets")
        @include("PkgApprentissage::etatRealisationMicroCompetence._$etatRealisationMicroCompetence_viewType")
        @endif
    </section>
    @show
</div>