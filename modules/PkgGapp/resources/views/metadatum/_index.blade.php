{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'metadatum',
        filterFormSelector: '#metadatum-crud-filter-form',
        crudSelector: '#metadatum-crud',
        tableSelector: '#metadatum-data-container',
        formSelector: '#metadatumForm',
        modalSelector : '#metadatumModal',
        indexUrl: '{{ route('metadata.index') }}', 
        createUrl: '{{ route('metadata.create') }}',
        editUrl: '{{ route('metadata.edit',  ['metadatum' => ':id']) }}',
        showUrl: '{{ route('metadata.show',  ['metadatum' => ':id']) }}',
        storeUrl: '{{ route('metadata.store') }}', 
        deleteUrl: '{{ route('metadata.destroy',  ['metadatum' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::metadatum.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::metadatum.singular") }}',
    });
</script>
@endpush
<div id="metadatum-crud" class="crud">
    @section('metadatum-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::metadatum.singular");
    @endphp
    <x-crud-header 
        id="metadatum-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::metadatum.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('metadatum-crud-table')
    <section id="metadatum-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('metadatum-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$metadata_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-metadatum'"
                            :createRoute="route('metadata.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-metadatum'"
                            :importRoute="route('metadata.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-metadatum'"
                            :exportRoute="route('metadata.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('metadatum-crud-filters')
                <div class="card-header">
                    <form id="metadatum-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($metadata_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('metadatum-crud-search-bar')
                        <div id="metadatum-crud-search-bar"
                            class="{{ count($metadata_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('metadata_search')"
                                name="metadata_search"
                                id="metadata_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="metadatum-data-container" class="data-container">
                    @include('PkgGapp::metadatum._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('metadatum-crud-modal')
    <x-modal id="metadatumModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>