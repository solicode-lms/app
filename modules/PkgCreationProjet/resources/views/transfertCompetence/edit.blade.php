{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::transfertCompetence.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'transfertCompetence',
        filterFormSelector: '#transfertCompetence-crud-filter-form',
        crudSelector: '#card-tab-transfertCompetence', 
        formSelector: '#transfertCompetenceForm',
        editUrl: '{{ route('transfertCompetences.edit',  ['transfertCompetence' => ':id']) }}',
        indexUrl: '{{ route('transfertCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
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
                <div id="card-tab-transfertCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-transfertCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="transfertCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#transfertCompetence-hasmany-tabs-home" role="tab" aria-controls="transfertCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationProjet::transfertCompetence.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="transfertCompetence-hasmany-tabs-validation-tab" data-toggle="pill" href="#transfertCompetence-hasmany-tabs-validation" role="tab" aria-controls="transfertCompetence-hasmany-tabs-validation" aria-selected="false">{{__('PkgRealisationProjets::validation.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-transfertCompetence-tabContent">
                            <div class="tab-pane fade show active" id="transfertCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="transfertCompetence-hasmany-tabs-home-tab">
                                @include('PkgCreationProjet::transfertCompetence._fields')
                            </div>

                            <div class="tab-pane fade" id="transfertCompetence-hasmany-tabs-validation" role="tabpanel" aria-labelledby="transfertCompetence-hasmany-tabs-validation-tab">
                                @include('PkgRealisationProjets::validation._index',['isMany' => true])
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
