{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'iPackage',
        filterFormSelector: '#iPackage-crud-filter-form',
        crudSelector: '#iPackage-crud',
        tableSelector: '#iPackage-data-container',
        formSelector: '#iPackageForm',
        modalSelector : '#iPackageModal',
        indexUrl: '{{ route('iPackages.index') }}', 
        createUrl: '{{ route('iPackages.create') }}',
        editUrl: '{{ route('iPackages.edit',  ['iPackage' => ':id']) }}',
        showUrl: '{{ route('iPackages.show',  ['iPackage' => ':id']) }}',
        storeUrl: '{{ route('iPackages.store') }}', 
        deleteUrl: '{{ route('iPackages.destroy',  ['iPackage' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::iPackage.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::iPackage.singular") }}',
    });
</script>
@endpush
<div id="iPackage-crud" class="crud">
    @section('iPackage-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::iPackage.singular");
    @endphp
    <x-crud-header 
        id="iPackage-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::iPackage.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('iPackage-crud-table')
    <section id="iPackage-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('iPackage-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$iPackages_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-iPackage'"
                            :createRoute="route('iPackages.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-iPackage'"
                            :importRoute="route('iPackages.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-iPackage'"
                            :exportRoute="route('iPackages.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('iPackage-crud-filters')
                <div class="card-header">
                    <form id="iPackage-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($iPackages_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('iPackage-crud-search-bar')
                        <div id="iPackage-crud-search-bar"
                            class="{{ count($iPackages_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('iPackages_search')"
                                name="iPackages_search"
                                id="iPackages_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="iPackage-data-container" class="data-container">
                    @include('PkgGapp::iPackage._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('iPackage-crud-modal')
    <x-modal id="iPackageModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>