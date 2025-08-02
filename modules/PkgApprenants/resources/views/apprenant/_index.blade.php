{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : true,
        entity_name: 'apprenant',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'apprenant.index' }}', 
        filterFormSelector: '#apprenant-crud-filter-form',
        crudSelector: '#apprenant-crud',
        tableSelector: '#apprenant-data-container',
        formSelector: '#apprenantForm',
        indexUrl: '{{ route('apprenants.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('apprenants.create') }}',
        editUrl: '{{ route('apprenants.edit',  ['apprenant' => ':id']) }}',
        showUrl: '{{ route('apprenants.show',  ['apprenant' => ':id']) }}',
        storeUrl: '{{ route('apprenants.store') }}', 
        updateAttributesUrl: '{{ route('apprenants.updateAttributes') }}', 
        deleteUrl: '{{ route('apprenants.destroy',  ['apprenant' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-apprenant')),
        calculationUrl:  '{{ route('apprenants.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::apprenant.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::apprenant.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $apprenant_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="apprenant-crud" class="crud">
    @section('apprenant-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::apprenant.singular");
    @endphp
    <x-crud-header 
        id="apprenant-crud-header" icon="fas fa-id-card"  
        iconColor="text-info"
        title="{{ $apprenant_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('apprenant-crud-table')
    <section id="apprenant-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('apprenant-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$apprenants_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$apprenant_instance"
                                    :createPermission="'create-apprenant'"
                                    :createRoute="route('apprenants.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-apprenant'"
                                    :importRoute="route('apprenants.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-apprenant'"
                                    :exportXlsxRoute="route('apprenants.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('apprenants.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$apprenant_viewTypes"
                                    :viewType="$apprenant_viewType"
                                    :total="$apprenants_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('apprenant-crud-filters')
                @if(!empty($apprenants_total) &&  $apprenants_total > 5)
                <div class="card-header">
                    <form id="apprenant-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($apprenants_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($apprenants_filters as $filter)
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
                        @section('apprenant-crud-search-bar')
                        <div id="apprenant-crud-search-bar"
                            class="{{ count($apprenants_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('apprenants_search')"
                                name="apprenants_search"
                                id="apprenants_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="apprenant-data-container" class="data-container">
                    @if($apprenant_viewType != "widgets")
                    @include("PkgApprenants::apprenant._$apprenant_viewType")
                    @endif
                </div>
                @section('apprenant-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-apprenant")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('apprenants.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-apprenant')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('apprenants.bulkDelete') }}" 
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
     <section id="apprenant-data-container-out" >
        @if($apprenant_viewType == "widgets")
        @include("PkgApprenants::apprenant._$apprenant_viewType")
        @endif
    </section>
    @show
</div>