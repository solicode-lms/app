{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'livrable',
        filterFormSelector: '#livrable-crud-filter-form',
        crudSelector: '#livrable-crud',
        tableSelector: '#livrable-data-container',
        formSelector: '#livrableForm',
        modalSelector : '#livrableModal',
        indexUrl: '{{ route('livrables.index') }}', 
        createUrl: '{{ route('livrables.create') }}',
        editUrl: '{{ route('livrables.edit',  ['livrable' => ':id']) }}',
        showUrl: '{{ route('livrables.show',  ['livrable' => ':id']) }}',
        storeUrl: '{{ route('livrables.store') }}', 
        deleteUrl: '{{ route('livrables.destroy',  ['livrable' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',
    });
</script>
@endpush
<div id="livrable-crud" class="crud">
    @section('livrable-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::livrable.singular");
    @endphp
    <x-crud-header 
        id="livrable-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::livrable.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('livrable-crud-table')
    <section id="livrable-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('livrable-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$livrables_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-livrable'"
                            :createRoute="route('livrables.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-livrable'"
                            :importRoute="route('livrables.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-livrable'"
                            :exportRoute="route('livrables.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('livrable-crud-filters')
                <div class="card-header">
                    <form id="livrable-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($livrables_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('livrable-crud-search-bar')
                        <div id="livrable-crud-search-bar"
                            class="{{ count($livrables_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('livrables_search')"
                                name="livrables_search"
                                id="livrables_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="livrable-data-container" class="data-container">
                    @include('PkgCreationProjet::livrable._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('livrable-crud-modal')
    <x-modal id="livrableModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>