{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'validation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'validation.index' }}', 
        filterFormSelector: '#validation-crud-filter-form',
        crudSelector: '#validation-crud',
        tableSelector: '#validation-data-container',
        formSelector: '#validationForm',
        indexUrl: '{{ route('validations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('validations.create') }}',
        editUrl: '{{ route('validations.edit',  ['validation' => ':id']) }}',
        showUrl: '{{ route('validations.show',  ['validation' => ':id']) }}',
        storeUrl: '{{ route('validations.store') }}', 
        updateAttributesUrl: '{{ route('validations.updateAttributes') }}', 
        deleteUrl: '{{ route('validations.destroy',  ['validation' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-validation')),
        calculationUrl:  '{{ route('validations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::validation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::validation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $validation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="validation-crud" class="crud">
    @section('validation-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::validation.singular");
    @endphp
    <x-crud-header 
        id="validation-crud-header" icon="fas fa-check-circle"  
        iconColor="text-info"
        title="{{ $validation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('validation-crud-table')
    <section id="validation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('validation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$validations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$validation_instance"
                                    :createPermission="'create-validation'"
                                    :createRoute="route('validations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-validation'"
                                    :importRoute="route('validations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-validation'"
                                    :exportXlsxRoute="route('validations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('validations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$validation_viewTypes"
                                    :viewType="$validation_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('validation-crud-filters')
                <div class="card-header">
                    <form id="validation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($validations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($validations_filters as $filter)
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
                        @section('validation-crud-search-bar')
                        <div id="validation-crud-search-bar"
                            class="{{ count($validations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('validations_search')"
                                name="validations_search"
                                id="validations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="validation-data-container" class="data-container">
                    @if($validation_viewType == "table")
                    @include("PkgRealisationProjets::validation._$validation_viewType")
                    @endif
                </div>
                @section('validation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-validation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('validations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-validation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('validations.bulkDelete') }}" 
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
     <section id="validation-data-container-out" >
        @if($validation_viewType == "widgets")
        @include("PkgRealisationProjets::validation._$validation_viewType")
        @endif
    </section>
    @show
</div>