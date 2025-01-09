{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'iModel',
        filterFormSelector: '#iModel-crud-filter-form',
        crudSelector: '#iModel-crud',
        tableSelector: '#iModel-data-container',
        formSelector: '#iModelForm',
        modalSelector : '#iModelModal',
        indexUrl: '{{ route('iModels.index') }}', 
        createUrl: '{{ route('iModels.create') }}',
        editUrl: '{{ route('iModels.edit',  ['iModel' => ':id']) }}',
        showUrl: '{{ route('iModels.show',  ['iModel' => ':id']) }}',
        storeUrl: '{{ route('iModels.store') }}', 
        deleteUrl: '{{ route('iModels.destroy',  ['iModel' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::iModel.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::iModel.singular") }}',
    });
</script>
@endpush
<div id="iModel-crud" class="crud">
    @section('iModel-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::iModel.singular");
    @endphp
    <x-crud-header 
        id="iModel-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::iModel.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('iModel-crud-table')
    <section id="iModel-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('iModel-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$iModels_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-iModel'"
                            :createRoute="route('iModels.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-iModel'"
                            :importRoute="route('iModels.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-iModel'"
                            :exportRoute="route('iModels.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('iModel-crud-filters')
                <div class="card-header">
                    <form id="iModel-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($iModels_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('iModel-crud-search-bar')
                        <div id="iModel-crud-search-bar"
                            class="{{ count($iModels_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('iModels_search')"
                                name="iModels_search"
                                id="iModels_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="iModel-data-container" class="data-container">
                    @include('PkgGapp::iModel._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('iModel-crud-modal')
    <x-modal id="iModelModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>