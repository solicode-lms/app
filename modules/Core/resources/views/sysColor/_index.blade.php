{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'sysColor',
        filterFormSelector: '#sysColor-crud-filter-form',
        crudSelector: '#sysColor-crud',
        tableSelector: '#sysColor-data-container',
        formSelector: '#sysColorForm',
        modalSelector : '#sysColorModal',
        indexUrl: '{{ route('sysColors.index') }}', 
        createUrl: '{{ route('sysColors.create') }}',
        editUrl: '{{ route('sysColors.edit',  ['sysColor' => ':id']) }}',
        showUrl: '{{ route('sysColors.show',  ['sysColor' => ':id']) }}',
        storeUrl: '{{ route('sysColors.store') }}', 
        deleteUrl: '{{ route('sysColors.destroy',  ['sysColor' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
    });
</script>
@endpush
<div id="sysColor-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::groupe.singular");
    @endphp
    <x-crud-header 
        id="sysColor-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('Core::sysColor.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="sysColor-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$sysColors_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-sysColor'"
                            :createRoute="route('sysColors.create')"
                            :createText="__('Ajouter une sysColor')"
                            :importPermission="'import-sysColor'"
                            :importRoute="route('sysColors.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-sysColor'"
                            :exportRoute="route('sysColors.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="sysColor-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($sysColors_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="sysColor-crud-search-bar"
                            class="{{ count($sysColors_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysColors_search')"
                                name="sysColors_search"
                                id="sysColors_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysColor-data-container" class="data-container">
                    @include('Core::sysColor._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="sysColorModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>