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
        entity_name: 'anneeFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'anneeFormation.index' }}', 
        filterFormSelector: '#anneeFormation-crud-filter-form',
        crudSelector: '#anneeFormation-crud',
        tableSelector: '#anneeFormation-data-container',
        formSelector: '#anneeFormationForm',
        indexUrl: '{{ route('anneeFormations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('anneeFormations.create') }}',
        editUrl: '{{ route('anneeFormations.edit',  ['anneeFormation' => ':id']) }}',
        fieldMetaUrl: '{{ route('anneeFormations.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('anneeFormations.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('anneeFormations.show',  ['anneeFormation' => ':id']) }}',
        getEntityUrl: '{{ route("anneeFormations.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('anneeFormations.store') }}', 
        updateAttributesUrl: '{{ route('anneeFormations.updateAttributes') }}', 
        deleteUrl: '{{ route('anneeFormations.destroy',  ['anneeFormation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-anneeFormation')),
        calculationUrl:  '{{ route('anneeFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::anneeFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::anneeFormation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $anneeFormation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="anneeFormation-crud" class="crud">
    @section('anneeFormation-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::anneeFormation.singular");
    @endphp
    <x-crud-header 
        id="anneeFormation-crud-header" icon="fas fa-calendar-plus"  
        iconColor="text-info"
        title="{{ $anneeFormation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('anneeFormation-crud-table')
    <section id="anneeFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('anneeFormation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$anneeFormations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$anneeFormation_instance"
                                    :createPermission="'create-anneeFormation'"
                                    :createRoute="route('anneeFormations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-anneeFormation'"
                                    :importRoute="route('anneeFormations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-anneeFormation'"
                                    :exportXlsxRoute="route('anneeFormations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('anneeFormations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$anneeFormation_viewTypes"
                                    :viewType="$anneeFormation_viewType"
                                    :total="$anneeFormations_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('anneeFormation-crud-filters')
                @if(!empty($anneeFormations_total) &&  $anneeFormations_total > 5)
                <div class="card-header">
                    <form id="anneeFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($anneeFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($anneeFormations_filters as $filter)
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
                        @section('anneeFormation-crud-search-bar')
                        <div id="anneeFormation-crud-search-bar"
                            class="{{ count($anneeFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('anneeFormations_search')"
                                name="anneeFormations_search"
                                id="anneeFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="anneeFormation-data-container" class="data-container">
                    @if($anneeFormation_viewType != "widgets")
                    @include("PkgFormation::anneeFormation._$anneeFormation_viewType")
                    @endif
                </div>
                @section('anneeFormation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-anneeFormation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('anneeFormations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-anneeFormation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('anneeFormations.bulkDelete') }}" 
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
     <section id="anneeFormation-data-container-out" >
        @if($anneeFormation_viewType == "widgets")
        @include("PkgFormation::anneeFormation._$anneeFormation_viewType")
        @endif
    </section>
    @show
</div>