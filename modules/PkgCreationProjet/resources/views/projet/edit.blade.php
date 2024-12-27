{{-- TODO : add HasMany edit component --}}
{{-- TODO : affichage des message de façon global pour toutes les interface pas seulement index --}}
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
      <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
        <li class="pt-2 px-3">
            <h3 class="card-title">
                <i class="nav-icon fas fa-table"></i>
                {{ __('Core::msg.edit') }}
            </h3>
        </li>
        <li class="nav-item">
          <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Projet</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Compétences</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Livrable</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill" href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">Ressources</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content" id="custom-tabs-two-tabContent">
        <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
          

             <!-- Inclure le formulaire -->
             @include('PkgCreationProjet::projet._fields')
        
        </div>
        <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
       
            @include('PkgCreationProjet::transfertCompetence._content')
         

        </div>
        <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
         Ressources
        </div>
        <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
            Livrable
        </div>
      </div>
    </div>

    
    <!-- /.card -->
  </div>






                



                  
                </div>
            </div>
        </div>
    </section>
@endsection
