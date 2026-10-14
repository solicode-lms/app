{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgRealisationTache::revisionTache.singular'))
@section('content')
    @include('PkgRealisationTache::revisionTache._edit')
@endsection
