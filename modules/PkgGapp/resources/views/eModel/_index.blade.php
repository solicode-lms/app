{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'eModel',
        filterFormSelector: '#eModel-crud-filter-form',
        crudSelector: '#eModel-crud',
        tableSelector: '#eModel-data-container',
        formSelector: '#eModelForm',
        modalSelector : '#eModelModal',
        indexUrl: '{{ route('eModels.index') }}', 
        createUrl: '{{ route('eModels.create') }}',
        editUrl: '{{ route('eModels.edit',  ['eModel' => ':id']) }}',
        showUrl: '{{ route('eModels.show',  ['eModel' => ':id']) }}',
        storeUrl: '{{ route('eModels.store') }}', 
        deleteUrl: '{{ route('eModels.destroy',  ['eModel' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eModel.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eModel.singular") }}',
    });
</script>
@endpush
<div id="eModel-crud" class="crud">
    @section('eModel-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eModel.singular");
    @endphp
    <x-crud-header 
        id="eModel-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::eModel.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eModel-crud-table')
    <section id="eModel-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eModel-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eModels_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-eModel'"
                            :createRoute="route('eModels.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eModel'"
                            :importRoute="route('eModels.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eModel'"
                            :exportRoute="route('eModels.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('eModel-crud-filters')
                <div class="card-header">
                    <form id="eModel-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eModels_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('eModel-crud-search-bar')
                        <div id="eModel-crud-search-bar"
                            class="{{ count($eModels_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eModels_search')"
                                name="eModels_search"
                                id="eModels_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eModel-data-container" class="data-container">
                    @include('PkgGapp::eModel._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('eModel-crud-modal')
    <x-modal id="eModelModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>