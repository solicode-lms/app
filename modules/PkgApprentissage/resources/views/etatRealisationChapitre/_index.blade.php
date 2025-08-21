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
        entity_name: 'etatRealisationChapitre',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationChapitre.index' }}', 
        filterFormSelector: '#etatRealisationChapitre-crud-filter-form',
        crudSelector: '#etatRealisationChapitre-crud',
        tableSelector: '#etatRealisationChapitre-data-container',
        formSelector: '#etatRealisationChapitreForm',
        indexUrl: '{{ route('etatRealisationChapitres.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatRealisationChapitres.create') }}',
        editUrl: '{{ route('etatRealisationChapitres.edit',  ['etatRealisationChapitre' => ':id']) }}',
        fieldMetaUrl: '{{ route('etatRealisationChapitres.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('etatRealisationChapitres.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('etatRealisationChapitres.show',  ['etatRealisationChapitre' => ':id']) }}',
        getEntityUrl: '{{ route("etatRealisationChapitres.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatRealisationChapitres.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationChapitres.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationChapitres.destroy',  ['etatRealisationChapitre' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatRealisationChapitre')),
        calculationUrl:  '{{ route('etatRealisationChapitres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::etatRealisationChapitre.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationChapitre.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationChapitre_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationChapitre-crud" class="crud">
    @section('etatRealisationChapitre-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::etatRealisationChapitre.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationChapitre-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $etatRealisationChapitre_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationChapitre-crud-table')
    <section id="etatRealisationChapitre-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationChapitre-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationChapitres_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatRealisationChapitre_instance"
                                    :createPermission="'create-etatRealisationChapitre'"
                                    :createRoute="route('etatRealisationChapitres.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatRealisationChapitre'"
                                    :importRoute="route('etatRealisationChapitres.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatRealisationChapitre'"
                                    :exportXlsxRoute="route('etatRealisationChapitres.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatRealisationChapitres.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatRealisationChapitre_viewTypes"
                                    :viewType="$etatRealisationChapitre_viewType"
                                    :total="$etatRealisationChapitres_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationChapitre-crud-filters')
                @if(!empty($etatRealisationChapitres_total) &&  $etatRealisationChapitres_total > 5)
                <div class="card-header">
                    <form id="etatRealisationChapitre-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationChapitres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationChapitres_filters as $filter)
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
                        @section('etatRealisationChapitre-crud-search-bar')
                        <div id="etatRealisationChapitre-crud-search-bar"
                            class="{{ count($etatRealisationChapitres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationChapitres_search')"
                                name="etatRealisationChapitres_search"
                                id="etatRealisationChapitres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatRealisationChapitre-data-container" class="data-container">
                    @if($etatRealisationChapitre_viewType != "widgets")
                    @include("PkgApprentissage::etatRealisationChapitre._$etatRealisationChapitre_viewType")
                    @endif
                </div>
                @section('etatRealisationChapitre-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationChapitre")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationChapitres.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationChapitre')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationChapitres.bulkDelete') }}" 
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
     <section id="etatRealisationChapitre-data-container-out" >
        @if($etatRealisationChapitre_viewType == "widgets")
        @include("PkgApprentissage::etatRealisationChapitre._$etatRealisationChapitre_viewType")
        @endif
    </section>
    @show
</div>