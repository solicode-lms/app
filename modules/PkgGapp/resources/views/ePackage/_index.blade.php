{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: {{ isset($edit_has_many) && $edit_has_many ? 'true' : 'false' }},
        entity_name: 'ePackage',
        filterFormSelector: '#ePackage-crud-filter-form',
        crudSelector: '#ePackage-crud',
        tableSelector: '#ePackage-data-container',
        formSelector: '#ePackageForm',
        modalSelector : '#ePackageModal',
        indexUrl: '{{ route('ePackages.index') }}', 
        createUrl: '{{ route('ePackages.create') }}',
        editUrl: '{{ route('ePackages.edit',  ['ePackage' => ':id']) }}',
        showUrl: '{{ route('ePackages.show',  ['ePackage' => ':id']) }}',
        storeUrl: '{{ route('ePackages.store') }}', 
        deleteUrl: '{{ route('ePackages.destroy',  ['ePackage' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::ePackage.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::ePackage.singular") }}',
    });
</script>
@endpush
<div id="ePackage-crud" class="crud">
    @section('ePackage-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::ePackage.singular");
    @endphp

    <x-crud-header 
        id="ePackage-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::ePackage.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />

    @show
    @section('ePackage-crud-table')
    <section id="ePackage-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('ePackage-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$ePackages_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-ePackage'"
                            :createRoute="route('ePackages.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-ePackage'"
                            :importRoute="route('ePackages.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-ePackage'"
                            :exportRoute="route('ePackages.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('ePackage-crud-filters')
                <div class="card-header">
                    <form id="ePackage-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($ePackages_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($ePackages_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('ePackage-crud-search-bar')
                        <div id="ePackage-crud-search-bar"
                            class="{{ count($ePackages_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('ePackages_search')"
                                name="ePackages_search"
                                id="ePackages_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="ePackage-data-container" class="data-container">
                    @include('PkgGapp::ePackage._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('ePackage-crud-modal')
    <x-modal id="ePackageModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>