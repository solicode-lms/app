{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::role'))
@section('content')
    <div class="content-header">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}.
            </div>
        @endif
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ curd_index_title('PkgAutorisation::role') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        @can('create-RoleController')
                            <a href="{{ route('roles.create') }}" class="btn btn-info">
                                <i class="fas fa-plus"></i>
                                {{ curd_index_add_label('PkgAutorisation::role') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="content" id="section_crud">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="card_crud">
                        <div class="card-header col-md-12">
                            <div class="p-0">
                                <div class="input-group input-group-sm float-sm-right col-md-3 p-0">
                                    <input type="text" name="crud_search_input" id="crud_search_input"
                                           class="form-control float-right" placeholder="Recherche">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="data-container">
                        @include('PkgAutorisation::role._table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id='page' value="1">
    </section>
@endsection
