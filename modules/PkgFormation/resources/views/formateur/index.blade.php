{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgFormation::formateur'))
@section('content')
    @include('PkgFormation::formateur._index')
@endsection
