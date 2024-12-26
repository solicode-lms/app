{{-- TODO  : Création par étape : hasMany --}}




@extends('layouts.admin')
@section('title', curd_index_add_label('PkgCreationProjet::projet'))

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
                                <i class="nav-icon fas fa-table"></i>
                                {{ curd_index_add_label('PkgCreationProjet::projet') }}
                            </h3>
                        </div>


                
                        <div class="container">
                          <div class="mt-5 mb-5 text-center">
                            <h2>Additional element : Steps Using Bootstrap 4</h2>
                          </div>
                          <ul class="step d-flex flex-nowrap">
                          <li class="step-item">
                            <a href="#!" class="">Step 1</a>
                          </li>
                          <li class="step-item">
                            <a href="#!" class="">Step 2</a>
                          </li>
                          <li class="step-item active">
                            <a href="#!" class="">Step 3</a>
                          </li>
                          <li class="step-item">
                            <a href="#!" class="">Step 4</a>
                          </li>
                        </ul> 
                        <div class="text-muted mt-5 text-center small">by : <a class="text-muted" target="_blank" href="http://totoprayogo.com">totoprayogo.com</a></div>
                        </div>

                        <!-- Obtenir le formulaire -->
                        @include('PkgCreationProjet::projet._fields')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
