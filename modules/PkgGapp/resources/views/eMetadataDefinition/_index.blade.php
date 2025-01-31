{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'eMetadataDefinition',
        filterFormSelector: '#eMetadataDefinition-crud-filter-form',
        crudSelector: '#eMetadataDefinition-crud',
        tableSelector: '#eMetadataDefinition-data-container',
        formSelector: '#eMetadataDefinitionForm',
        modalSelector : '#eMetadataDefinitionModal',
        indexUrl: '{{ route('eMetadataDefinitions.index') }}', 
        createUrl: '{{ route('eMetadataDefinitions.create') }}',
        editUrl: '{{ route('eMetadataDefinitions.edit',  ['eMetadataDefinition' => ':id']) }}',
        showUrl: '{{ route('eMetadataDefinitions.show',  ['eMetadataDefinition' => ':id']) }}',
        storeUrl: '{{ route('eMetadataDefinitions.store') }}', 
        deleteUrl: '{{ route('eMetadataDefinitions.destroy',  ['eMetadataDefinition' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadataDefinition.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadataDefinition.singular") }}',
    });
</script>
@endpush
<div id="eMetadataDefinition-crud" class="crud">
    @section('eMetadataDefinition-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eMetadataDefinition.singular");
    @endphp
    <x-crud-header 
        id="eMetadataDefinition-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::eMetadataDefinition.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eMetadataDefinition-crud-table')
    <section id="eMetadataDefinition-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eMetadataDefinition-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eMetadataDefinitions_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-eMetadataDefinition'"
                            :createRoute="route('eMetadataDefinitions.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eMetadataDefinition'"
                            :importRoute="route('eMetadataDefinitions.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eMetadataDefinition'"
                            :exportRoute="route('eMetadataDefinitions.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('eMetadataDefinition-crud-filters')
                <div class="card-header">
                    <form id="eMetadataDefinition-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eMetadataDefinitions_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eMetadataDefinitions_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('eMetadataDefinition-crud-search-bar')
                        <div id="eMetadataDefinition-crud-search-bar"
                            class="{{ count($eMetadataDefinitions_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eMetadataDefinitions_search')"
                                name="eMetadataDefinitions_search"
                                id="eMetadataDefinitions_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eMetadataDefinition-data-container" class="data-container">
                    @include('PkgGapp::eMetadataDefinition._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('eMetadataDefinition-crud-modal')
    <x-modal id="eMetadataDefinitionModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>