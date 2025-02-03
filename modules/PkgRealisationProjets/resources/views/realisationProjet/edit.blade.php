{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationProjets::realisationProjet.singular'))
@section('content')
    @include('PkgRealisationProjets::realisationProjet._edit')
@endsection
