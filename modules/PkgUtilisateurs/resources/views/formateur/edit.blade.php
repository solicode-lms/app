{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgUtilisateurs::formateur.singular'))

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
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </div>
                        <!-- Inclure le formulaire -->
                        @include('PkgUtilisateurs::formateur._fields')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
