{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::module.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'module',
        filterFormSelector: '#module-crud-filter-form',
        crudSelector: '#card-tab-module', 
        formSelector: '#moduleForm',
        editUrl: '{{ route('modules.edit',  ['module' => ':id']) }}',
        indexUrl: '{{ route('modules.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::module.singular") }}',
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
                <div id="card-tab-module" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-module-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-puzzle-piece"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="module-hasmany-tabs-home-tab" data-toggle="pill" href="#module-hasmany-tabs-home" role="tab" aria-controls="module-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::module.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="module-hasmany-tabs-competence-tab" data-toggle="pill" href="#module-hasmany-tabs-competence" role="tab" aria-controls="module-hasmany-tabs-competence" aria-selected="false">{{__('PkgCompetences::competence.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-module-tabContent">
                            <div class="tab-pane fade show active" id="module-hasmany-tabs-home" role="tabpanel" aria-labelledby="module-hasmany-tabs-home-tab">
                                @include('PkgCompetences::module._fields')
                            </div>

                            <div class="tab-pane fade" id="module-hasmany-tabs-competence" role="tabpanel" aria-labelledby="module-hasmany-tabs-competence-tab">
                                @include('PkgCompetences::competence._index',['isMany' => true])
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
