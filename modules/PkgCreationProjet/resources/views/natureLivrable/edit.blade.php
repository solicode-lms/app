{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCreationProjet::natureLivrable.singular'))
@section('content')
    @include('PkgCreationProjet::natureLivrable._edit')
@endsection
