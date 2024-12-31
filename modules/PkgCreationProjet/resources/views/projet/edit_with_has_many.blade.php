{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::projet.singular'))
@section('content')
    <div class="content-header">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}.
        </div>
        @endif
        @if (session('info'))
        <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('info') }}.
        </div>
        @endif
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div class="card card-info card-tabs card-workflow">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="edit-projet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="projet-hasmany-tabs-home-tab" data-toggle="pill" href="#projet-hasmany-tabs-home" role="tab" aria-controls="projet-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationProjet::projet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-livrable-tab" data-toggle="pill" href="#projet-hasmany-tabs-livrable" role="tab" aria-controls="projet-hasmany-tabs-livrable" aria-selected="false">{{__('PkgCreationProjet::livrable.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-resource-tab" data-toggle="pill" href="#projet-hasmany-tabs-resource" role="tab" aria-controls="projet-hasmany-tabs-resource" aria-selected="false">{{__('PkgCreationProjet::resource.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-transfertCompetence-tab" data-toggle="pill" href="#projet-hasmany-tabs-transfertCompetence" role="tab" aria-controls="projet-hasmany-tabs-transfertCompetence" aria-selected="false">{{__('PkgCreationProjet::transfertCompetence.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-projet-tabContent">
                            <div class="tab-pane fade show active" id="projet-hasmany-tabs-home" role="tabpanel" aria-labelledby="projet-hasmany-tabs-home-tab">
                                @include('PkgCreationProjet::projet._fields')
                            </div>

                            <div class="tab-pane fade" id="projet-hasmany-tabs-livrable" role="tabpanel" aria-labelledby="projet-hasmany-tabs-livrable-tab">
                                @include('PkgCreationProjet::livrable._index')
                            </div>
                            <div class="tab-pane fade" id="projet-hasmany-tabs-resource" role="tabpanel" aria-labelledby="projet-hasmany-tabs-resource-tab">
                                @include('PkgCreationProjet::resource._index')
                            </div>
                            <div class="tab-pane fade" id="projet-hasmany-tabs-transfertCompetence" role="tabpanel" aria-labelledby="projet-hasmany-tabs-transfertCompetence-tab">
                                @include('PkgCreationProjet::transfertCompetence._index')
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
