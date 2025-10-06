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
        entity_name: 'etatRealisationCompetence',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationCompetence.index' }}', 
        filterFormSelector: '#etatRealisationCompetence-crud-filter-form',
        crudSelector: '#etatRealisationCompetence-crud',
        tableSelector: '#etatRealisationCompetence-data-container',
        formSelector: '#etatRealisationCompetenceForm',
        indexUrl: '{{ route('etatRealisationCompetences.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatRealisationCompetences.create') }}',
        editUrl: '{{ route('etatRealisationCompetences.edit',  ['etatRealisationCompetence' => ':id']) }}',
        fieldMetaUrl: '{{ route('etatRealisationCompetences.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('etatRealisationCompetences.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('etatRealisationCompetences.show',  ['etatRealisationCompetence' => ':id']) }}',
        getEntityUrl: '{{ route("etatRealisationCompetences.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatRealisationCompetences.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationCompetences.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationCompetences.destroy',  ['etatRealisationCompetence' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatRealisationCompetence')),
        calculationUrl:  '{{ route('etatRealisationCompetences.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::etatRealisationCompetence.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationCompetence.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationCompetence_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationCompetence-crud" class="crud">
    @section('etatRealisationCompetence-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::etatRealisationCompetence.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationCompetence-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $etatRealisationCompetence_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationCompetence-crud-table')
    <section id="etatRealisationCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationCompetence-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationCompetences_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatRealisationCompetence_instance"
                                    :createPermission="'create-etatRealisationCompetence'"
                                    :createRoute="route('etatRealisationCompetences.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatRealisationCompetence'"
                                    :importRoute="route('etatRealisationCompetences.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatRealisationCompetence'"
                                    :exportXlsxRoute="route('etatRealisationCompetences.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatRealisationCompetences.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatRealisationCompetence_viewTypes"
                                    :viewType="$etatRealisationCompetence_viewType"
                                    :total="$etatRealisationCompetences_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationCompetence-crud-filters')
                @if(!empty($etatRealisationCompetences_total) &&  $etatRealisationCompetences_total > 50)
                <div class="card-header">
                    <form id="etatRealisationCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationCompetences_filters as $filter)
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
                        @section('etatRealisationCompetence-crud-search-bar')
                        <div id="etatRealisationCompetence-crud-search-bar"
                            class="{{ count($etatRealisationCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationCompetences_search')"
                                name="etatRealisationCompetences_search"
                                id="etatRealisationCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatRealisationCompetence-data-container" class="data-container">
                    @if($etatRealisationCompetence_viewType != "widgets")
                    @include("PkgApprentissage::etatRealisationCompetence._$etatRealisationCompetence_viewType")
                    @endif
                </div>
                @section('etatRealisationCompetence-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationCompetence")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationCompetences.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationCompetence')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationCompetences.bulkDelete') }}" 
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
     <section id="etatRealisationCompetence-data-container-out" >
        @if($etatRealisationCompetence_viewType == "widgets")
        @include("PkgApprentissage::etatRealisationCompetence._$etatRealisationCompetence_viewType")
        @endif
    </section>
    @show
</div>