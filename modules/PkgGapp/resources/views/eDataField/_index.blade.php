{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'eDataField',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'eDataField.index' }}', 
        filterFormSelector: '#eDataField-crud-filter-form',
        crudSelector: '#eDataField-crud',
        tableSelector: '#eDataField-data-container',
        formSelector: '#eDataFieldForm',
        indexUrl: '{{ route('eDataFields.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('eDataFields.create') }}',
        editUrl: '{{ route('eDataFields.edit',  ['eDataField' => ':id']) }}',
        showUrl: '{{ route('eDataFields.show',  ['eDataField' => ':id']) }}',
        storeUrl: '{{ route('eDataFields.store') }}', 
        updateAttributesUrl: '{{ route('eDataFields.updateAttributes') }}', 
        deleteUrl: '{{ route('eDataFields.destroy',  ['eDataField' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-eDataField')),
        calculationUrl:  '{{ route('eDataFields.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eDataField.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eDataField.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $eDataField_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="eDataField-crud" class="crud">
    @section('eDataField-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eDataField.singular");
    @endphp
    <x-crud-header 
        id="eDataField-crud-header" icon="fas fa-th"  
        iconColor="text-info"
        title="{{ $eDataField_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eDataField-crud-table')
    <section id="eDataField-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eDataField-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$eDataFields_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$eDataField_instance"
                                    :createPermission="'create-eDataField'"
                                    :createRoute="route('eDataFields.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-eDataField'"
                                    :importRoute="route('eDataFields.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-eDataField'"
                                    :exportXlsxRoute="route('eDataFields.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('eDataFields.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$eDataField_viewTypes"
                                    :viewType="$eDataField_viewType"
                                    :total="$eDataFields_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('eDataField-crud-filters')
                @if(!empty($eDataFields_total) &&  $eDataFields_total > 10)
                <div class="card-header">
                    <form id="eDataField-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eDataFields_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eDataFields_filters as $filter)
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
                        @section('eDataField-crud-search-bar')
                        <div id="eDataField-crud-search-bar"
                            class="{{ count($eDataFields_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eDataFields_search')"
                                name="eDataFields_search"
                                id="eDataFields_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="eDataField-data-container" class="data-container">
                    @if($eDataField_viewType != "widgets")
                    @include("PkgGapp::eDataField._$eDataField_viewType")
                    @endif
                </div>
                @section('eDataField-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-eDataField")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('eDataFields.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-eDataField')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('eDataFields.bulkDelete') }}" 
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
     <section id="eDataField-data-container-out" >
        @if($eDataField_viewType == "widgets")
        @include("PkgGapp::eDataField._$eDataField_viewType")
        @endif
    </section>
    @show
</div>