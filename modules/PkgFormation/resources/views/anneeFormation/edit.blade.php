{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgFormation::anneeFormation.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'anneeFormation',
        filterFormSelector: '#anneeFormation-crud-filter-form',
        crudSelector: '#card-tab-anneeFormation', 
        formSelector: '#anneeFormationForm',
        editUrl: '{{ route('anneeFormations.edit',  ['anneeFormation' => ':id']) }}',
        indexUrl: '{{ route('anneeFormations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::anneeFormation.singular") }}',
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
                <div id="card-tab-anneeFormation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-anneeFormation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="anneeFormation-hasmany-tabs-home-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-home" role="tab" aria-controls="anneeFormation-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::anneeFormation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="anneeFormation-hasmany-tabs-affectationProjet-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-affectationProjet" role="tab" aria-controls="anneeFormation-hasmany-tabs-affectationProjet" aria-selected="false">{{__('PkgRealisationProjets::affectationProjet.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="anneeFormation-hasmany-tabs-groupe-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-groupe" role="tab" aria-controls="anneeFormation-hasmany-tabs-groupe" aria-selected="false">{{__('PkgApprenants::groupe.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-anneeFormation-tabContent">
                            <div class="tab-pane fade show active" id="anneeFormation-hasmany-tabs-home" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-home-tab">
                                @include('PkgFormation::anneeFormation._fields')
                            </div>

                            <div class="tab-pane fade" id="anneeFormation-hasmany-tabs-affectationProjet" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-affectationProjet-tab">
                                @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true])
                            </div>
                            <div class="tab-pane fade" id="anneeFormation-hasmany-tabs-groupe" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-groupe-tab">
                                @include('PkgApprenants::groupe._index',['isMany' => true])
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
