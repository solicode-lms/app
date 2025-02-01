{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'groupe',
        filterFormSelector: '#groupe-crud-filter-form',
        crudSelector: '#groupe-crud',
        tableSelector: '#groupe-data-container',
        formSelector: '#groupeForm',
        modalSelector : '#groupeModal',
        indexUrl: '{{ route('groupes.index') }}', 
        createUrl: '{{ route('groupes.create') }}',
        editUrl: '{{ route('groupes.edit',  ['groupe' => ':id']) }}',
        showUrl: '{{ route('groupes.show',  ['groupe' => ':id']) }}',
        storeUrl: '{{ route('groupes.store') }}', 
        deleteUrl: '{{ route('groupes.destroy',  ['groupe' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::groupe.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::groupe.singular") }}',
    });
</script>
@endpush
<div id="groupe-crud" class="crud">
    @section('groupe-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::groupe.singular");
    @endphp
    <x-crud-header 
        id="groupe-crud-header" icon="fas fa-cubes"  
        iconColor="text-info"
        title="{{ __('PkgApprenants::groupe.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('groupe-crud-table')
    <section id="groupe-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('groupe-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$groupes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-groupe'"
                            :createRoute="route('groupes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-groupe'"
                            :importRoute="route('groupes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-groupe'"
                            :exportRoute="route('groupes.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('groupe-crud-filters')
                <div class="card-header">
                    <form id="groupe-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($groupes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($groupes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('groupe-crud-search-bar')
                        <div id="groupe-crud-search-bar"
                            class="{{ count($groupes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('groupes_search')"
                                name="groupes_search"
                                id="groupes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="groupe-data-container" class="data-container">
                    @include('PkgApprenants::groupe._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('groupe-crud-modal')
    <x-modal id="groupeModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>