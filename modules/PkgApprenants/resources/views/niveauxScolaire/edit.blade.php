{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgApprenants::niveauxScolaire.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'niveauxScolaire',
        filterFormSelector: '#niveauxScolaire-crud-filter-form',
        crudSelector: '#card-tab-niveauxScolaire', 
        formSelector: '#niveauxScolaireForm',
        editUrl: '{{ route('niveauxScolaires.edit',  ['niveauxScolaire' => ':id']) }}',
        indexUrl: '{{ route('niveauxScolaires.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::niveauxScolaire.singular") }}',
    });
</script>
@endpush


@section('content')
    <div class="content-header">
    <!-- debug
    @foreach ($contextState as $key => $value)
    Key: {{ $key }}, Value: {{ $value }}<br>
    @endforeach
     -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-niveauxScolaire" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-niveauxScolaire-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="niveauxScolaire-hasmany-tabs-home-tab" data-toggle="pill" href="#niveauxScolaire-hasmany-tabs-home" role="tab" aria-controls="niveauxScolaire-hasmany-tabs-home" aria-selected="true">{{__('PkgApprenants::niveauxScolaire.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="niveauxScolaire-hasmany-tabs-apprenant-tab" data-toggle="pill" href="#niveauxScolaire-hasmany-tabs-apprenant" role="tab" aria-controls="niveauxScolaire-hasmany-tabs-apprenant" aria-selected="false">{{__('PkgApprenants::apprenant.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-niveauxScolaire-tabContent">
                            <div class="tab-pane fade show active" id="niveauxScolaire-hasmany-tabs-home" role="tabpanel" aria-labelledby="niveauxScolaire-hasmany-tabs-home-tab">
                                @include('PkgApprenants::niveauxScolaire._fields')
                            </div>

                            <div class="tab-pane fade" id="niveauxScolaire-hasmany-tabs-apprenant" role="tabpanel" aria-labelledby="niveauxScolaire-hasmany-tabs-apprenant-tab">
                                @include('PkgApprenants::apprenant._index',['isMany' => true, "edit_has_many" => false])
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
