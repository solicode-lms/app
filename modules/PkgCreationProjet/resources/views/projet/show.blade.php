{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCreationProjet::projet.singular'))
 
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
                                <i class="nav-icon fas fa-lightbulb"></i>
                                 {{__("Core::msg.show") . " : " . __("PkgCreationProjet::projet.singular") }} - {{ $itemRealisationMicroCompetence }}
                            </h3>
                        </div>
                        <!-- Inclure le formulaire -->
                        @include('PkgCreationProjet::projet._show')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

