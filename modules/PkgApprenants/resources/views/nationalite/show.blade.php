{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgApprenants::nationalite.singular'))
 
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
                                <i class="nav-icon fas fa-map-marked-alt"></i>
                                 {{__("Core::msg.show") . " : " . __("PkgApprenants::nationalite.singular") }} - {{ $itemRealisationMicroCompetence }}
                            </h3>
                        </div>
                        <!-- Inclure le formulaire -->
                        @include('PkgApprenants::nationalite._show')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

