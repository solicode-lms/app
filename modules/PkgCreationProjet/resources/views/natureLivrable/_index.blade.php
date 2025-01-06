{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'natureLivrable',
        filterFormSelector: '#natureLivrable-crud-filter-form',
        crudSelector: '#natureLivrable-crud',
        tableSelector: '#natureLivrable-data-container',
        formSelector: '#natureLivrableForm',
        modalSelector : '#natureLivrableModal',
        indexUrl: '{{ route('natureLivrables.index') }}', 
        createUrl: '{{ route('natureLivrables.create') }}',
        editUrl: '{{ route('natureLivrables.edit',  ['natureLivrable' => ':id']) }}',
        showUrl: '{{ route('natureLivrables.show',  ['natureLivrable' => ':id']) }}',
        storeUrl: '{{ route('natureLivrables.store') }}', 
        deleteUrl: '{{ route('natureLivrables.destroy',  ['natureLivrable' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
    });
</script>
@endpush
<div id="natureLivrable-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::natureLivrable.singular");
    @endphp
    <x-crud-header 
        id="natureLivrable-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::natureLivrable.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="natureLivrable-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$natureLivrables_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-natureLivrable'"
                            :createRoute="route('natureLivrables.create')"
                            :createText="__('Ajouter une natureLivrable')"
                            :importPermission="'import-natureLivrable'"
                            :importRoute="route('natureLivrables.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-natureLivrable'"
                            :exportRoute="route('natureLivrables.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="natureLivrable-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($natureLivrables_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="natureLivrable-crud-search-bar"
                            class="{{ count($natureLivrables_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('natureLivrables_search')"
                                name="natureLivrables_search"
                                id="natureLivrables_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="natureLivrable-data-container" class="data-container">
                    @include('PkgCreationProjet::natureLivrable._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="natureLivrableModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>