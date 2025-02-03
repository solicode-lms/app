{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationProjets::livrablesRealisation.singular'))
@section('content')
    @include('PkgRealisationProjets::livrablesRealisation._edit')
@endsection
