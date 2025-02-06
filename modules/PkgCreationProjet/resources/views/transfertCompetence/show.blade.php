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
                                <label for="competence_id">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.competence_id')) }}:</label>
                                <p>{{ $itemTransfertCompetence->competence_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="question">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.question')) }}:</label>
                                <p>{{ $itemTransfertCompetence->question }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="niveau_difficulte_id">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.niveau_difficulte_id')) }}:</label>
                                <p>{{ $itemTransfertCompetence->niveau_difficulte_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="note">{{ ucfirst(__('PkgCreationProjet::transfertCompetence.note')) }}:</label>
                                <p>{{ $itemTransfertCompetence->note }}</p>
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
