{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationProjets::realisationProjet.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'realisationProjet',
        filterFormSelector: '#realisationProjet-crud-filter-form',
        crudSelector: '#card-tab-realisationProjet', 
        formSelector: '#realisationProjetForm',
        editUrl: '{{ route('realisationProjets.edit',  ['realisationProjet' => ':id']) }}',
        indexUrl: '{{ route('realisationProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::realisationProjet.singular") }}',
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
                <div id="card-tab-realisationProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationProjet-hasmany-tabs-home" role="tab" aria-controls="realisationProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationProjets::realisationProjet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="realisationProjet-hasmany-tabs-validation-tab" data-toggle="pill" href="#realisationProjet-hasmany-tabs-validation" role="tab" aria-controls="realisationProjet-hasmany-tabs-validation" aria-selected="false">{{__('PkgRealisationProjets::validation.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationProjet-tabContent">
                            <div class="tab-pane fade show active" id="realisationProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationProjet-hasmany-tabs-home-tab">
                                @include('PkgRealisationProjets::realisationProjet._fields')
                            </div>

                            <div class="tab-pane fade" id="realisationProjet-hasmany-tabs-validation" role="tabpanel" aria-labelledby="realisationProjet-hasmany-tabs-validation-tab">
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
