{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgCreationProjet::transfertCompetence.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('transfertCompetences.edit', $itemTransfertCompetence->id) }}" class="btn btn-default float-right">
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
                                <label for="appreciation_id">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.appreciation_id')) }}:</label>
                                <p>{{ $itemTransfertCompetence->appreciation_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="competence_id">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.competence_id')) }}:</label>
                                <p>{{ $itemTransfertCompetence->competence_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="description">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.description')) }}:</label>
                                <p>{{ $itemTransfertCompetence->description }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="projet_id">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.projet_id')) }}:</label>
                                <p>{{ $itemTransfertCompetence->projet_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
