{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationProjets::validation.singular'))
@section('content')
    @include('PkgRealisationProjets::validation._edit')
@endsection
