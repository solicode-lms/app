{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::transfertCompetence.singular'))
@section('content')
    @include('PkgCreationProjet::transfertCompetence._edit')
@endsection
