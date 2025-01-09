{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'metadataType',
        filterFormSelector: '#metadataType-crud-filter-form',
        crudSelector: '#metadataType-crud',
        tableSelector: '#metadataType-data-container',
        formSelector: '#metadataTypeForm',
        modalSelector : '#metadataTypeModal',
        indexUrl: '{{ route('metadataTypes.index') }}', 
        createUrl: '{{ route('metadataTypes.create') }}',
        editUrl: '{{ route('metadataTypes.edit',  ['metadataType' => ':id']) }}',
        showUrl: '{{ route('metadataTypes.show',  ['metadataType' => ':id']) }}',
        storeUrl: '{{ route('metadataTypes.store') }}', 
        deleteUrl: '{{ route('metadataTypes.destroy',  ['metadataType' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::metadataType.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::metadataType.singular") }}',
    });
</script>
@endpush
<div id="metadataType-crud" class="crud">
    @section('metadataType-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::metadataType.singular");
    @endphp
    <x-crud-header 
        id="metadataType-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::metadataType.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('metadataType-crud-table')
    <section id="metadataType-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('metadataType-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$metadataTypes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-metadataType'"
                            :createRoute="route('metadataTypes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-metadataType'"
                            :importRoute="route('metadataTypes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-metadataType'"
                            :exportRoute="route('metadataTypes.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('metadataType-crud-filters')
                <div class="card-header">
                    <form id="metadataType-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($metadataTypes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('metadataType-crud-search-bar')
                        <div id="metadataType-crud-search-bar"
                            class="{{ count($metadataTypes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('metadataTypes_search')"
                                name="metadataTypes_search"
                                id="metadataTypes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="metadataType-data-container" class="data-container">
                    @include('PkgGapp::metadataType._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('metadataType-crud-modal')
    <x-modal id="metadataTypeModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>