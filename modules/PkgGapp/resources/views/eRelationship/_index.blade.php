{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'eRelationship',
        filterFormSelector: '#eRelationship-crud-filter-form',
        crudSelector: '#eRelationship-crud',
        tableSelector: '#eRelationship-data-container',
        formSelector: '#eRelationshipForm',
        modalSelector : '#eRelationshipModal',
        indexUrl: '{{ route('eRelationships.index') }}', 
        createUrl: '{{ route('eRelationships.create') }}',
        editUrl: '{{ route('eRelationships.edit',  ['eRelationship' => ':id']) }}',
        showUrl: '{{ route('eRelationships.show',  ['eRelationship' => ':id']) }}',
        storeUrl: '{{ route('eRelationships.store') }}', 
        deleteUrl: '{{ route('eRelationships.destroy',  ['eRelationship' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eRelationship.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eRelationship.singular") }}',
    });
</script>
@endpush
<div id="eRelationship-crud" class="crud">
    @section('eRelationship-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eRelationship.singular");
    @endphp
    <x-crud-header 
        id="eRelationship-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::eRelationship.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eRelationship-crud-table')
    <section id="eRelationship-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eRelationship-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eRelationships_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-eRelationship'"
                            :createRoute="route('eRelationships.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eRelationship'"
                            :importRoute="route('eRelationships.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eRelationship'"
                            :exportRoute="route('eRelationships.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('eRelationship-crud-filters')
                <div class="card-header">
                    <form id="eRelationship-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eRelationships_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('eRelationship-crud-search-bar')
                        <div id="eRelationship-crud-search-bar"
                            class="{{ count($eRelationships_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eRelationships_search')"
                                name="eRelationships_search"
                                id="eRelationships_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eRelationship-data-container" class="data-container">
                    @include('PkgGapp::eRelationship._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('eRelationship-crud-modal')
    <x-modal id="eRelationshipModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>