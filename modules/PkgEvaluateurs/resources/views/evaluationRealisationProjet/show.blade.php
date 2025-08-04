{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgEvaluateurs::evaluationRealisationProjet.singular'))
 
@section('content')
    <div class="content-header">
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info crud-show">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                                 {{__("Core::msg.show") . " : " . __("PkgEvaluateurs::evaluationRealisationProjet.singular") }} - {{ $itemRealisationMicroCompetence }}
                            </h3>
                        </div>
                        <!-- Inclure le formulaire -->
                        @include('PkgEvaluateurs::evaluationRealisationProjet._show')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

