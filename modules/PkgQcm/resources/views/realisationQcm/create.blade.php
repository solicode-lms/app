{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_add_label('PkgQcm::realisationQcm'))

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
                                <i class="nav-icon fas fa-cog"></i>
                                {{ curd_index_add_label('PkgQcm::realisationQcm') }}
                            </h3>
                        </div>
                        <!-- Obtenir le formulaire -->
                        @include('PkgQcm::realisationQcm._fields')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
