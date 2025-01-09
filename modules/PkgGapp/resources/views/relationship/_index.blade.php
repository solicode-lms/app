{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'relationship',
        filterFormSelector: '#relationship-crud-filter-form',
        crudSelector: '#relationship-crud',
        tableSelector: '#relationship-data-container',
        formSelector: '#relationshipForm',
        modalSelector : '#relationshipModal',
        indexUrl: '{{ route('relationships.index') }}', 
        createUrl: '{{ route('relationships.create') }}',
        editUrl: '{{ route('relationships.edit',  ['relationship' => ':id']) }}',
        showUrl: '{{ route('relationships.show',  ['relationship' => ':id']) }}',
        storeUrl: '{{ route('relationships.store') }}', 
        deleteUrl: '{{ route('relationships.destroy',  ['relationship' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::relationship.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::relationship.singular") }}',
    });
</script>
@endpush
<div id="relationship-crud" class="crud">
    @section('relationship-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::relationship.singular");
    @endphp
    <x-crud-header 
        id="relationship-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::relationship.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('relationship-crud-table')
    <section id="relationship-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('relationship-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$relationships_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-relationship'"
                            :createRoute="route('relationships.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-relationship'"
                            :importRoute="route('relationships.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-relationship'"
                            :exportRoute="route('relationships.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('relationship-crud-filters')
                <div class="card-header">
                    <form id="relationship-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($relationships_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('relationship-crud-search-bar')
                        <div id="relationship-crud-search-bar"
                            class="{{ count($relationships_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('relationships_search')"
                                name="relationships_search"
                                id="relationships_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="relationship-data-container" class="data-container">
                    @include('PkgGapp::relationship._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('relationship-crud-modal')
    <x-modal id="relationshipModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>