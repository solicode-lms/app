{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgRealisationProjets::validation.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('validations.edit', $itemValidation->id) }}" class="btn btn-default float-right">
                        <i class="far fa-edit"></i>
                        {{ __('Core::msg.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <label for="transfert_competence_id">{{ ucfirst(__('PkgRealisationProjets::validation.transfert_competence_id')) }}:</label>
                                <p>{{ $itemValidation->transfert_competence_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="note">{{ ucfirst(__('PkgRealisationProjets::validation.note')) }}:</label>
                                <p>{{ $itemValidation->note }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="message">{{ ucfirst(__('PkgRealisationProjets::validation.message')) }}:</label>
                                <p>{{ $itemValidation->message }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="is_valide">{{ ucfirst(__('PkgRealisationProjets::validation.is_valide')) }}:</label>
                                <p>{{ $itemValidation->is_valide }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="realisation_projet_id">{{ ucfirst(__('PkgRealisationProjets::validation.realisation_projet_id')) }}:</label>
                                <p>{{ $itemValidation->realisation_projet_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
