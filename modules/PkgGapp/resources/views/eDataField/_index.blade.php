{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'eDataField',
        filterFormSelector: '#eDataField-crud-filter-form',
        crudSelector: '#eDataField-crud',
        tableSelector: '#eDataField-data-container',
        formSelector: '#eDataFieldForm',
        modalSelector : '#eDataFieldModal',
        indexUrl: '{{ route('eDataFields.index') }}', 
        createUrl: '{{ route('eDataFields.create') }}',
        editUrl: '{{ route('eDataFields.edit',  ['eDataField' => ':id']) }}',
        showUrl: '{{ route('eDataFields.show',  ['eDataField' => ':id']) }}',
        storeUrl: '{{ route('eDataFields.store') }}', 
        deleteUrl: '{{ route('eDataFields.destroy',  ['eDataField' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eDataField.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eDataField.singular") }}',
    });
</script>
@endpush
<div id="eDataField-crud" class="crud">
    @section('eDataField-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eDataField.singular");
    @endphp
    <x-crud-header 
        id="eDataField-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgGapp::eDataField.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eDataField-crud-table')
    <section id="eDataField-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eDataField-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eDataFields_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-eDataField'"
                            :createRoute="route('eDataFields.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eDataField'"
                            :importRoute="route('eDataFields.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eDataField'"
                            :exportRoute="route('eDataFields.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('eDataField-crud-filters')
                <div class="card-header">
                    <form id="eDataField-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eDataFields_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eDataFields_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('eDataField-crud-search-bar')
                        <div id="eDataField-crud-search-bar"
                            class="{{ count($eDataFields_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eDataFields_search')"
                                name="eDataFields_search"
                                id="eDataFields_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eDataField-data-container" class="data-container">
                    @include('PkgGapp::eDataField._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('eDataField-crud-modal')
    <x-modal id="eDataFieldModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>