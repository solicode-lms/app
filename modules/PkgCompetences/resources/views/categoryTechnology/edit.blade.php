{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::categoryTechnology.singular'))

@push('scripts')
<script>
    window.contextState = @json($contextState);
 </script>
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'categoryTechnology',
        filterFormSelector: '#categoryTechnology-crud-filter-form',
        crudSelector: '#card-tab-categoryTechnology', 
        tableSelector: '#categoryTechnology-data-container',
        formSelector: '#categoryTechnologyForm',
        modalSelector : '#categoryTechnologyModal',
        indexUrl: '{{ route('categoryTechnologies.index') }}', 
        createUrl: '{{ route('categoryTechnologies.create') }}',
        editUrl: '{{ route('categoryTechnologies.edit',  ['categoryTechnology' => ':id']) }}',
        showUrl: '{{ route('categoryTechnologies.show',  ['categoryTechnology' => ':id']) }}',
        storeUrl: '{{ route('categoryTechnologies.store') }}', 
        deleteUrl: '{{ route('categoryTechnologies.destroy',  ['categoryTechnology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
    });
</script>
@endpush


@section('content')
    <div class="content-header">
    <!-- debug
    @foreach ($contextState as $key => $value)
    Key: {{ $key }}, Value: {{ $value }}<br>
    @endforeach
     -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-categoryTechnology" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-categoryTechnology-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-bolt"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="categoryTechnology-hasmany-tabs-home-tab" data-toggle="pill" href="#categoryTechnology-hasmany-tabs-home" role="tab" aria-controls="categoryTechnology-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::categoryTechnology.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="categoryTechnology-hasmany-tabs-technology-tab" data-toggle="pill" href="#categoryTechnology-hasmany-tabs-technology" role="tab" aria-controls="categoryTechnology-hasmany-tabs-technology" aria-selected="false">{{__('PkgCompetences::technology.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-categoryTechnology-tabContent">
                            <div class="tab-pane fade show active" id="categoryTechnology-hasmany-tabs-home" role="tabpanel" aria-labelledby="categoryTechnology-hasmany-tabs-home-tab">
                                @include('PkgCompetences::categoryTechnology._fields')
                            </div>

                            <div class="tab-pane fade" id="categoryTechnology-hasmany-tabs-technology" role="tabpanel" aria-labelledby="categoryTechnology-hasmany-tabs-technology-tab">
                                @include('PkgCompetences::technology._index')
                            </div>

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@endsection
