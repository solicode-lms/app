{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgApprenants::apprenant'))
@section('content')
    @include('PkgApprenants::apprenant._index')
@endsection
