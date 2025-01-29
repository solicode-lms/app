{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::appreciation.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'appreciation',
        filterFormSelector: '#appreciation-crud-filter-form',
        crudSelector: '#card-tab-appreciation', 
        formSelector: '#appreciationForm',
        editUrl: '{{ route('appreciations.edit',  ['appreciation' => ':id']) }}',
        indexUrl: '{{ route('appreciations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::appreciation.singular") }}',
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
                <div id="card-tab-appreciation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-appreciation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-user"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="appreciation-hasmany-tabs-home-tab" data-toggle="pill" href="#appreciation-hasmany-tabs-home" role="tab" aria-controls="appreciation-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::appreciation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="appreciation-hasmany-tabs-transfertCompetence-tab" data-toggle="pill" href="#appreciation-hasmany-tabs-transfertCompetence" role="tab" aria-controls="appreciation-hasmany-tabs-transfertCompetence" aria-selected="false">{{__('PkgCreationProjet::transfertCompetence.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-appreciation-tabContent">
                            <div class="tab-pane fade show active" id="appreciation-hasmany-tabs-home" role="tabpanel" aria-labelledby="appreciation-hasmany-tabs-home-tab">
                                @include('PkgCompetences::appreciation._fields')
                            </div>

                            <div class="tab-pane fade" id="appreciation-hasmany-tabs-transfertCompetence" role="tabpanel" aria-labelledby="appreciation-hasmany-tabs-transfertCompetence-tab">
                                @include('PkgCreationProjet::transfertCompetence._index',['isMany' => true])
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
