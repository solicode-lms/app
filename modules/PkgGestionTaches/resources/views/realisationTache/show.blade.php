
@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgGestionTaches::realisationTache.singular'))
 
@section('content')
    <div class="content-header">
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-laptop-code"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </div>
                        <!-- Inclure le formulaire -->
                        @include('PkgGestionTaches::realisationTache._show')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
