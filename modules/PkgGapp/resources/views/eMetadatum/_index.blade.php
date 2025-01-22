{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'eMetadatum',
        filterFormSelector: '#eMetadatum-crud-filter-form',
        crudSelector: '#eMetadatum-crud',
        tableSelector: '#eMetadatum-data-container',
        formSelector: '#eMetadatumForm',
        modalSelector : '#eMetadatumModal',
        indexUrl: '{{ route('eMetadata.index') }}', 
        createUrl: '{{ route('eMetadata.create') }}',
        editUrl: '{{ route('eMetadata.edit',  ['eMetadatum' => ':id']) }}',
        showUrl: '{{ route('eMetadata.show',  ['eMetadatum' => ':id']) }}',
        storeUrl: '{{ route('eMetadata.store') }}', 
        deleteUrl: '{{ route('eMetadata.destroy',  ['eMetadatum' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadatum.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadatum.singular") }}',
    });
</script>
@endpush
<div id="eMetadatum-crud" class="crud">
    @section('eMetadatum-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eMetadatum.singular");
    @endphp
    <x-crud-header 
        id="eMetadatum-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::eMetadatum.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eMetadatum-crud-table')
    <section id="eMetadatum-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eMetadatum-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eMetadata_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-eMetadatum'"
                            :createRoute="route('eMetadata.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eMetadatum'"
                            :importRoute="route('eMetadata.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eMetadatum'"
                            :exportRoute="route('eMetadata.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('eMetadatum-crud-filters')
                <div class="card-header">
                    <form id="eMetadatum-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eMetadata_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eMetadata_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('eMetadatum-crud-search-bar')
                        <div id="eMetadatum-crud-search-bar"
                            class="{{ count($eMetadata_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eMetadata_search')"
                                name="eMetadata_search"
                                id="eMetadata_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eMetadatum-data-container" class="data-container">
                    @include('PkgGapp::eMetadatum._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('eMetadatum-crud-modal')
    <x-modal id="eMetadatumModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>