{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::prioriteTache.singular'))
@section('content')
    @include('PkgRealisationTache::prioriteTache._edit')
@endsection
