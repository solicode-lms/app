{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'filiere',
        filterFormSelector: '#filiere-crud-filter-form',
        crudSelector: '#filiere-crud',
        tableSelector: '#filiere-data-container',
        formSelector: '#filiereForm',
        modalSelector : '#filiereModal',
        indexUrl: '{{ route('filieres.index') }}', 
        createUrl: '{{ route('filieres.create') }}',
        editUrl: '{{ route('filieres.edit',  ['filiere' => ':id']) }}',
        showUrl: '{{ route('filieres.show',  ['filiere' => ':id']) }}',
        storeUrl: '{{ route('filieres.store') }}', 
        deleteUrl: '{{ route('filieres.destroy',  ['filiere' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::filiere.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::filiere.singular") }}',
    });
</script>
@endpush
<div id="filiere-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::filiere.singular");
    @endphp
    <x-crud-header 
        id="filiere-crud-header" icon="fas fa-book"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::filiere.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="filiere-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$filieres_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-filiere'"
                            :createRoute="route('filieres.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-filiere'"
                            :importRoute="route('filieres.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-filiere'"
                            :exportRoute="route('filieres.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="filiere-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($filieres_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="filiere-crud-search-bar"
                            class="{{ count($filieres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('filieres_search')"
                                name="filieres_search"
                                id="filieres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="filiere-data-container" class="data-container">
                    @include('PkgCompetences::filiere._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="filiereModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>