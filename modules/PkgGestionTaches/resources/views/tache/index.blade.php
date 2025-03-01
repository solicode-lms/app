{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgGestionTaches::tache'))
@section('content')
    @include('PkgGestionTaches::tache._index')
@endsection
