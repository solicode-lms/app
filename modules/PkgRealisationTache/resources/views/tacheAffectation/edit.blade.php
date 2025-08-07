{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::tacheAffectation.singular'))
@section('content')
    @include('PkgRealisationTache::tacheAffectation._edit')
@endsection
