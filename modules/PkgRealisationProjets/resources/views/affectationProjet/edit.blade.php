{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationProjets::affectationProjet.singular'))
@section('content')
    @include('PkgRealisationProjets::affectationProjet._edit')
@endsection
